<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pendaftaran Akun APAR Monitoring System</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; color: #334155; line-height: 1.6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        
        <h2 style="color: #0f172a; margin-top: 0;">Halo, {{ $user->name }}</h2>
        
        <p>Anda telah didaftarkan ke dalam <strong>APAR Monitoring System</strong> sebagai <strong>{{ $user->role }}</strong>.</p>
        
        <p>Berikut adalah <strong>PIN Inspeksi (6 digit)</strong> Anda. PIN ini akan digunakan sebagai verifikasi saat mengatur kata sandi dan juga saat melakukan inspeksi APAR.</p>
        
        <div style="background-color: #f1f5f9; padding: 15px; border-radius: 8px; text-align: center; margin: 20px 0;">
            <span style="font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #009B77;">{{ $user->pin }}</span>
        </div>
        
        <p style="color: #ef4444; font-size: 14px;"><strong>Penting:</strong> PIN ini hanya dapat digunakan satu kali untuk keperluan *setup* password (kata sandi) Anda.</p>

        <p>Silakan klik tombol di bawah ini untuk memverifikasi PIN Anda dan membuat kata sandi baru untuk akun Anda:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $setupUrl }}" style="background-color: #009B77; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Buat Kata Sandi</a>
        </div>
        
        <p style="font-size: 12px; color: #64748b;">Jika tombol di atas tidak berfungsi, Anda juga dapat menyalin dan menempelkan URL berikut ke browser Anda:<br>
        <a href="{{ $setupUrl }}" style="color: #009B77; word-break: break-all;">{{ $setupUrl }}</a></p>
        
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #94a3b8; margin-bottom: 0;">Terima kasih,<br>Tim Administrator APAR Monitoring System</p>
    </div>
</body>
</html>
