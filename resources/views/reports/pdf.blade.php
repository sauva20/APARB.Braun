<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inspeksi APAR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #009B77;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #009B77;
            margin: 0 0 5px 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #555;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 6px;
            text-align: left;
        }
        th {
            background-color: #e8f5f3;
            color: #009B77;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            text-align: center;
        }
        .text-center { text-align: center; }
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .bg-green { background-color: #d1fae5; color: #059669; }
        .bg-red { background-color: #fee2e2; color: #dc2626; }
        .bg-orange { background-color: #fef3c7; color: #d97706; }
        
        .footer {
            margin-top: 40px;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Inspeksi APAR Bulan {{ $monthName }}</h1>
        <p>Identification of Inspection Portable Fire Extinguisher</p>
    </div>

    @php
        $activeFilters = [];
        if (!empty($monthName)) $activeFilters[] = $monthName;
        if (!empty($gedungName) && $gedungName !== 'Semua Gedung') $activeFilters[] = $gedungName;
        if (!empty($status) && $status !== 'all') {
            if ($status === 'sudah') $activeFilters[] = 'Sudah Diinspeksi';
            elseif ($status === 'belum') $activeFilters[] = 'Belum Diinspeksi';
            else $activeFilters[] = $status;
        }
    @endphp

    <table style="width: 100%; margin-bottom: 8px; border: none;">
        <tr>
            <td style="text-align: left; font-size: 11px; border: none !important; padding: 0 !important; background: transparent;">
                Identification Date: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i:s') }}
            </td>
            <td style="text-align: right; font-size: 11px; border: none !important; padding: 0 !important; background: transparent;">
                @if(count($activeFilters) > 0)
                    {{ implode(' | ', $activeFilters) }}
                @endif
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="8%">ID APAR</th>
                <th width="6%">Gedung</th>
                <th width="8%">Lokasi</th>
                <th width="12%">Jenis</th>
                <th width="8%">Kapasitas</th>
                <th width="8%">Status</th>
                <th width="12%">Tgl Inspeksi</th>
                <th width="10%">Inspektor</th>
                <th width="7%">Kondisi</th>
                <th width="30%">Catatan Tambahan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $index => $row)
                @php 
                    $apar = $row['apar'];
                    $inspeksi = $row['inspeksi'];
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><b>{{ $apar->kode }}</b></td>
                    <td><b>{{ $apar->lokasi->gedung->nama ?? 'n/a' }}</b></td>
                    <td>{{ $apar->lokasi->nama ?? 'n/a' }}</td>
                    <td>{{ $apar->jenis->nama ?? 'n/a' }}</td>
                    <td>{{ $apar->kapasitas->ukuran ?? 'n/a' }}</td>
                    <td class="text-center">
                        @if($row['status'] == 'Sudah Diinspeksi')
                            <span class="badge bg-green">Sudah</span>
                        @else
                            <span class="badge bg-orange">Belum</span>
                        @endif
                    </td>
                    <td>
                        {{ $inspeksi ? \Carbon\Carbon::parse($inspeksi->created_at)->format('d-m-Y H:i') : 'n/a' }}
                    </td>
                    <td>{{ $inspeksi->user->name ?? 'n/a' }}</td>
                    <td>
                        @if($inspeksi)
                            @if($inspeksi->status == 'layak')
                                <span style="color: #009B77; font-weight: bold; font-size: 8px;">LAYAK</span>
                            @else
                                <span style="color: #EF4444; font-weight: bold; font-size: 8px;">PERBAIKAN</span>
                            @endif
                        @else
                            n/a
                        @endif
                    </td>
                    <td style="font-size: 8px;">
                        {{ $inspeksi->catatan_tambahan ?? 'n/a' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data untuk periode dan filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
