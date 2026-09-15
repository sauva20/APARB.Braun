<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Semua QR Code APAR</title>
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
            padding: 24px;
        }
        .header-bar {
            max-width: 900px;
            margin: 0 auto 24px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 16px 24px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .btn-print {
            background-color: #009B77;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
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
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-back:hover {
            background: #f1f5f9;
            color: #334155;
        }
        .grid-container {
            max-width: 900px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .label-card {
            background: white;
            border: 2px dashed #009B77;
            border-radius: 16px;
            padding: 18px;
            display: flex;
            gap: 16px;
            align-items: center;
            page-break-inside: avoid;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            position: relative;
        }
        .qr-wrapper {
            flex-shrink: 0;
            background: white;
            padding: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-wrapper svg {
            width: 120px;
            height: 120px;
        }
        .info-wrapper {
            flex-grow: 1;
        }
        .tag-system {
            font-size: 10px;
            font-weight: 800;
            color: #009B77;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 2px;
        }
        .kode-apar {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }
        .meta-row {
            font-size: 12px;
            color: #475569;
            margin-bottom: 3px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .meta-row strong {
            color: #1e293b;
        }
        .scan-hint {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #f1f5f9;
            font-size: 10px;
            font-weight: 600;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .header-bar {
                display: none !important;
            }
            .grid-container {
                max-width: 100%;
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
            .label-card {
                box-shadow: none;
                border: 1.5px dashed #009B77;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="header-bar">
        <div>
            <h2 style="font-size: 18px; font-weight: 800; color: #0f172a;">Cetak Stiker QR Code APAR</h2>
            <p style="font-size: 12px; color: #64748b; margin-top: 2px;">Total: {{ $apars->count() }} unit APAR siap dicetak ke kertas label/stiker</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="/master-data" class="btn-back">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn-print">
                <i class="ph-bold ph-printer"></i> Cetak Sekarang
            </button>
        </div>
    </div>

    <div class="grid-container">
        @forelse($apars as $apar)
        <div class="label-card">
            <div class="qr-wrapper">
                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate(route('scan.apar', $apar->kode)) !!}
            </div>
            <div class="info-wrapper">
                <div class="tag-system">PFE Monitoring System</div>
                <div class="kode-apar">{{ $apar->kode }}</div>
                
                <div class="meta-row">
                    <i class="ph-bold ph-buildings" style="color: #009B77;"></i>
                    <span><strong>{{ $apar->lokasi->gedung->nama ?? 'n/a' }}</strong></span>
                </div>
                <div class="meta-row">
                    <i class="ph-bold ph-map-pin" style="color: #009B77;"></i>
                    <span>{{ $apar->lokasi->nama ?? 'n/a' }}</span>
                </div>
                <div class="meta-row">
                    <i class="ph-bold ph-fire-extinguisher" style="color: #009B77;"></i>
                    <span>{{ $apar->jenis->nama ?? 'n/a' }} ({{ $apar->kapasitas->ukuran ?? 'n/a' }})</span>
                </div>

                <div class="scan-hint">
                    <i class="ph-bold ph-scan"></i> Scan QR untuk cek detail & inspeksi
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: span 2; text-align: center; padding: 48px; background: white; border-radius: 16px; color: #64748b;">
            Belum ada data APAR yang terdaftar.
        </div>
        @endforelse
    </div>

</body>
</html>
