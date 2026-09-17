<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PFE Inspection Reminder</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; color: #334155; line-height: 1.6; margin: 0; padding: 0;">
    <div style="max-width: 650px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0; border-top: 5px solid #009B77;">
        
        <div style="background-color: #009B77; color: #ffffff; padding: 24px; text-align: center;">
            <h1 style="margin: 0; font-size: 22px; font-weight: 700; letter-spacing: 0.5px;">PFE Inspection Reminder</h1>
        </div>

        <div style="padding: 32px 24px;">
            <p style="font-weight: 600; font-size: 16px; margin-top: 0;">Hello, {{ $user->name }}</p>
            
            @php
                $rawJenis = $jenis;
                if ($rawJenis == 'Inspeksi Rutin Bulanan') $rawJenis = 'Routine Monthly Inspection';
                if ($rawJenis == 'Inspeksi Khusus / Temuan') $rawJenis = 'Special Inspection / Findings';
                $scheduleType = ucfirst(str_replace('_', ' ', $rawJenis));
            @endphp
            <p>
                This is an automated reminder from the B. Braun PFE Monitoring Control System. 
                You have a <strong>{{ $scheduleType }}</strong> schedule that will fall on:
            </p>

            <div style="background-color: #f1f5f9; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #009B77;">
                <h3 style="margin: 0; color: #009B77; font-size: 18px;">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h3>
            </div>

            @if($jenis === 'Inspeksi Rutin Bulanan')
            <p>
                Please prepare your time to perform routine inspections on all buildings under your responsibility:
            </p>
            <ul style="color: #475569; font-weight: 600; margin-bottom: 24px;">
                @foreach($user->gedungs as $gedung)
                    <li>Building {{ $gedung->nama }}</li>
                @endforeach
            </ul>
            @endif

            <div style="background-color: #fffbeb; padding: 20px; border-radius: 8px; border: 1px solid #fde68a; text-align: center; margin-bottom: 24px;">
                <p style="margin-top: 0; color: #92400e; font-weight: 600; font-size: 14px; letter-spacing: 0.5px;">YOUR INSPECTION PIN</p>
                <h3 style="letter-spacing: 6px; color: #b45309; font-size: 28px; margin: 10px 0;">{{ $user->pin }}</h3>
                <p style="font-size: 13px; color: #92400e; margin-bottom: 0;">Use this PIN to verify inspections in the application.</p>
            </div>

            <p style="margin-top: 24px;">
                Please go directly to the PFE locations in the field and scan the QR code on each unit to begin the inspection process.
            </p>
        </div>

        <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 24px; text-align: center; font-size: 13px; color: #64748b;">
            <p style="margin: 0;">This email is sent automatically by the PFE Monitoring Control System (B. Braun). Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
