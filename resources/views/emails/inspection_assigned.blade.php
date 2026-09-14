<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Inspeksi APAR</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2>Halo, {{ $user->name }}</h2>
    
    <p>Anda telah ditugaskan untuk melakukan inspeksi APAR pada jadwal berikut:</p>
    
    <ul>
        @foreach($jadwals as $jadwal)
            <li style="margin-bottom: 10px;">
                <strong>Area:</strong> {{ $jadwal->tipe_area == 'gedung' ? 'Gedung ' . ($jadwal->gedung->nama ?? '-') : 'Lokasi ' . ($jadwal->lokasi->nama ?? '-') }}<br>
                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($jadwal->tanggal_inspeksi)->translatedFormat('d F Y') }}<br>
                <strong>Jenis Jadwal:</strong> {{ ucfirst(str_replace('_', ' ', $jadwal->jenis_jadwal)) }}
            </li>
        @endforeach
    </ul>

    <p>Untuk memulai inspeksi, silakan datangi lokasi APAR dan scan QR Code yang terdapat pada APAR tersebut.</p>

    <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #009B77; margin: 20px 0;">
        <p style="margin-top: 0;"><strong>PIN INSPEKSI ANDA:</strong></p>
        <h3 style="letter-spacing: 5px; color: #009B77; margin-bottom: 0;">{{ $pin ?? $user->pin }}</h3>
        <p style="font-size: 12px; color: #666;">Gunakan PIN ini untuk verifikasi saat Anda menekan tombol 'Inspeksi' setelah melakukan scan QR Code APAR.</p>
    </div>

    <p>Jika ada catatan tambahan dari sistem:<br>
    <em>{{ $jadwals->first()->catatan_tambahan ?: '-' }}</em></p>

    <p>Terima kasih,<br>
    <strong>APAR Monitoring System (B. Braun)</strong></p>
</body>
</html>
