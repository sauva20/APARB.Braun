@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/60">
        <!-- Left: Page Context -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-[#009B77]">
                <i class="ph-bold ph-file-text text-xl"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-800 leading-tight">Laporan Inspeksi APAR</h2>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">Rekapitulasi data inspeksi bulanan</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.export-pdf', request()->all()) }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 text-sm bg-red-50 text-red-600 hover:bg-red-100 font-bold rounded-xl transition-colors">
                <i class="ph-bold ph-file-pdf text-lg"></i>
                Export PDF
            </a>
            <a href="{{ route('reports.export-excel', request()->all()) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm bg-emerald-50 text-emerald-600 hover:bg-emerald-100 font-bold rounded-xl transition-colors">
                <i class="ph-bold ph-file-xls text-lg"></i>
                Export Excel
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/60">
        <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <!-- Bulan -->
            <div class="flex-1 min-w-[150px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bulan</label>
                <div x-data="{ open: false, selected: '{{ \Carbon\Carbon::create()->month((int)$selectedMonth)->locale('id')->translatedFormat('F') }}' }" class="relative">
                    <input type="hidden" name="month" value="{{ $selectedMonth }}" x-ref="month_input">
                    <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex items-center justify-between py-2.5 px-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all cursor-pointer hover:border-slate-300">
                        <span x-text="selected" class="truncate"></span>
                        <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute left-0 z-50 w-full mt-2 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 max-h-60 overflow-y-auto" x-cloak>
                        @foreach(range(1, 12) as $m)
                            @php $mName = \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F'); @endphp
                            <button type="button" @click="selected = '{{ $mName }}'; open = false; $refs.month_input.value = '{{ $m }}'; $refs.month_input.form.submit();" class="w-full text-left px-4 py-2.5 text-sm font-medium transition-colors flex items-center justify-between" :class="selected === '{{ $mName }}' ? 'text-[#009B77] bg-[#009B77]/5' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                <span>{{ $mName }}</span>
                                <i class="ph-bold ph-check text-[#009B77]" x-show="selected === '{{ $mName }}'" x-cloak></i>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Tahun -->
            <div class="flex-1 min-w-[120px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tahun</label>
                <div x-data="{ open: false, selected: '{{ $selectedYear }}' }" class="relative">
                    <input type="hidden" name="year" value="{{ $selectedYear }}" x-ref="year_input">
                    <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex items-center justify-between py-2.5 px-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all cursor-pointer hover:border-slate-300">
                        <span x-text="selected" class="truncate"></span>
                        <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute left-0 z-50 w-full mt-2 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 max-h-60 overflow-y-auto" x-cloak>
                        @foreach(range(now()->year - 2, now()->year) as $y)
                            <button type="button" @click="selected = '{{ $y }}'; open = false; $refs.year_input.value = '{{ $y }}'; $refs.year_input.form.submit();" class="w-full text-left px-4 py-2.5 text-sm font-medium transition-colors flex items-center justify-between" :class="selected === '{{ $y }}' ? 'text-[#009B77] bg-[#009B77]/5' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                <span>{{ $y }}</span>
                                <i class="ph-bold ph-check text-[#009B77]" x-show="selected === '{{ $y }}'" x-cloak></i>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Gedung -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Gedung</label>
                <div x-data="{ open: false, selected: '{{ request('gedung_id') ? addslashes($gedungs->firstWhere('id', request('gedung_id'))->nama ?? 'Semua Gedung') : 'Semua Gedung' }}' }" class="relative">
                    <input type="hidden" name="gedung_id" value="{{ request('gedung_id') }}" x-ref="gedung_input">
                    <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex items-center justify-between py-2.5 px-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all cursor-pointer hover:border-slate-300">
                        <span x-text="selected" class="truncate"></span>
                        <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute left-0 z-50 w-full mt-2 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 max-h-60 overflow-y-auto" x-cloak>
                        <button type="button" @click="selected = 'Semua Gedung'; open = false; $refs.gedung_input.value = ''; $refs.gedung_input.form.submit();" class="w-full text-left px-4 py-2.5 text-sm font-medium transition-colors flex items-center justify-between" :class="selected === 'Semua Gedung' ? 'text-[#009B77] bg-[#009B77]/5' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                            <span>Semua Gedung</span>
                            <i class="ph-bold ph-check text-[#009B77]" x-show="selected === 'Semua Gedung'" x-cloak></i>
                        </button>
                        @foreach($gedungs as $gedung)
                            <button type="button" @click="selected = '{{ addslashes($gedung->nama) }}'; open = false; $refs.gedung_input.value = '{{ $gedung->id }}'; $refs.gedung_input.form.submit();" class="w-full text-left px-4 py-2.5 text-sm font-medium transition-colors flex items-center justify-between" :class="selected === '{{ addslashes($gedung->nama) }}' ? 'text-[#009B77] bg-[#009B77]/5' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                <span>{{ $gedung->nama }}</span>
                                <i class="ph-bold ph-check text-[#009B77]" x-show="selected === '{{ addslashes($gedung->nama) }}'" x-cloak></i>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Status -->
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status</label>
                <div x-data="{ 
                    open: false, 
                    selected: '{{ $status == 'sudah' ? 'Sudah Diinspeksi' : ($status == 'belum' ? 'Belum Diinspeksi' : 'Semua Status') }}' 
                }" class="relative">
                    <input type="hidden" name="status" value="{{ $status }}" x-ref="status_input">
                    <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex items-center justify-between py-2.5 px-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all cursor-pointer hover:border-slate-300">
                        <span x-text="selected" class="truncate"></span>
                        <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute left-0 z-50 w-full mt-2 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 max-h-60 overflow-y-auto" x-cloak>
                        <button type="button" @click="selected = 'Semua Status'; open = false; $refs.status_input.value = 'all'; $refs.status_input.form.submit();" class="w-full text-left px-4 py-2.5 text-sm font-medium transition-colors flex items-center justify-between" :class="selected === 'Semua Status' ? 'text-[#009B77] bg-[#009B77]/5' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                            <span>Semua Status</span>
                            <i class="ph-bold ph-check text-[#009B77]" x-show="selected === 'Semua Status'" x-cloak></i>
                        </button>
                        <button type="button" @click="selected = 'Sudah Diinspeksi'; open = false; $refs.status_input.value = 'sudah'; $refs.status_input.form.submit();" class="w-full text-left px-4 py-2.5 text-sm font-medium transition-colors flex items-center justify-between" :class="selected === 'Sudah Diinspeksi' ? 'text-[#009B77] bg-[#009B77]/5' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                            <span>Sudah Diinspeksi</span>
                            <i class="ph-bold ph-check text-[#009B77]" x-show="selected === 'Sudah Diinspeksi'" x-cloak></i>
                        </button>
                        <button type="button" @click="selected = 'Belum Diinspeksi'; open = false; $refs.status_input.value = 'belum'; $refs.status_input.form.submit();" class="w-full text-left px-4 py-2.5 text-sm font-medium transition-colors flex items-center justify-between" :class="selected === 'Belum Diinspeksi' ? 'text-[#009B77] bg-[#009B77]/5' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                            <span>Belum Diinspeksi</span>
                            <i class="ph-bold ph-check text-[#009B77]" x-show="selected === 'Belum Diinspeksi'" x-cloak></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#009B77] text-white divide-x divide-white/20 text-center">
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider w-16 text-center">No</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">APAR</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">Jenis</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">Kapasitas</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">Gedung</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">Lokasi</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">Status & Tgl Inspeksi</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">PIC</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">Kondisi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($paginatedData as $index => $row)
                        @php 
                            $apar = $row['apar'];
                            $inspeksi = $row['inspeksi'];
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors divide-x divide-slate-100">
                            <td class="py-4 px-6 text-center font-semibold text-slate-400">
                                {{ $paginatedData->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800">{{ $apar->kode }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-600">{{ $apar->jenis->nama ?? 'n/a' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-600">{{ $apar->kapasitas->ukuran ?? 'n/a' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-700">{{ $apar->lokasi->gedung->nama ?? 'n/a' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-600">{{ $apar->lokasi->nama ?? 'n/a' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @if($row['status'] == 'Sudah Diinspeksi')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-teal-50 text-teal-600 border border-teal-100 uppercase tracking-wider">
                                        <i class="ph-fill ph-check-circle"></i> Sudah
                                    </span>
                                    <div class="text-[11px] font-medium text-slate-500 mt-1.5">
                                        {{ \Carbon\Carbon::parse($inspeksi->created_at)->translatedFormat('d M Y') }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-wider">
                                        <i class="ph-fill ph-clock"></i> Belum
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($inspeksi && $inspeksi->user)
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-[#009B77]/10 flex items-center justify-center text-[#009B77] text-xs font-bold border border-[#009B77]/20">
                                            {{ strtoupper(substr($inspeksi->user->name, 0, 2)) }}
                                        </div>
                                        <span class="font-semibold text-slate-700">{{ $inspeksi->user->name }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 font-medium">n/a</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-left">
                                @if($inspeksi)
                                    @if($inspeksi->status === 'layak')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-teal-50 text-teal-600 border border-teal-100 uppercase tracking-wider">
                                            <i class="ph-fill ph-check-circle"></i> Layak
                                        </span>
                                    @elseif($inspeksi->status === 'perbaikan' || $inspeksi->status === 'rusak')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-red-50 text-red-600 border border-red-100 uppercase tracking-wider">
                                            <i class="ph-fill ph-warning-circle"></i> Perbaikan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-wider">
                                            <i class="ph-fill ph-arrows-clockwise"></i> {{ str_replace('_', ' ', $inspeksi->status) }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-slate-400 font-medium">n/a</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-500 font-semibold">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="ph-duotone ph-folder-open text-4xl text-slate-300"></i>
                                    Tidak ada data untuk periode ini.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($paginatedData->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $paginatedData->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>
@endsection
