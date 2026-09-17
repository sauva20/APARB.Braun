<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ __('Inspection Guidelines') }} - PFE Monitoring Control System</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @font-face {
            font-family: 'Rotis Sans Serif';
            src: url('/fonts/RotisSansSerif.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Rotis Sans Serif';
            src: url('/fonts/RotisSansSerif-Bold.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
            font-display: swap;
        }

        body {
            font-family: 'Rotis Sans Serif', sans-serif;
            background-color: #F0F0F0;
            color: #1A1A1A;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-[#F0F0F0] text-[#1A1A1A] relative min-h-screen w-screen overflow-x-hidden flex flex-col pb-24">

    <!-- Decorative Background Elements -->
    <div class="fixed top-[-10%] left-[-10%] w-[60%] h-[40%] bg-[#009B77] rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none"></div>
    <div class="fixed bottom-10 right-[-10%] w-[50%] h-[40%] bg-[#8A4B9F] rounded-full mix-blend-multiply filter blur-[100px] opacity-15 pointer-events-none"></div>

    <!-- Language Switcher -->
    <div class="fixed top-4 right-4 z-[100] flex items-center gap-1.5">
        <a href="{{ route('set-locale', 'id') }}" class="text-[10px] font-bold px-2 py-1.5 rounded-lg {{ app()->getLocale() == 'id' ? 'bg-[#009B77] text-white border-transparent' : 'bg-white/80 text-slate-500 hover:bg-white border-slate-200/50' }} shadow-sm backdrop-blur-sm border transition-colors">ID</a>
        <a href="{{ route('set-locale', 'en') }}" class="text-[10px] font-bold px-2 py-1.5 rounded-lg {{ app()->getLocale() == 'en' ? 'bg-[#009B77] text-white border-transparent' : 'bg-white/80 text-slate-500 hover:bg-white border-slate-200/50' }} shadow-sm backdrop-blur-sm border transition-colors">EN</a>
    </div>

    <div class="w-full max-w-xl mx-auto relative z-10 flex flex-col p-4 sm:p-6 space-y-6">
        
        <!-- Header Area -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <a href="{{ route('scan.apar', $apar->kode) }}" class="w-8 h-8 rounded-lg bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-slate-500 hover:text-[#009B77] hover:border-[#009B77] transition-all" title="{{ __('Back') }}">
                    <i class="ph-bold ph-arrow-left text-lg"></i>
                </a>
                <div>
                    <h2 class="text-base font-extrabold text-slate-800 leading-tight">{{ __('Inspection Guidelines') }}</h2>
                    <p class="text-[10px] font-semibold text-slate-500 mt-0.5">{{ __('Points to check during PFE inspection') }}</p>
                </div>
            </div>
        </div>

        <!-- Guidelines List -->
        <div class="bg-white rounded-[24px] shadow-[0_10px_30px_-15px_rgba(0,155,119,0.1)] border border-slate-100 p-5 sm:p-6">
            <div class="space-y-4">

                <div class="flex gap-3">
                    <div class="w-6 h-6 rounded-full bg-[#009B77]/10 text-[#009B77] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        1
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-0.5">{{ __('Ketersediaan') }}</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ __('Pastikan APAR tersedia di tempat yang mudah diakses dan terlihat dengan jelas.') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-6 h-6 rounded-full bg-[#009B77]/10 text-[#009B77] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        2
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-0.5">{{ __('Tanggal inspeksi terakhir') }}</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ __('Periksa apakah APAR telah diperiksa dan dikalibrasi sesuai dengan jadwal yang ditetapkan.') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-6 h-6 rounded-full bg-[#009B77]/10 text-[#009B77] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        3
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-0.5">{{ __('Label dan tanda peringatan') }}</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ __('Pastikan label dan tanda peringatan pada APAR masih terbaca dengan jelas dan tidak rusak.') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-6 h-6 rounded-full bg-[#009B77]/10 text-[#009B77] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        4
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-0.5">{{ __('Segel keselamatan') }}</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ __('Periksa segel keselamatan pada APAR. Pastikan segel tidak rusak atau telah terputus.') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-6 h-6 rounded-full bg-[#009B77]/10 text-[#009B77] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        5
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-0.5">{{ __('Kondisi fisik') }}</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ __('Periksa apakah APAR dalam kondisi fisik yang baik, tanpa kerusakan atau kebocoran yang signifikan.') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-6 h-6 rounded-full bg-[#009B77]/10 text-[#009B77] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        6
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-0.5">{{ __('Penunjuk tekanan') }}</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ __('Periksa penunjuk tekanan pada APAR. Pastikan tekanan berada dalam rentang yang diperbolehkan.') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-6 h-6 rounded-full bg-[#009B77]/10 text-[#009B77] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        7
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-0.5">{{ __('Instruksi penggunaan') }}</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ __('Periksa apakah instruksi penggunaan APAR masih terpasang dan mudah diakses.') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-6 h-6 rounded-full bg-[#009B77]/10 text-[#009B77] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        8
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-0.5">{{ __('Pemeriksaan visual') }}</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ __('Lakukan pemeriksaan visual terhadap selang, nozzle, dan katup pemadam. Pastikan tidak ada kerusakan, kebocoran, atau penyumbatan yang signifikan.') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-6 h-6 rounded-full bg-[#009B77]/10 text-[#009B77] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        9
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-0.5">{{ __('Pengoperasian') }}</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ __('Pastikan tuas atau pengatur aliran APAR berfungsi dengan baik dan tidak mengalami kebocoran.') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-6 h-6 rounded-full bg-[#009B77]/10 text-[#009B77] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        10
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-0.5">{{ __('Ketersediaan pelatihan') }}</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ __('Periksa apakah pengguna APAR telah menjalani pelatihan yang diperlukan.') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-6 h-6 rounded-full bg-[#009B77]/10 text-[#009B77] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        11
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-0.5">{{ __('Dokumentasi') }}</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ __('Pastikan catatan inspeksi APAR diisi dengan lengkap dan diperbarui secara teratur.') }}</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <div class="w-6 h-6 rounded-full bg-[#009B77]/10 text-[#009B77] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        12
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 mb-0.5">{{ __('Tindakan perbaikan') }}</h4>
                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ __('Jika ditemukan masalah atau kekurangan selama inspeksi, pastikan tindakan perbaikan yang tepat diambil dan dicatat.') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed bottom-0 left-0 right-0 p-4 sm:p-6 bg-gradient-to-t from-[#F0F0F0] via-[#F0F0F0]/90 to-transparent z-40 pb-6">
        <div class="w-full max-w-xl mx-auto">
            <a href="{{ route('inspeksi.mulai', $apar->id) }}" class="w-full bg-[#009B77] hover:bg-[#008264] text-white font-bold py-4 rounded-xl shadow-lg shadow-[#009B77]/30 transition-all text-sm flex items-center justify-center gap-2 tracking-wide">
                <span>{{ __('Start Inspection') }}</span>
                <i class="ph-bold ph-arrow-right"></i>
            </a>
        </div>
    </div>

</body>
</html>
