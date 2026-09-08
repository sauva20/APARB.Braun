@extends('layouts.app')

@section('title', 'Dashboard Overview - APAR Monitoring System')

@section('content')
<div class="space-y-8">
    
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Left: Date Context -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-[#009B77]">
                <i class="ph-bold ph-calendar-blank text-xl"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800 leading-tight">Ringkasan Hari Ini</h2>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-3">
            <button class="btn-smooth-ring bg-[#009B77] hover:bg-[#008264] text-white font-bold py-2.5 px-5 rounded-xl shadow-[0_4px_12px_rgba(0,155,119,0.25)] transition-all flex items-center gap-2 text-sm hover:-translate-y-0.5">
                <i class="ph-bold ph-download-simple text-lg"></i>
                <span class="hidden sm:inline">Unduh Laporan</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        
        <!-- Card 1: Total APAR -->
        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-1 border border-slate-200/60 flex flex-col justify-between transition-all duration-300 group cursor-pointer report-card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total APAR</h3>
                <div class="w-10 h-10 rounded-xl bg-[#009B77]/10 group-hover:bg-[#009B77]/20 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-[#009B77]">
                    <i class="ph-fill ph-fire-extinguisher text-xl"></i>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <h2 class="text-4xl font-extrabold text-slate-800 transition-colors group-hover:text-[#009B77]">124</h2>
                <span class="inline-flex items-center gap-1 text-xs font-bold text-[#009B77] bg-[#009B77]/10 px-2 py-1 rounded-lg mb-1.5">
                    <i class="ph-bold ph-trend-up"></i> +2 bln ini
                </span>
            </div>
        </div>

        <!-- Card 2: Kondisi Baik -->
        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-1 border border-slate-200/60 flex flex-col justify-between transition-all duration-300 group cursor-pointer report-card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Kondisi Baik</h3>
                <div class="w-10 h-10 rounded-xl bg-[#009B77]/10 group-hover:bg-[#009B77]/20 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-[#009B77]">
                    <i class="ph-bold ph-check-circle text-xl"></i>
                </div>
            </div>
            <div>
                <div class="flex items-end justify-between mb-2">
                    <h2 class="text-4xl font-extrabold text-slate-800 transition-colors group-hover:text-[#009B77]">112</h2>
                    <span class="text-xs font-bold text-[#009B77] mb-1.5">90%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-[#009B77] h-1.5 rounded-full" style="width: 90%"></div>
                </div>
            </div>
        </div>

        <!-- Card 3: Rusak / Servis -->
        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-1 border border-slate-200/60 flex flex-col justify-between transition-all duration-300 group cursor-pointer report-card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Rusak / Servis</h3>
                <div class="w-10 h-10 rounded-xl bg-red-50 group-hover:bg-red-100 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-red-500">
                    <i class="ph-fill ph-wrench text-xl"></i>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <h2 class="text-4xl font-extrabold text-red-500 transition-transform group-hover:scale-105 origin-left">5</h2>
                <span class="inline-flex items-center gap-1 text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded-lg mb-1.5">
                    <i class="ph-bold ph-warning"></i> Segera
                </span>
            </div>
        </div>

        <!-- Card 4: Akan Kedaluwarsa -->
        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-1 border border-slate-200/60 flex flex-col justify-between transition-all duration-300 group cursor-pointer report-card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Akan Kedaluwarsa</h3>
                <div class="w-10 h-10 rounded-xl bg-amber-50 group-hover:bg-amber-100 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-amber-500">
                    <i class="ph-bold ph-hourglass-high text-xl"></i>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <h2 class="text-4xl font-extrabold text-amber-500 transition-transform group-hover:scale-105 origin-left">7</h2>
                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg mb-1.5">
                    <i class="ph-bold ph-calendar-blank"></i> < 30 hr
                </span>
            </div>
        </div>

    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="p-6 bg-[#009B77] border-b border-white/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="text-lg font-bold text-white">Jadwal Inspeksi Terbaru</h3>
            <div class="flex items-center gap-3">
                <!-- Filter Lokasi (Custom Dropdown) -->
                <div x-data="{ open: false, selected: 'Semua Lokasi', options: ['Semua Lokasi', 'Lobby Utama', 'Gudang Bahan Kimia', 'Ruang Server Lt 3', 'Kantin Karyawan'] }" class="relative w-40">
                    <button @click="open = !open" @click.away="open = false" class="w-full flex items-center justify-between py-2 px-3 bg-white/10 border border-white/20 text-white rounded-lg text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white/50 transition-colors cursor-pointer hover:bg-white/20 backdrop-blur-sm">
                        <span x-text="selected" class="truncate"></span>
                        <i class="ph-bold ph-caret-down text-white/70 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute left-1/2 -translate-x-1/2 z-50 w-48 mt-2 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 overflow-hidden origin-top">
                        <template x-for="option in options">
                            <button @click="selected = option; open = false" class="w-full text-left px-4 py-2.5 text-sm font-semibold transition-colors flex items-center justify-between" :class="selected === option ? 'text-[#009B77] bg-[#009B77]/5' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                <span x-text="option"></span>
                                <i class="ph-bold ph-check text-[#009B77]" x-show="selected === option"></i>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Filter Status (Custom Dropdown) -->
                <div x-data="{ open: false, selected: 'Semua Status', options: ['Semua Status', 'Bagus', 'Rusak / Servis', 'Kedaluwarsa'] }" class="relative w-40">
                    <button @click="open = !open" @click.away="open = false" class="w-full flex items-center justify-between py-2 px-3 bg-white/10 border border-white/20 text-white rounded-lg text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-white/50 transition-colors cursor-pointer hover:bg-white/20 backdrop-blur-sm">
                        <span x-text="selected" class="truncate"></span>
                        <i class="ph-bold ph-caret-down text-white/70 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute left-1/2 -translate-x-1/2 z-50 w-48 mt-2 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 overflow-hidden origin-top">
                        <template x-for="option in options">
                            <button @click="selected = option; open = false" class="w-full text-left px-4 py-2.5 text-sm font-semibold transition-colors flex items-center justify-between" :class="selected === option ? 'text-[#009B77] bg-[#009B77]/5' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                <span x-text="option"></span>
                                <i class="ph-bold ph-check text-[#009B77]" x-show="selected === option"></i>
                            </button>
                        </template>
                    </div>
                </div>
                <div class="w-px h-6 bg-white/20 mx-1"></div>
                <a href="#" class="text-sm font-bold text-white hover:text-white/80 transition-colors flex items-center gap-1 group">
                    Semua <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#009B77] text-white divide-x divide-white/20 text-center border-b border-[#009B77]">
                        <th class="py-3 px-4 text-xs font-bold uppercase tracking-wider">APAR ID</th>
                        <th class="py-3 px-4 text-xs font-bold uppercase tracking-wider">Lokasi</th>
                        <th class="py-3 px-4 text-xs font-bold uppercase tracking-wider">Inspektor</th>
                        <th class="py-3 px-4 text-xs font-bold uppercase tracking-wider">Inspeksi Terakhir</th>
                        <th class="py-3 px-4 text-xs font-bold uppercase tracking-wider">Inspeksi Berikutnya</th>
                        <th class="py-3 px-4 text-xs font-bold uppercase tracking-wider">Status</th>
                        <th class="py-3 px-4 text-xs font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    <tr class="hover:bg-slate-50 transition-colors divide-x divide-slate-100">
                        <td class="py-4 px-6 font-bold text-slate-800">#AP-001</td>
                        <td class="py-4 px-6 font-semibold text-slate-600">Lobby Utama</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-[#009B77]/10 flex items-center justify-center text-[#009B77] text-xs font-bold">AR</div>
                                <span class="font-semibold text-slate-600">Andi R.</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 font-medium text-slate-500">10 Okt 2023</td>
                        <td class="py-4 px-6 font-medium text-slate-500">10 Nov 2023</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-[#009B77]/10 text-[#009B77]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#009B77]"></span> Bagus
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-1">
                                <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-[#009B77] hover:bg-[#009B77]/10 transition-colors" title="Lihat Detail">
                                    <i class="ph-bold ph-eye text-base"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-colors" title="Edit">
                                    <i class="ph-bold ph-pencil-simple text-base"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Hapus">
                                    <i class="ph-bold ph-trash text-base"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors divide-x divide-slate-100">
                        <td class="py-4 px-6 font-bold text-slate-800">#AP-042</td>
                        <td class="py-4 px-6 font-semibold text-slate-600">Gudang Bahan Kimia</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 text-xs font-bold">BS</div>
                                <span class="font-semibold text-slate-600">Budi S.</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 font-medium text-slate-500">15 Okt 2023</td>
                        <td class="py-4 px-6 font-medium text-slate-500">15 Nov 2023</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-red-50 text-red-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Rusak
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-1">
                                <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-[#009B77] hover:bg-[#009B77]/10 transition-colors" title="Lihat Detail">
                                    <i class="ph-bold ph-eye text-base"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-colors" title="Edit">
                                    <i class="ph-bold ph-pencil-simple text-base"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Hapus">
                                    <i class="ph-bold ph-trash text-base"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors divide-x divide-slate-100">
                        <td class="py-4 px-6 font-bold text-slate-800">#AP-115</td>
                        <td class="py-4 px-6 font-semibold text-slate-600">Ruang Server Lt 3</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-purple-50 flex items-center justify-center text-purple-500 text-xs font-bold">DW</div>
                                <span class="font-semibold text-slate-600">Dewi W.</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 font-medium text-slate-500">02 Okt 2023</td>
                        <td class="py-4 px-6 font-medium text-slate-500">02 Nov 2023</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-[#009B77]/10 text-[#009B77]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#009B77]"></span> Bagus
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-1">
                                <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-[#009B77] hover:bg-[#009B77]/10 transition-colors" title="Lihat Detail">
                                    <i class="ph-bold ph-eye text-base"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-colors" title="Edit">
                                    <i class="ph-bold ph-pencil-simple text-base"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Hapus">
                                    <i class="ph-bold ph-trash text-base"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors divide-x divide-slate-100">
                        <td class="py-4 px-6 font-bold text-slate-800">#AP-088</td>
                        <td class="py-4 px-6 font-semibold text-slate-600">Kantin Karyawan</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-[#009B77]/10 flex items-center justify-center text-[#009B77] text-xs font-bold">AR</div>
                                <span class="font-semibold text-slate-600">Andi R.</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 font-medium text-slate-500">28 Sep 2023</td>
                        <td class="py-4 px-6 font-medium text-slate-500">28 Okt 2023</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Expiring
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-1">
                                <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-[#009B77] hover:bg-[#009B77]/10 transition-colors" title="Lihat Detail">
                                    <i class="ph-bold ph-eye text-base"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-50 transition-colors" title="Edit">
                                    <i class="ph-bold ph-pencil-simple text-base"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Hapus">
                                    <i class="ph-bold ph-trash text-base"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
