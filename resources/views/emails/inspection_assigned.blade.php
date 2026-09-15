<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Inspeksi APAR</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2>Halo, {{ $user->name }}</h2>
    
    <p>Anda telah ditugaskan untuk melakukan inspeksi APAR pada jadwal berikut:</p>
    
    @php
        $firstJadwal = $jadwals->first();
        
        $apars = collect();
        foreach($jadwals as $jadwal) {
            if ($jadwal->tipe_area == 'gedung' && $jadwal->gedung) {
                foreach($jadwal->gedung->lokasi as $l) {
                    foreach($l->apar as $a) {
                        $apars->push($a);
                    }
                }
            } elseif ($jadwal->tipe_area == 'lokasi' && $jadwal->lokasi) {
                foreach($jadwal->lokasi->apar as $a) {
                    $apars->push($a);
                }
            }
        }
        $apars = $apars->unique('id')->values();
    @endphp

    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #eee;">
        <p style="margin: 0 0 8px 0;"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($firstJadwal->tanggal_inspeksi)->translatedFormat('d F Y') }}</p>
        <p style="margin: 0;"><strong>Jenis Jadwal:</strong> {{ ucfirst(str_replace('_', ' ', $firstJadwal->jenis_jadwal)) }}</p>
    </div>

    <p>Berikut adalah daftar APAR yang harus diinspeksi:</p>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 14px;">
        <thead>
            <tr style="background-color: #009B77; color: white;">
                <th style="padding: 10px; border: 1px solid #ddd; text-align: center; width: 50px;">No</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Kode APAR</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Gedung</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($apars as $index => $apar)
            <tr style="{{ $index % 2 == 0 ? 'background-color: #fff;' : 'background-color: #fcfcfc;' }}">
                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">{{ $index + 1 }}</td>
                <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold; color: #333;">{{ $apar->kode }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $apar->lokasi->gedung->nama ?? '-' }}</td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $apar->lokasi->nama ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding: 15px; border: 1px solid #ddd; text-align: center; color: #666;">Tidak ada data APAR di area tersebut.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p>Untuk memulai inspeksi, silakan datangi lokasi APAR dan scan QR Code yang terdapat pada APAR tersebut.</p>

    <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #009B77; margin: 20px 0;">
        <p style="margin-top: 0;"><strong>PIN INSPEKSI ANDA:</strong></p>
        <h3 style="letter-spacing: 5px; color: #009B77; margin-bottom: 0;">{{ $pin ?? $user->pin }}</h3>
        <p style="font-size: 12px; color: #666;">Gunakan PIN ini untuk verifikasi saat Anda menekan tombol 'Inspeksi' setelah melakukan scan QR Code APAR.</p>
    </div>

    <p>Notes :<br>
    <em>{{ $jadwals->first()->catatan_tambahan ?: '-' }}</em></p>

    <p>Terima kasih,<br>
    <strong>APAR Monitoring System (B. Braun)</strong></p>
</body>
</html>
