<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code {{ $apar->kode }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #1e293b;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .header-bar {
            width: 440px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 14px 20px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .btn-print {
            background-color: #009B77;
            color: white;
            border: none;
            padding: 9px 18px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(0, 155, 119, 0.25);
            transition: all 0.2s;
        }
        .btn-print:hover {
            background-color: #008264;
        }
        .btn-back {
            background: white;
            color: #64748b;
            border: 1px solid #cbd5e1;
            padding: 9px 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-back:hover {
            background: #f1f5f9;
            color: #334155;
        }
        .label-card {
            width: 440px;
            background: white;
            border: 2.5px dashed #009B77;
            border-radius: 20px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            page-break-inside: avoid;
        }
        .system-badge {
            background: #e6f7f2;
            color: #009B77;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
        }
        .kode-apar {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 16px;
        }
        .qr-box {
            background: white;
            padding: 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            margin-bottom: 16px;
            display: inline-flex;
        }
        .qr-box svg {
            width: 180px;
            height: 180px;
        }
        .info-section {
            width: 100%;
            background: #f8fafc;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
        }
        .info-row {
            font-size: 13px;
            color: #475569;
            margin-bottom: 4px;
            display: flex;
            justify-content: space-between;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }
        .info-row strong {
            color: #0f172a;
        }
        .scan-hint {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
                display: block;
            }
            .header-bar {
                display: none !important;
            }
            .label-card {
                box-shadow: none;
                border: 2px dashed #009B77;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>

    <div class="header-bar">
        <a href="/master-data" class="btn-back">
            <i class="ph-bold ph-arrow-left"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn-print">
            <i class="ph-bold ph-printer"></i> Cetak Label
        </button>
    </div>

    <div class="label-card">
        <div class="system-badge">{{ __('PFE MONITORING CONTROL SYSTEM') }}</div>
        <div class="kode-apar">{{ $apar->kode }}</div>

        <div class="qr-box">
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->generate(route('scan.apar', $apar->kode)) !!}
        </div>

        <div class="info-section">
            <div class="info-row">
                <span>Gedung:</span>
                <strong>{{ $apar->lokasi->gedung->nama ?? 'n/a' }}</strong>
            </div>
            <div class="info-row">
                <span>Lokasi:</span>
                <strong>{{ $apar->lokasi->nama ?? 'n/a' }}</strong>
            </div>
            <div class="info-row">
                <span>Jenis:</span>
                <strong>{{ $apar->jenis->nama ?? 'n/a' }} ({{ $apar->kapasitas->ukuran ?? 'n/a' }})</strong>
            </div>
        </div>

        <div class="scan-hint">
            <i class="ph-bold ph-scan"></i> Scan dengan kamera HP untuk inspeksi
        </div>
    </div>

</body>
</html>
