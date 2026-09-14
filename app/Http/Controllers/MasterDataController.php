<?php

namespace App\Http\Controllers;

use App\Models\Apar;
use App\Models\Gedung;
use App\Models\JenisApar;
use App\Models\KapasitasApar;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MasterDataController extends Controller
{
    public function index(Request $request)
    {
        $gedungs = Gedung::all();
        $lokasis = Lokasi::with('gedung')->get();
        $jenisApars = JenisApar::all();
        $kapasitasApars = KapasitasApar::all();

        $query = Apar::with(['lokasi.gedung', 'jenis', 'kapasitas', 'latestInspeksi.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhereHas('lokasi', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%")
                            ->orWhereHas('gedung', function ($q3) use ($search) {
                                $q3->where('nama', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($request->filled('gedung_id')) {
            $query->whereHas('lokasi', function ($q) use ($request) {
                $q->where('gedung_id', $request->gedung_id);
            });
        }

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        if ($request->filled('kapasitas_id')) {
            $query->where('kapasitas_id', $request->kapasitas_id);
        }

        if ($request->filled('jenis_id')) {
            $query->where('jenis_id', $request->jenis_id);
        }

        $apars = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();

        return view('master-data.index', compact('gedungs', 'lokasis', 'jenisApars', 'kapasitasApars', 'apars'));
    }

    public function exportPdf(Request $request)
    {
        $query = Apar::with(['lokasi.gedung', 'jenis', 'kapasitas', 'latestInspeksi.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhereHas('lokasi', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%")
                            ->orWhereHas('gedung', function ($q3) use ($search) {
                                $q3->where('nama', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($request->filled('gedung_id')) {
            $query->whereHas('lokasi', function ($q) use ($request) {
                $q->where('gedung_id', $request->gedung_id);
            });
        }

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        if ($request->filled('kapasitas_id')) {
            $query->where('kapasitas_id', $request->kapasitas_id);
        }

        if ($request->filled('jenis_id')) {
            $query->where('jenis_id', $request->jenis_id);
        }

        $apars = $query->orderBy('id', 'asc')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('master-data.pdf', compact('apars'))->setPaper('a4', 'portrait');
        return $pdf->stream('Data_APAR_' . date('Ymd_His') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = Apar::with(['lokasi.gedung', 'jenis', 'kapasitas', 'latestInspeksi.user', 'pic']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhereHas('lokasi', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%")
                            ->orWhereHas('gedung', function ($q3) use ($search) {
                                $q3->where('nama', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($request->filled('gedung_id')) {
            $query->whereHas('lokasi', function ($q) use ($request) {
                $q->where('gedung_id', $request->gedung_id);
            });
        }

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        if ($request->filled('kapasitas_id')) {
            $query->where('kapasitas_id', $request->kapasitas_id);
        }

        if ($request->filled('jenis_id')) {
            $query->where('jenis_id', $request->jenis_id);
        }

        $apars = $query->orderBy('id', 'asc')->get();

        $filename = "Data_APAR_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No', 'ID APAR', 'Lokasi', 'Gedung', 'Jenis', 'Kapasitas', 'Kelas Kebakaran', 'Tgl Kedaluwarsa', 'Qty', 'Vendor', 'PIC', 'Terakhir Inspeksi'];

        $callback = function() use($apars, $columns) {
            $file = fopen('php://output', 'w');
            // add BOM to fix UTF-8 in Excel
            fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
            fputcsv($file, $columns);

            foreach ($apars as $index => $apar) {
                $jenisNamaL = strtolower($apar->jenis->nama ?? '');
                $kelas = '-';
                if (strpos($jenisNamaL, 'dry chemical') !== false || strpos($jenisNamaL, 'powder') !== false) {
                    $kelas = 'A-B-C';
                } elseif (strpos($jenisNamaL, 'carbon') !== false || strpos($jenisNamaL, 'dioxide') !== false || strpos($jenisNamaL, 'co2') !== false) {
                    $kelas = 'B-C';
                }

                $lastInspeksi = $apar->latestInspeksi;
                $picName = '-';
                if ($lastInspeksi && $lastInspeksi->user) {
                    $picName = $lastInspeksi->user->name;
                } elseif ($apar->pic) {
                    $picName = $apar->pic->name;
                }

                $row = [
                    $index + 1,
                    $apar->kode,
                    $apar->lokasi->nama ?? '-',
                    $apar->lokasi->gedung->nama ?? '-',
                    $apar->jenis->nama ?? '-',
                    $apar->kapasitas->ukuran ?? '-',
                    $kelas,
                    $apar->tgl_kedaluwarsa ? $apar->tgl_kedaluwarsa->format('d M Y') : '-',
                    $apar->qty,
                    $apar->vendor ?? '-',
                    $picName,
                    $lastInspeksi ? $lastInspeksi->created_at->format('d M Y') : '-'
                ];

                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function storeGedung(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:gedung,nama'], ['nama.unique' => 'Nama gedung ini sudah terdaftar.']);
        Gedung::create($request->only('nama'));

        return redirect()->back()->with('success', 'Data gedung berhasil ditambahkan!');
    }

    public function storeLokasi(Request $request)
    {
        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('lokasi')->where(fn ($query) => $query->where('gedung_id', $request->gedung_id)),
            ],
            'gedung_id' => 'required|exists:gedung,id',
        ], [
            'nama.unique' => 'Lokasi dengan nama ini sudah ada di gedung yang dipilih.',
        ]);
        Lokasi::create($request->only('nama', 'gedung_id'));

        return redirect()->back()->with('success', 'Data lokasi berhasil ditambahkan!');
    }

    public function storeJenis(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:jenis_apar,nama'], ['nama.unique' => 'Jenis APAR ini sudah terdaftar.']);
        JenisApar::create($request->only('nama'));

        return redirect()->back()->with('success', 'Jenis APAR berhasil ditambahkan!');
    }

    public function storeKapasitas(Request $request)
    {
        if ($request->has('ukuran')) {
            $ukuran = trim($request->ukuran);
            if (! preg_match('/(?i)kg$/', $ukuran)) {
                $request->merge(['ukuran' => trim($ukuran).' Kg']);
            }
        }

        $request->validate(['ukuran' => 'required|string|max:255|unique:kapasitas_apar,ukuran'], ['ukuran.unique' => 'Kapasitas APAR ini sudah terdaftar.']);
        KapasitasApar::create($request->only('ukuran'));

        return redirect()->back()->with('success', 'Kapasitas APAR berhasil ditambahkan!');
    }

    public function destroyGedung(Gedung $gedung)
    {
        $gedung->delete();

        return redirect()->back()->with('success', 'Data gedung berhasil dihapus!');
    }

    public function destroyLokasi(Lokasi $lokasi)
    {
        $lokasi->delete();

        return redirect()->back()->with('success', 'Data lokasi berhasil dihapus!');
    }

    public function destroyJenis(JenisApar $jenisApar)
    {
        $jenisApar->delete();

        return redirect()->back()->with('success', 'Jenis APAR berhasil dihapus!');
    }

    public function destroyKapasitas(KapasitasApar $kapasitasApar)
    {
        $kapasitasApar->delete();

        return redirect()->back()->with('success', 'Kapasitas APAR berhasil dihapus!');
    }

    public function getQrData(Apar $apar)
    {
        $url = route('scan.apar', $apar->kode);
        $svg = QrCode::size(250)->generate($url);

        return response()->json([
            'id' => $apar->id,
            'kode' => $apar->kode,
            'svg' => (string) $svg,
        ]);
    }

    public function downloadQr(Apar $apar)
    {
        $url = route('scan.apar', $apar->kode);
        $svg = QrCode::size(500)->generate($url);

        // Return as downloadable SVG file
        return response((string) $svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="QR_'.$apar->kode.'.svg"');
    }

    public function printAllQr()
    {
        $apars = Apar::with(['lokasi.gedung', 'jenis', 'kapasitas'])->orderBy('kode')->get();

        return view('master-data.print-all', compact('apars'));
    }

    public function printSingleQr(Apar $apar)
    {
        $apar->load(['lokasi.gedung', 'jenis', 'kapasitas']);

        return view('master-data.print-single', compact('apar'));
    }

    public function destroyApar(Apar $apar)
    {
        $apar->delete();

        return redirect()->back()->with('success', 'Data APAR berhasil dihapus!');
    }

    public function storeApar(Request $request)
    {
        $request->validate([
            'gedung_id' => 'required|exists:gedung,id',
            'lokasi' => 'required|string|max:255',
            'jenis_id' => 'required|exists:jenis_apar,id',
            'kapasitas_id' => 'required|exists:kapasitas_apar,id',
            'vendor' => 'nullable|string|max:255',
            'tgl_kedaluwarsa' => 'nullable|date',
            'nomor_apar' => 'required|string|max:10',
        ]);

        $lokasi = Lokasi::firstOrCreate([
            'gedung_id' => $request->gedung_id,
            'nama' => $request->lokasi,
        ]);

        $prefix = $this->generatePrefix($lokasi->id, $request->jenis_id);
        $kode = $prefix . $request->nomor_apar;

        // Cek unik
        if (Apar::where('kode', $kode)->exists()) {
            return redirect()->back()->withErrors(['nomor_apar' => 'Nomor APAR ini sudah digunakan untuk prefix ' . $prefix])->withInput()->with('form_type', 'tambah_apar');
        }

        $data = $request->except(['kode', 'nomor_apar', 'form_type', 'gedung_id', 'lokasi']);
        $data['kode'] = $kode;
        $data['lokasi_id'] = $lokasi->id;
        $data['pic_id'] = auth()->id();

        if (empty($data['vendor'])) {
            $data['vendor'] = 'N/A';
        }

        Apar::create($data);

        return redirect()->back()->with('success', 'Data APAR berhasil ditambahkan!');
    }

    public function updateGedung(Request $request, Gedung $gedung)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:gedung,nama,'.$gedung->id], ['nama.unique' => 'Nama gedung ini sudah terdaftar.']);
        $gedung->update($request->only('nama'));

        return redirect()->back()->with('success', 'Data gedung berhasil diperbarui!');
    }

    public function updateLokasi(Request $request, Lokasi $lokasi)
    {
        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('lokasi')->where(fn ($query) => $query->where('gedung_id', $request->gedung_id)),
            ],
            'gedung_id' => 'required|exists:gedung,id',
        ], [
            'nama.unique' => 'Lokasi dengan nama ini sudah ada di gedung yang dipilih.',
        ]);
        $lokasi->update($request->only('nama', 'gedung_id'));

        return redirect()->back()->with('success', 'Data lokasi berhasil diperbarui!');
    }

    public function updateJenis(Request $request, JenisApar $jenisApar)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:jenis_apar,nama,'.$jenisApar->id], ['nama.unique' => 'Jenis APAR ini sudah terdaftar.']);
        $jenisApar->update($request->only('nama'));

        return redirect()->back()->with('success', 'Jenis APAR berhasil diperbarui!');
    }

    public function updateKapasitas(Request $request, KapasitasApar $kapasitasApar)
    {
        if ($request->has('ukuran')) {
            $ukuran = trim($request->ukuran);
            if (! preg_match('/(?i)kg$/', $ukuran)) {
                $request->merge(['ukuran' => trim($ukuran).' Kg']);
            }
        }

        $request->validate(['ukuran' => 'required|string|max:255|unique:kapasitas_apar,ukuran,'.$kapasitasApar->id], ['ukuran.unique' => 'Kapasitas APAR ini sudah terdaftar.']);
        $kapasitasApar->update($request->only('ukuran'));

        return redirect()->back()->with('success', 'Kapasitas APAR berhasil diperbarui!');
    }

    public function updateApar(Request $request, Apar $apar)
    {
        $request->validate([
            'gedung_id' => 'required|exists:gedung,id',
            'lokasi' => 'required|string|max:255',
            'jenis_id' => 'required|exists:jenis_apar,id',
            'kapasitas_id' => 'required|exists:kapasitas_apar,id',
            'vendor' => 'nullable|string|max:255',
            'tgl_kedaluwarsa' => 'nullable|date',
            'nomor_apar' => 'required|string|max:10',
        ]);

        $lokasi = Lokasi::firstOrCreate([
            'gedung_id' => $request->gedung_id,
            'nama' => $request->lokasi,
        ]);

        $data = $request->except(['kode', 'nomor_apar', 'form_type', 'gedung_id', 'lokasi']);
        $data['lokasi_id'] = $lokasi->id;
        $data['pic_id'] = auth()->id();

        if (empty($data['vendor'])) {
            $data['vendor'] = 'N/A';
        }

        $prefix = $this->generatePrefix($lokasi->id, $request->jenis_id);
        $newKode = $prefix . $request->nomor_apar;

        // Cek unik untuk kode yang baru jika ada perubahan
        if ($newKode !== $apar->kode && Apar::where('kode', $newKode)->where('id', '!=', $apar->id)->exists()) {
            return redirect()->back()->withErrors(['nomor_apar' => 'Nomor APAR ini sudah digunakan untuk prefix ' . $prefix])->withInput()->with('form_type', 'edit_apar')->with('id', $apar->id);
        }

        $data['kode'] = $newKode;

        $apar->update($data);

        return redirect()->back()->with('success', 'Data APAR berhasil diperbarui!');
    }

    private function generatePrefix($lokasi_id, $jenis_id)
    {
        $lokasi = Lokasi::with('gedung')->find($lokasi_id);
        $jenis = JenisApar::find($jenis_id);

        $gedungNama = $lokasi->gedung->nama ?? '';
        $gedungCode = '';
        if (strpos(strtoupper($gedungNama), 'BU-') !== false) {
            $parts = explode('BU-', strtoupper($gedungNama));
            $gedungCode = trim($parts[1] ?? '');
        }
        if (empty($gedungCode)) {
            $gedungCode = substr(strtoupper($gedungNama), 0, 1);
        }

        $jenisCode = 'C';
        $jenisNamaL = strtolower($jenis->nama ?? '');
        if (strpos($jenisNamaL, 'dry chemical') !== false || strpos($jenisNamaL, 'powder') !== false) {
            $jenisCode = 'A';
        } elseif (strpos($jenisNamaL, 'carbon') !== false || strpos($jenisNamaL, 'dioxide') !== false || strpos($jenisNamaL, 'co2') !== false) {
            $jenisCode = 'B';
        } else {
            $jenisCode = substr(strtoupper($jenis->nama ?? 'C'), 0, 1);
        }

        $buildingPrefix = "PFE-{$gedungCode}-";
        return $buildingPrefix . $jenisCode;
    }

    public function history(Apar $apar)
    {
        // Hanya ambil 1 data aktivitas terakhir (yang paling baru)
        $activities = $apar->activities()->with('causer')->latest()->take(1)->get();
        return view('master-data.history-partial', compact('apar', 'activities'));
    }
}
