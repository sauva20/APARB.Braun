<?php

namespace App\Http\Controllers;

use App\Mail\InspectionAssigned;
use App\Models\Apar;
use App\Models\Gedung;
use App\Models\JadwalInspeksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InspeksiController extends Controller
{
    private function getPertanyaan()
    {
        return [
            __('Apakah APAR tersebut berada dalam lokasi yang mudah diakses dan terlihat dengan jelas?'),
            __('Apakah penunjuk tekanan pada APAR menunjukkan tekanan yang sesuai?'),
            __('Apakah segel keselamatan pada APAR terjaga dan tidak rusak?'),
            __('Apakah tabung APAR dalam kondisi baik dan tidak terdapat kerusakan fisik?'),
            __('Apakah spindel pengatur aliran pada APAR berfungsi dengan baik?'),
            __('Apakah tuas pemadam pada APAR dapat dioperasikan dengan mudah dan bebas dari kebocoran?'),
            __('Apakah nozzle atau alat semprot pada APAR tidak tersumbat atau rusak?'),
            __('Apakah selang pemadam pada APAR tidak terdapat kerusakan, sobek, atau kebocoran?'),
            __('Apakah label instruksi penggunaan APAR masih terpasang dan mudah dibaca?'),
            __('Apakah tanggal terakhir inspeksi APAR telah dicatat dan sesuai dengan jadwal inspeksi yang ditetapkan?'),
            __('Apakah APAR tersebut dilengkapi dengan segala perlengkapan tambahan yang diperlukan, seperti penyangga dinding atau bracket pemasangan?'),
            __('Apakah petunjuk penggunaan APAR dan tanda peringatan bahaya terkait penggunaan APAR tersedia dan mudah diakses?'),
            __('Apakah petugas yang bertanggung jawab terhadap APAR terlatih dalam penggunaan dan pemeliharaan APAR?'),
            __('Apakah daerah sekitar APAR bebas dari bahan yang mudah terbakar atau bahan yang dapat menghambat akses ke APAR?'),
            __('Apakah APAR tersebut telah diuji atau dirakit kembali setelah digunakan sebelumnya?'),
            
            // 10 Item Pemeriksaan Fisik (Langkah 1)
            __('Jenis APAR'),
            __('Kapasitas'),
            __('Tanggal isi ulang terakhir'),
            __('Tanggal masa berlaku'),
            __('Segel keselamatan'),
            __('Karat pada tabung'),
            __('Kepadatan isi dalam tabung'),
            __('Nozzle'),
            __('Pin pengaman'),
            __('Lokasi APAR'),
        ];
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $currentMonth = (int) $request->input('month', now()->month);
        $currentYear = (int) $request->input('year', now()->year);

        $query = Gedung::query();

        // Jika Staff, hanya ambil gedung yang ditugaskan ke dia
        if (auth()->user()->role === 'Staff') {
            $query->whereHas('users', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        $gedungs = $query->with([
            'lokasi.apar',
            'lokasi.apar.inspeksis' => function ($query) use ($currentMonth, $currentYear) {
                $query->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->latest();
            }, 
            'lokasi.apar.inspeksis.user'
        ])->get();

        // Filter out empty gedungs and lokasis for cleaner UI
        $gedungs = $gedungs->filter(function ($gedung) {
            $gedung->lokasi = $gedung->lokasi->filter(function ($lokasi) {
                return $lokasi->apar->isNotEmpty();
            });
            return $gedung->lokasi->isNotEmpty();
        });

        $totalApar = 0;
        $selesai = 0;

        foreach ($gedungs as $gedung) {
            foreach ($gedung->lokasi as $lokasi) {
                foreach ($lokasi->apar as $apar) {
                    $totalApar++;
                    if ($apar->inspeksis->isNotEmpty()) {
                        $selesai++;
                    }
                }
            }
        }

        $menunggu = $totalApar - $selesai;
        $pertanyaan = $this->getPertanyaan();

        $jadwalsQuery = JadwalInspeksi::with(['gedung', 'lokasi', 'user'])
            ->whereMonth('tanggal_inspeksi', $currentMonth)
            ->whereYear('tanggal_inspeksi', $currentYear)
            ->orderBy('tanggal_inspeksi', 'asc');
        $jadwals = $jadwalsQuery->get();
        $users = User::with('gedungs')->get();

        // Hitung progres untuk jadwal asli (Ad-Hoc)
        foreach ($jadwals as $jadwal) {
            $aparsInSchedule = $jadwal->tipe_area === 'gedung' 
                ? Apar::whereHas('lokasi', fn($q) => $q->where('gedung_id', $jadwal->gedung_id))->get()
                : Apar::where('lokasi_id', $jadwal->lokasi_id)->get();
            
            $jadwalMonth = \Carbon\Carbon::parse($jadwal->tanggal_inspeksi)->month;
            $jadwalYear = \Carbon\Carbon::parse($jadwal->tanggal_inspeksi)->year;

            $totalAparInArea = $aparsInSchedule->count();
            $inspectedCount = 0;

            foreach ($aparsInSchedule as $a) {
                if ($a->inspeksis()->whereMonth('created_at', $jadwalMonth)->whereYear('created_at', $jadwalYear)->exists()) {
                    $inspectedCount++;
                }
            }

            $jadwal->total_apar = $totalAparInArea;
            $jadwal->inspected_apar = $inspectedCount;

            if ($totalAparInArea > 0 && $inspectedCount === $totalAparInArea) {
                $jadwal->status = 'selesai';
            } elseif ($inspectedCount > 0) {
                $jadwal->status = 'proses';
            }
        }

        // Gabungkan jadwal rutin dari profil Staff ke dalam daftar jadwal
        foreach ($users as $user) {
            if ($user->role === 'Staff' && $user->jadwal_rutin_tanggal) {
                $maxDays = \Carbon\Carbon::create($currentYear, $currentMonth, 1)->daysInMonth;
                $tgl = min($user->jadwal_rutin_tanggal, $maxDays);
                $tanggalRutin = \Carbon\Carbon::create($currentYear, $currentMonth, $tgl)->format('Y-m-d');
                
                foreach ($user->gedungs as $gedung) {
                    $mockJadwal = new JadwalInspeksi([
                        'jenis_jadwal' => 'Inspeksi Rutin Bulanan',
                        'tanggal_inspeksi' => $tanggalRutin,
                        'tipe_area' => 'gedung',
                        'gedung_id' => $gedung->id,
                        'user_id' => $user->id,
                        'catatan_tambahan' => 'Jadwal rutin bulanan otomatis',
                        'status' => 'menunggu'
                    ]);
                    $mockJadwal->id = 'rutin_'.$user->id.'_'.$gedung->id; 
                    $mockJadwal->setRelation('gedung', $gedung);
                    $mockJadwal->setRelation('user', $user);
                    $mockJadwal->is_rutin_virtual = true;

                    // Cek progres
                    $aparsInGedung = $gedung->lokasi->flatMap->apar;
                    $totalAparInArea = $aparsInGedung->count();
                    $inspectedCount = 0;

                    foreach ($aparsInGedung as $a) {
                        if ($a->inspeksis()->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->exists()) {
                            $inspectedCount++;
                        }
                    }

                    $mockJadwal->total_apar = $totalAparInArea;
                    $mockJadwal->inspected_apar = $inspectedCount;

                    if ($totalAparInArea > 0 && $inspectedCount === $totalAparInArea) {
                        $mockJadwal->status = 'selesai';
                    } elseif ($inspectedCount > 0) {
                        $mockJadwal->status = 'proses';
                    }

                    $jadwals->push($mockJadwal);
                }
            }
        }

        // Urutkan ulang setelah digabung
        $jadwals = $jadwals->sortBy('tanggal_inspeksi')->values();

        return view('inspection-schedule.index', compact('gedungs', 'totalApar', 'selesai', 'menunggu', 'pertanyaan', 'jadwals', 'users', 'currentMonth', 'currentYear'));
    }

    public function pedoman(Apar $apar)
    {
        return view('inspeksi.pedoman', compact('apar'));
    }

    public function create(Apar $apar)
    {
        $pertanyaan = $this->getPertanyaan();
        $gedungs = \App\Models\Gedung::with('lokasi')->get();
        $jenisApars = \App\Models\JenisApar::all();
        $kapasitasApars = \App\Models\KapasitasApar::all();

        return view('inspeksi.mulai', compact('apar', 'pertanyaan', 'gedungs', 'jenisApars', 'kapasitasApars'));
    }

    public function store(Request $request, Apar $apar)
    {
        $request->validate([
            'checklist' => 'required|array|size:25',
            'checklist.*.jawaban' => 'required|in:ya,tidak,ada,tidak ada',
            'checklist.*.keterangan' => 'nullable|string',
            'status' => 'required|string',
            'catatan_tambahan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
            'foto_base64' => 'required|string',
            'qty' => 'required|integer|min:0',
            'tgl_kedaluwarsa' => 'required|date',
        ], [
            'foto_base64.required' => __('Foto kondisi APAR wajib diambil sebelum submit.'),
        ]);

        if ($request->hasFile('foto')) {
            if ($apar->foto && Storage::disk('public')->exists($apar->foto)) {
                Storage::disk('public')->delete($apar->foto);
            }
            $path = $request->file('foto')->store('foto-apar', 'public');
            $apar->update(['foto' => $path]);
        } elseif ($request->filled('foto_base64')) {
            if ($apar->foto && Storage::disk('public')->exists($apar->foto)) {
                Storage::disk('public')->delete($apar->foto);
            }
            $image_parts = explode(";base64,", $request->foto_base64);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1] ?? 'jpeg';
            $image_base64 = base64_decode($image_parts[1]);
            $path = 'foto-apar/' . uniqid() . '.' . $image_type;
            Storage::disk('public')->put($path, $image_base64);
            $apar->update(['foto' => $path]);
        }

        // Update qty, tgl_kedaluwarsa, and pic_id to the person who inspected
        $apar->update([
            'qty' => $request->qty,
            'tgl_kedaluwarsa' => $request->tgl_kedaluwarsa,
            'pic_id' => auth()->id()
        ]);

        $apar->inspeksis()->create([
            'user_id' => auth()->id(),
            'checklist' => $request->checklist,
            'status' => $request->status,
            'catatan_tambahan' => $request->catatan_tambahan,
        ]);

        // Check if related JadwalInspeksi should be marked as selesai
        $jadwals = JadwalInspeksi::where('status', 'menunggu')
            ->where(function($q) use ($apar) {
                $q->where(function($qGedung) use ($apar) {
                    $qGedung->where('tipe_area', 'gedung')->where('gedung_id', $apar->lokasi->gedung_id);
                })->orWhere(function($qLokasi) use ($apar) {
                    $qLokasi->where('tipe_area', 'lokasi')->where('lokasi_id', $apar->lokasi_id);
                });
            })->get();

        foreach ($jadwals as $jadwal) {
            $aparsInSchedule = $jadwal->tipe_area === 'gedung' 
                ? Apar::whereHas('lokasi', fn($q) => $q->where('gedung_id', $jadwal->gedung_id))->get()
                : Apar::where('lokasi_id', $jadwal->lokasi_id)->get();
            
            $allInspected = $aparsInSchedule->every(function ($a) use ($jadwal) {
                return $a->inspeksis()
                    ->whereMonth('created_at', \Carbon\Carbon::parse($jadwal->tanggal_inspeksi)->month)
                    ->whereYear('created_at', \Carbon\Carbon::parse($jadwal->tanggal_inspeksi)->year)
                    ->exists();
            });

            if ($allInspected && $aparsInSchedule->isNotEmpty()) {
                $jadwal->update(['status' => 'selesai']);
            }
        }

        return redirect()->route('inspeksi.sukses', $apar->id);
    }

    public function sukses(Apar $apar)
    {
        return view('inspeksi.sukses', compact('apar'));
    }

    public function storeJadwal(Request $request)
    {
        $request->validate([
            'jenis_jadwal' => 'required|string',
            'tanggal' => 'required|date',
            'areas' => 'required|array|min:1',
            'user_id' => 'required|exists:users,id',
            'catatan_tambahan' => 'nullable|string',
        ]);

        $jadwals = collect();

        foreach ($request->areas as $area) {
            $isGedung = str_starts_with($area, 'g_');
            $id = substr($area, 2);

            $jadwal = JadwalInspeksi::create([
                'jenis_jadwal' => $request->jenis_jadwal,
                'tanggal_inspeksi' => $request->tanggal,
                'tipe_area' => $isGedung ? 'gedung' : 'lokasi',
                'gedung_id' => $isGedung ? $id : null,
                'lokasi_id' => !$isGedung ? $id : null,
                'user_id' => $request->user_id,
                'catatan_tambahan' => $request->catatan_tambahan,
                'status' => 'menunggu',
            ]);
            
            $jadwals->push($jadwal);
        }

        $user = User::find($request->user_id);
        if ($user && $user->email && $jadwals->isNotEmpty()) {
            Mail::to($user->email)->send(new InspectionAssigned($user, $jadwals, $user->pin));
        }

        return redirect('/inspection-schedule')->with('success', __('Jadwal inspeksi berhasil dibuat dan notifikasi email telah dikirim!'));
    }

    public function updateJadwal(Request $request, JadwalInspeksi $jadwal)
    {
        $request->validate([
            'jenis_jadwal' => 'required|string',
            'tanggal' => 'required|date',
            'tipe_area' => 'required|in:gedung,lokasi',
            'gedung_id' => 'required_if:tipe_area,gedung',
            'lokasi_id' => 'required_if:tipe_area,lokasi',
            'user_id' => 'required|exists:users,id',
            'catatan_tambahan' => 'nullable|string',
        ]);

        $jadwal->update([
            'jenis_jadwal' => $request->jenis_jadwal,
            'tanggal_inspeksi' => $request->tanggal,
            'tipe_area' => $request->tipe_area,
            'gedung_id' => $request->tipe_area === 'gedung' ? $request->gedung_id : null,
            'lokasi_id' => $request->tipe_area === 'lokasi' ? $request->lokasi_id : null,
            'user_id' => $request->user_id,
            'catatan_tambahan' => $request->catatan_tambahan,
        ]);

        return redirect('/inspection-schedule')->with('success', __('Jadwal inspeksi berhasil diperbarui!'));
    }

    public function destroyJadwal($id)
    {
        $jadwal = JadwalInspeksi::find($id);
        
        if ($jadwal) {
            $jadwal->delete();
            return redirect('/inspection-schedule')->with('success', __('Jadwal inspeksi berhasil dihapus!'));
        }

        return redirect('/inspection-schedule');
    }
}
