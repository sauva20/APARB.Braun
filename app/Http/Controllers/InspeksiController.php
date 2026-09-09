<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InspeksiController extends Controller
{
    private function getPertanyaan()
    {
        return [
            'Apakah APAR tersebut berada dalam lokasi yang mudah diakses dan terlihat dengan jelas?',
            'Apakah penunjuk tekanan pada APAR menunjukkan tekanan yang sesuai?',
            'Apakah segel keselamatan pada APAR terjaga dan tidak rusak?',
            'Apakah tabung APAR dalam kondisi baik dan tidak terdapat kerusakan fisik?',
            'Apakah spindel pengatur aliran pada APAR berfungsi dengan baik?',
            'Apakah tuas pemadam pada APAR dapat dioperasikan dengan mudah dan bebas dari kebocoran?',
            'Apakah nozzle atau alat semprot pada APAR tidak tersumbat atau rusak?',
            'Apakah selang pemadam pada APAR tidak terdapat kerusakan, sobek, atau kebocoran?',
            'Apakah label instruksi penggunaan APAR masih terpasang dan mudah dibaca?',
            'Apakah tanggal terakhir inspeksi APAR telah dicatat dan sesuai dengan jadwal inspeksi yang ditetapkan?',
            'Apakah APAR tersebut dilengkapi dengan segala perlengkapan tambahan yang diperlukan, seperti penyangga dinding atau bracket pemasangan?',
            'Apakah petunjuk penggunaan APAR dan tanda peringatan bahaya terkait penggunaan APAR tersedia dan mudah diakses?',
            'Apakah petugas yang bertanggung jawab terhadap APAR terlatih dalam penggunaan dan pemeliharaan APAR?',
            'Apakah daerah sekitar APAR bebas dari bahan yang mudah terbakar atau bahan yang dapat menghambat akses ke APAR?',
            'Apakah APAR tersebut telah diuji atau dirakit kembali setelah digunakan sebelumnya?'
        ];
    }

    public function index()
    {
        $gedungs = \App\Models\Gedung::with(['lokasi.apar.inspeksis' => function ($query) {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year)
                  ->latest();
        }, 'lokasi.apar.inspeksis.user'])->get();

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

        $jadwals = \App\Models\JadwalInspeksi::with(['gedung', 'lokasi'])->orderBy('tanggal_inspeksi', 'asc')->get();

        return view('inspection-schedule.index', compact('gedungs', 'totalApar', 'selesai', 'menunggu', 'pertanyaan', 'jadwals'));
    }

    public function create(\App\Models\Apar $apar)
    {
        $pertanyaan = $this->getPertanyaan();

        return view('inspeksi.mulai', compact('apar', 'pertanyaan'));
    }

    public function store(Request $request, \App\Models\Apar $apar)
    {
        $request->validate([
            'checklist' => 'required|array|size:15',
            'checklist.*.jawaban' => 'required|in:ya,tidak',
            'checklist.*.keterangan' => 'nullable|string',
            'status' => 'required|string',
            'catatan_tambahan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            if ($apar->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($apar->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($apar->foto);
            }
            $path = $request->file('foto')->store('foto-apar', 'public');
            $apar->update(['foto' => $path]);
        }

        $apar->inspeksis()->create([
            'user_id' => auth()->id(),
            'checklist' => $request->checklist,
            'status' => $request->status,
            'catatan_tambahan' => $request->catatan_tambahan
        ]);

        return redirect('/inspection-schedule')->with('success', 'Inspeksi berhasil disimpan!');
    }
    public function storeJadwal(Request $request)
    {
        $request->validate([
            'jenis_jadwal' => 'required|string',
            'tanggal' => 'required|date',
            'tipe_area' => 'required|in:gedung,lokasi',
            'gedung_id' => 'required_if:tipe_area,gedung',
            'lokasi_id' => 'required_if:tipe_area,lokasi',
            'petugas' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string',
        ]);

        \App\Models\JadwalInspeksi::create([
            'jenis_jadwal' => $request->jenis_jadwal,
            'tanggal_inspeksi' => $request->tanggal,
            'tipe_area' => $request->tipe_area,
            'gedung_id' => $request->tipe_area === 'gedung' ? $request->gedung_id : null,
            'lokasi_id' => $request->tipe_area === 'lokasi' ? $request->lokasi_id : null,
            'petugas' => $request->petugas,
            'catatan_tambahan' => $request->catatan_tambahan,
            'status' => 'menunggu'
        ]);

        return redirect('/inspection-schedule')->with('success', 'Jadwal inspeksi berhasil dibuat!');
    }

    public function updateJadwal(Request $request, \App\Models\JadwalInspeksi $jadwal)
    {
        $request->validate([
            'jenis_jadwal' => 'required|string',
            'tanggal' => 'required|date',
            'tipe_area' => 'required|in:gedung,lokasi',
            'gedung_id' => 'required_if:tipe_area,gedung',
            'lokasi_id' => 'required_if:tipe_area,lokasi',
            'petugas' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string',
        ]);

        $jadwal->update([
            'jenis_jadwal' => $request->jenis_jadwal,
            'tanggal_inspeksi' => $request->tanggal,
            'tipe_area' => $request->tipe_area,
            'gedung_id' => $request->tipe_area === 'gedung' ? $request->gedung_id : null,
            'lokasi_id' => $request->tipe_area === 'lokasi' ? $request->lokasi_id : null,
            'petugas' => $request->petugas,
            'catatan_tambahan' => $request->catatan_tambahan,
        ]);

        return redirect('/inspection-schedule')->with('success', 'Jadwal inspeksi berhasil diperbarui!');
    }

    public function destroyJadwal(\App\Models\JadwalInspeksi $jadwal)
    {
        $jadwal->delete();
        return redirect('/inspection-schedule')->with('success', 'Jadwal inspeksi berhasil dihapus!');
    }
}
