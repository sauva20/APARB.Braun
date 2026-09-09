<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi APAR - {{ $apar->kode }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen pb-20">

    <div class="max-w-md mx-auto bg-white min-h-screen shadow-xl border-x border-slate-100 relative">
        <!-- Header -->
        <div class="bg-gradient-to-br from-[#009B77] to-[#007b5e] pt-12 pb-6 px-6 text-center relative overflow-hidden rounded-b-3xl">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            
            <div class="w-16 h-16 mx-auto bg-white rounded-2xl shadow-lg flex items-center justify-center text-[#009B77] mb-4 relative z-10">
                <i class="ph-fill ph-fire-extinguisher text-3xl"></i>
            </div>
            <h1 class="text-2xl font-black text-white tracking-wide relative z-10">{{ $apar->kode }}</h1>
            <p class="text-white/80 font-medium mt-1 text-sm relative z-10">Informasi & Status APAR</p>
        </div>

        <!-- Content -->
        <div class="p-6 -mt-4 relative z-20 space-y-4">
            
            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl mb-4">
                <div class="flex items-start">
                    <i class="ph-fill ph-warning-circle text-red-500 text-xl mr-3"></i>
                    <p class="text-sm font-bold text-red-700">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <!-- Info Cards -->
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-map-pin text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Lokasi</p>
                    <p class="text-sm font-bold text-slate-800">{{ $apar->lokasi->nama ?? '-' }} ({{ $apar->lokasi->gedung->nama ?? '-' }})</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-drop text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Jenis</p>
                    <p class="text-sm font-bold text-slate-800">{{ $apar->jenis->nama ?? '-' }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-scales text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Kapasitas</p>
                    <p class="text-sm font-bold text-slate-800">{{ $apar->kapasitas->ukuran ?? '-' }}</p>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-calendar-blank text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Kedaluwarsa</p>
                    <p class="text-sm font-bold text-slate-800">{{ $apar->tgl_kedaluwarsa ? \Carbon\Carbon::parse($apar->tgl_kedaluwarsa)->format('d F Y') : '-' }}</p>
                </div>
            </div>

            @php
                $terakhirInspeksi = $apar->inspeksis()->latest()->first();
            @endphp
            
            @if($terakhirInspeksi)
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Terakhir</p>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md uppercase {{ $terakhirInspeksi->status == 'layak' ? 'bg-[#009B77]/10 text-[#009B77]' : 'bg-red-100 text-red-600' }}">
                        {{ $terakhirInspeksi->status }}
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                        <i class="ph-fill ph-user text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $terakhirInspeksi->user->name ?? 'User' }}</p>
                        <p class="text-xs font-semibold text-slate-500">{{ $terakhirInspeksi->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 border-dashed text-center">
                <p class="text-xs font-semibold text-slate-500">Belum ada riwayat inspeksi untuk APAR ini.</p>
            </div>
            @endif

        </div>

        <!-- Fixed Action Button -->
        <div x-data="{ showPinModal: false }" class="fixed bottom-0 left-0 right-0 z-50 pointer-events-none">
            <div class="max-w-md mx-auto p-6 pointer-events-auto bg-gradient-to-t from-white via-white to-transparent">
                <button @click="showPinModal = true" class="w-full bg-slate-800 text-white font-bold py-4 rounded-2xl shadow-lg shadow-slate-800/20 flex items-center justify-center gap-2 transition-transform active:scale-[0.98]">
                    <i class="ph-bold ph-scan text-xl"></i>
                    Lakukan Inspeksi
                </button>
            </div>

            <!-- PIN Modal -->
            <div x-show="showPinModal" style="display: none;" class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center sm:p-4 pointer-events-auto" x-cloak>
                <div x-show="showPinModal" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showPinModal = false"></div>
                
                <div x-show="showPinModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="translate-y-full sm:translate-y-8 sm:scale-95 opacity-0" x-transition:enter-end="translate-y-0 sm:scale-100 opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="translate-y-0 sm:scale-100 opacity-100" x-transition:leave-end="translate-y-full sm:translate-y-8 sm:scale-95 opacity-0"
                     class="relative w-full max-w-md bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden">
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-slate-800">Verifikasi Petugas</h3>
                            <button @click="showPinModal = false" class="text-slate-400 hover:text-slate-600 bg-slate-100 p-2 rounded-full"><i class="ph-bold ph-x text-lg"></i></button>
                        </div>
                        
                        <p class="text-sm font-medium text-slate-500 mb-6">Masukkan 6 digit PIN Anda untuk melakukan inspeksi pada APAR ini.</p>
                        
                        <form action="{{ route('scan.verify', $apar->kode) }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">PIN Petugas</label>
                                <input type="password" name="pin" maxlength="6" inputmode="numeric" required class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-3 px-4 text-center tracking-[0.5em] text-xl font-black text-slate-800 focus:bg-white focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none" placeholder="••••••">
                            </div>
                            
                            <button type="submit" class="w-full bg-[#009B77] hover:bg-[#008264] text-white font-bold py-3.5 rounded-xl shadow-[0_4px_12px_rgba(0,155,119,0.25)] transition-colors text-sm flex items-center justify-center gap-2">
                                Lanjut Inspeksi <i class="ph-bold ph-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
