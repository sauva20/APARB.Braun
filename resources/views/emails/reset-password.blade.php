<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Kata Sandi - PFE Monitoring Control System</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8fafc; color: #334155; line-height: 1.6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        
        <h2 style="color: #0f172a; margin-top: 0;">Halo, {{ $user->name }}</h2>
        
        <p>Anda menerima email ini karena kami menerima permintaan reset kata sandi untuk akun Anda di <strong>PFE Monitoring Control System</strong>.</p>
        
        <p>Silakan klik tombol di bawah ini untuk membuat kata sandi baru:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $resetUrl }}" style="background-color: #009B77; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Reset Kata Sandi</a>
        </div>
        
        <p style="color: #ef4444; font-size: 14px;">Tautan ini akan kedaluwarsa dalam <strong>60 menit</strong>.</p>

        <p>Jika Anda tidak pernah meminta reset kata sandi, abaikan email ini dan akun Anda akan tetap aman.</p>
        
        <p style="font-size: 12px; color: #64748b;">Jika tombol di atas tidak berfungsi, Anda juga dapat menyalin dan menempelkan URL berikut ke browser Anda:<br>
        <a href="{{ $resetUrl }}" style="color: #009B77; word-break: break-all;">{{ $resetUrl }}</a></p>
        
        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;">
        
        <p style="font-size: 12px; color: #94a3b8; margin-bottom: 0;">Terima kasih,<br>Tim Administrator PFE Monitoring Control System</p>
    </div>
</body>
</html>
