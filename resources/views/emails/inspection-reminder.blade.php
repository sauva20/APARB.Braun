<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pengingat Inspeksi APAR</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-w: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        
        <div style="background-color: #009B77; padding: 20px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Pengingat Inspeksi APAR</h1>
        </div>

        <div style="padding: 30px;">
            <p style="font-size: 16px; color: #333333; margin-top: 0;">Halo, <strong>{{ $user->name }}</strong></p>
            
            <p style="font-size: 16px; color: #555555; line-height: 1.5;">
                Ini adalah pengingat otomatis dari sistem APAR Monitoring B. Braun. 
                Anda memiliki jadwal <strong>{{ $jenis }}</strong> yang akan jatuh pada tanggal:
            </p>

            <div style="background-color: #f8f9fa; border-left: 4px solid #009B77; padding: 15px; margin: 20px 0;">
                <h3 style="margin: 0; color: #009B77; font-size: 18px;">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h3>
            </div>

            @if($jenis === 'Inspeksi Rutin Bulanan')
            <p style="font-size: 16px; color: #555555; line-height: 1.5;">
                Mohon persiapkan waktu Anda untuk melakukan inspeksi rutin pada seluruh gedung yang menjadi tanggung jawab Anda:
            </p>
            <ul style="color: #555555; font-size: 16px;">
                @foreach($user->gedungs as $gedung)
                    <li>Gedung {{ $gedung->nama }}</li>
                @endforeach
            </ul>
            @endif

            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 25px 0;">
                <p style="margin: 0; color: #856404; font-size: 15px;">
                    <strong>PIN Inspeksi Anda:</strong> <span style="font-family: monospace; font-size: 18px; letter-spacing: 2px;">{{ $user->pin }}</span><br>
                    <span style="font-size: 13px;">Gunakan PIN ini untuk memverifikasi inspeksi di aplikasi.</span>
                </p>
            </div>

            <p style="font-size: 16px; color: #555555; line-height: 1.5; margin-top: 30px;">
                Anda dapat login ke sistem untuk melihat detail lebih lanjut.
            </p>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/') }}" style="display: inline-block; background-color: #009B77; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 5px; font-weight: bold; font-size: 16px;">Buka Sistem</a>
            </div>
        </div>

        <div style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #eeeeee;">
            <p style="color: #999999; font-size: 12px; margin: 0;">
                Email ini dikirim secara otomatis oleh PFE Monitoring Control System B. Braun.<br>
                Mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>
