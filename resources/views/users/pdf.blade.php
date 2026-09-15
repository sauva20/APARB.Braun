<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data User</title>
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
    </style>
</head>
<body>

    <h2>Laporan Data User</h2>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th style="width: 12%;">User ID</th>
                <th style="width: 23%;">Nama</th>
                <th style="width: 22%;">Email</th>
                <th style="width: 15%;">Gedung</th>
                <th style="width: 8%;">Role</th>
                <th style="width: 15%;">Jadwal Inspeksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $user->employee_id ?? 'n/a' }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->gedungs->count() > 0)
                            {{ $user->gedungs->pluck('nama')->implode(', ') }}
                        @else
                            n/a
                        @endif
                    </td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->jadwal_rutin_tanggal ? 'Tanggal ' . $user->jadwal_rutin_tanggal : 'n/a' }}</td>
                </tr>
            @endforeach
            
            @if($users->isEmpty())
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Belum ada data User.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div style="font-size: 10px; color: #888; text-align: right;">
        Dicetak pada: {{ date('d M Y H:i:s') }}
    </div>

</body>
</html>
