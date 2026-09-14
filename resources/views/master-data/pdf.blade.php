<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data APAR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #009B77;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #009B77;
            color: white;
            padding: 8px;
            text-align: left;
            text-transform: uppercase;
        }
        td {
            padding: 8px;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .text-red {
            color: #e3342f;
        }
    </style>
</head>
<body>

    <h2>Laporan Data APAR</h2>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 12%;">ID APAR</th>
                <th style="width: 14%;">Lokasi</th>
                <th style="width: 14%;">Gedung</th>
                <th style="width: 13%;">Jenis</th>
                <th style="width: 10%;">Kapasitas</th>
                <th class="text-center" style="width: 8%;">Kelas</th>
                <th style="width: 12%;">Tgl Kedaluwarsa</th>
                <th class="text-center" style="width: 5%;">Qty</th>
                <th style="width: 12%;">PIC</th>
            </tr>
        </thead>
        <tbody>
            @foreach($apars as $index => $apar)
                @php
                    $jenisNamaL = strtolower($apar->jenis->nama ?? '');
                    $kelas = '-';
                    if (strpos($jenisNamaL, 'dry chemical') !== false || strpos($jenisNamaL, 'powder') !== false) {
                        $kelas = 'A-B-C';
                    } elseif (strpos($jenisNamaL, 'carbon') !== false || strpos($jenisNamaL, 'dioxide') !== false || strpos($jenisNamaL, 'co2') !== false) {
                        $kelas = 'B-C';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $apar->kode }}</td>
                    <td>{{ $apar->lokasi->nama ?? '-' }}</td>
                    <td>{{ $apar->lokasi->gedung->nama ?? '-' }}</td>
                    <td>{{ $apar->jenis->nama ?? '-' }}</td>
                    <td>{{ $apar->kapasitas->ukuran ?? '-' }}</td>
                    <td class="text-center">{{ $kelas }}</td>
                    <td>
                        @if($apar->tgl_kedaluwarsa && $apar->tgl_kedaluwarsa->isPast())
                            <span class="text-red">Expired ({{ $apar->tgl_kedaluwarsa->format('d/m/Y') }})</span>
                        @else
                            {{ $apar->tgl_kedaluwarsa ? $apar->tgl_kedaluwarsa->format('d/m/Y') : '-' }}
                        @endif
                    </td>
                    <td class="text-center font-bold">
                        {{ $apar->qty }}
                    </td>
                    <td>
                        @php
                            $lastInspeksi = $apar->latestInspeksi;
                        @endphp
                        @if($lastInspeksi && $lastInspeksi->user)
                            {{ $lastInspeksi->user->name }}
                        @elseif($apar->pic)
                            {{ $apar->pic->name }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
            
            @if($apars->isEmpty())
                <tr>
                    <td colspan="10" class="text-center" style="padding: 20px;">Belum ada data APAR.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div style="font-size: 10px; color: #888; text-align: right;">
        Dicetak pada: {{ date('d M Y H:i:s') }}
    </div>

</body>
</html>
