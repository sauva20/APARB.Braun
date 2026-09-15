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
            background-color: #f8f9fa;
            color: #009B77;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
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
        .signature-table {
            width: 100%;
            border: none;
            margin-top: 30px;
        }
        .signature-table td {
            border: none;
            text-align: center;
            width: 50%;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #000;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Inspeksi APAR Bulanan</h1>
        <p>Bulan: {{ $monthName }} | Gedung: {{ $gedungName }} | Filter: {{ ucfirst($status) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="8%">ID APAR</th>
                <th width="6%">Gedung</th>
                <th width="8%">Lokasi</th>
                <th width="10%">Jenis / Kapasitas</th>
                <th width="8%">Status</th>
                <th width="12%">Tgl Inspeksi</th>
                <th width="10%">Inspektor</th>
                <th width="7%">Kondisi</th>
                <th width="30%">Cek Fisik (Tekanan/Pin/Tuas/Selang/Bersih) & Ket</th>
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
                    <td><b>{{ $apar->lokasi->gedung->nama ?? '-' }}</b></td>
                    <td>{{ $apar->lokasi->nama ?? '-' }}</td>
                    <td>
                        {{ $apar->jenis->nama ?? '-' }}<br>
                        {{ $apar->kapasitas_id ?? '-' }} Kg
                    </td>
                    <td class="text-center">
                        @if($row['status'] == 'Sudah Diinspeksi')
                            <span class="badge bg-green">Sudah</span>
                        @else
                            <span class="badge bg-orange">Belum</span>
                        @endif
                    </td>
                    <td>
                        {{ $inspeksi ? \Carbon\Carbon::parse($inspeksi->created_at)->format('d-m-Y H:i') : '-' }}
                    </td>
                    <td>{{ $inspeksi->user->name ?? '-' }}</td>
                    <td>
                        @if($inspeksi)
                            @if($inspeksi->status == 'layak')
                                <span class="badge bg-green">Layak</span>
                            @else
                                <span class="badge bg-red">{{ $inspeksi->status }}</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td style="font-size: 8px;">
                        @if($inspeksi)
                            T: {{ $inspeksi->tekanan ? 'Ok' : 'X' }} | 
                            P: {{ $inspeksi->pin ? 'Ok' : 'X' }} | 
                            Ts: {{ $inspeksi->tuas ? 'Ok' : 'X' }} | 
                            S: {{ $inspeksi->selang ? 'Ok' : 'X' }} | 
                            B: {{ $inspeksi->kebersihan ? 'Ok' : 'X' }}
                            <br>
                            <i>Ket: {{ $inspeksi->keterangan ?? '-' }}</i>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data untuk periode dan filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                Dibuat Oleh,
                <br><br><br><br>
                <span class="signature-line"></span><br>
                <b>{{ auth()->user()->name }}</b><br>
                {{ auth()->user()->role }}
            </td>
            <td>
                Mengetahui,
                <br><br><br><br>
                <span class="signature-line"></span><br>
                <b>EHSS Manager</b>
            </td>
        </tr>
    </table>

</body>
</html>
