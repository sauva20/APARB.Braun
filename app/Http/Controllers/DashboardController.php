<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Apar;
use App\Models\Inspeksi;
use App\Models\JadwalInspeksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function getDashboardData(Request $request)
    {
        $now = Carbon::now();
        
        // 0. Filter Parameters
        $requestedYear = $request->input('year', $now->year);
        $requestedMonth = $request->input('month', $now->month);

        // Validasi: tidak boleh melihat masa depan (bulan/tahun lebih dari saat ini)
        if ($requestedYear > $now->year) {
            $selectedYear = $now->year;
            $selectedMonth = $now->month;
        } elseif ($requestedYear == $now->year && $requestedMonth > $now->month) {
            $selectedYear = $now->year;
            $selectedMonth = $now->month;
        } else {
            $selectedYear = $requestedYear;
            $selectedMonth = $requestedMonth;
        }
        // 1. Summary Cards Data
        $totalApar = Apar::count();
        
        $apars = Apar::with(['latestInspeksi', 'jenis'])->get();
        
        $kondisiBaik = 0;
        $rusakServis = 0;
        $akanKedaluwarsa = 0;
        $sudahKedaluwarsa = 0;
        $aparKosong = $apars->where('qty', '<', 1)->count();
        
        $now = Carbon::now();
        $thirtyDaysFromNow = $now->copy()->addDays(30);

        foreach ($apars as $apar) {
            // Status based on latest inspection
            if ($apar->latestInspeksi) {
                if ($apar->latestInspeksi->status === 'layak') {
                    $kondisiBaik++;
                } elseif (in_array($apar->latestInspeksi->status, ['rusak', 'perbaikan', 'isi_ulang'])) {
                    $rusakServis++;
                }
            }

            // Expiry Check
            if ($apar->tgl_kedaluwarsa) {
                if ($apar->tgl_kedaluwarsa->isPast()) {
                    $sudahKedaluwarsa++;
                } elseif ($apar->tgl_kedaluwarsa->between($now, $thirtyDaysFromNow)) {
                    $akanKedaluwarsa++;
                }
            }
        }

        // 2. Chart Data: Jenis APAR
        $jenisData = $apars->groupBy(function($item) {
            return $item->jenis->nama ?? 'Unknown';
        })->map->count();

        // 3. Chart Data: Yearly Inspections (Sudah vs Belum)
        $months = collect(range(1, 12))->map(function ($month) use ($selectedYear) {
            return Carbon::createFromDate($selectedYear, $month, 1);
        });

        $yearlyInspections = $months->map(function ($date) use ($totalApar) {
            // Batasi chart agar tidak menampilkan data di masa depan dalam tahun yg sama
            if ($date->isFuture()) {
                return [
                    'month' => $date->translatedFormat('M Y'),
                    'sudah' => 0,
                    'belum' => 0
                ];
            }

            $inspectedCount = Inspeksi::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->distinct('apar_id')
                ->count('apar_id');
                
            return [
                'month' => $date->translatedFormat('M Y'),
                'sudah' => $inspectedCount,
                'belum' => max(0, $totalApar - $inspectedCount)
            ];
        });

        // 4. Recent Activities (Latest 5 Inspections)
        $recentInspections = Inspeksi::with(['apar.lokasi', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // 5. Progress Inspeksi Bulan Ini
        $inspeksiBulanIniAparIds = Inspeksi::whereMonth('created_at', $selectedMonth)
            ->whereYear('created_at', $selectedYear)
            ->pluck('apar_id')
            ->unique();

        $sudahDiinspeksiBulanIni = $inspeksiBulanIniAparIds->count();
        $belumDiinspeksiBulanIni = max(0, $totalApar - $sudahDiinspeksiBulanIni);
        $persentaseInspeksi = $totalApar > 0 ? round(($sudahDiinspeksiBulanIni / $totalApar) * 100) : 0;

        $sudahDiinspeksiApars = Apar::with('lokasi.gedung')
            ->whereIn('id', $inspeksiBulanIniAparIds)
            ->get();
            
        $belumDiinspeksiApars = Apar::with('lokasi.gedung')
            ->whereNotIn('id', $inspeksiBulanIniAparIds)
            ->get();

        return compact(
            'totalApar',
            'kondisiBaik',
            'rusakServis',
            'akanKedaluwarsa',
            'sudahKedaluwarsa',
            'aparKosong',
            'jenisData',
            'yearlyInspections',
            'recentInspections',
            'sudahDiinspeksiBulanIni',
            'belumDiinspeksiBulanIni',
            'persentaseInspeksi',
            'sudahDiinspeksiApars',
            'belumDiinspeksiApars',
            'selectedMonth',
            'selectedYear'
        );
    }

    public function index(Request $request)
    {
        $data = $this->getDashboardData($request);
        
        if ($request->ajax()) {
            return response()->json([
                'stats' => [
                    'totalApar' => $data['totalApar'],
                    'kondisiBaik' => $data['kondisiBaik'],
                    'kondisiBaikPercentage' => $data['totalApar'] > 0 ? round(($data['kondisiBaik'] / $data['totalApar']) * 100) : 0,
                    'rusakServis' => $data['rusakServis'],
                    'akanKedaluwarsa' => $data['akanKedaluwarsa'],
                    'sudahKedaluwarsa' => $data['sudahKedaluwarsa'],
                    'aparKosong' => $data['aparKosong'],
                    'sudahDiinspeksiBulanIni' => $data['sudahDiinspeksiBulanIni'],
                    'belumDiinspeksiBulanIni' => $data['belumDiinspeksiBulanIni'],
                ],
                'charts' => [
                    'yearlyInspections' => $data['yearlyInspections'],
                    'jenisData' => $data['jenisData'],
                ],
                'html' => [
                    'recentInspections' => view('dashboard.partials.recent-inspections', ['recentInspections' => $data['recentInspections']])->render(),
                    'uninspectedTable' => view('dashboard.partials.uninspected-table', ['belumDiinspeksiApars' => $data['belumDiinspeksiApars']])->render(),
                    'inspectedTable' => view('dashboard.partials.inspected-table', ['sudahDiinspeksiApars' => $data['sudahDiinspeksiApars']])->render(),
                ]
            ]);
        }
        
        return view('dashboard.index', $data);
    }

    public function displayReport(Request $request)
    {
        $data = $this->getDashboardData($request);
        return view('dashboard.display-report', $data);
    }
}
