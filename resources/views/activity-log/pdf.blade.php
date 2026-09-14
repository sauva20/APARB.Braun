<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Audit Trail</title>
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

    <h2>Audit Trail</h2>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Waktu</th>
                <th style="width: 20%;">Pengguna</th>
                <th class="text-center" style="width: 15%;">Aksi</th>
                <th style="width: 15%;">Modul</th>
                <th style="width: 30%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
                <tr>
                    <td class="font-bold">{{ $activity->created_at->format('d M Y H:i:s') }}</td>
                    <td>{{ $activity->causer->name ?? 'Sistem / Guest' }}</td>
                    <td class="text-center font-bold">{{ strtoupper($activity->event) }}</td>
                    <td>{{ class_basename($activity->subject_type) }}</td>
                    <td>{{ $activity->description }}</td>
                </tr>
            @endforeach
            
            @if($activities->isEmpty())
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px;">Belum ada log aktivitas.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div style="font-size: 10px; color: #888; text-align: right;">
        Dicetak pada: {{ date('d M Y H:i:s') }}
    </div>

</body>
</html>
