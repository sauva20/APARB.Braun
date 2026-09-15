@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/60">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Laporan Inspeksi APAR</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Rekapitulasi data inspeksi bulanan</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.export-pdf', request()->all()) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 font-bold rounded-xl transition-colors">
                <i class="ph-bold ph-file-pdf text-lg"></i>
                Export PDF
            </a>
            <a href="{{ route('reports.export-excel', request()->all()) }}" class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 font-bold rounded-xl transition-colors">
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
                <select name="month" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#009B77]/20 focus:border-[#009B77] transition-all">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Tahun -->
            <div class="flex-1 min-w-[120px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tahun</label>
                <select name="year" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#009B77]/20 focus:border-[#009B77] transition-all">
                    @foreach(range(now()->year - 2, now()->year + 1) as $y)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Gedung -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Gedung</label>
                <select name="gedung_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#009B77]/20 focus:border-[#009B77] transition-all">
                    <option value="">Semua Gedung</option>
                    @foreach($gedungs as $gedung)
                        <option value="{{ $gedung->id }}" {{ $selectedGedung == $gedung->id ? 'selected' : '' }}>
                            {{ $gedung->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Status -->
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#009B77]/20 focus:border-[#009B77] transition-all">
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="sudah" {{ $status == 'sudah' ? 'selected' : '' }}>Sudah Diinspeksi</option>
                    <option value="belum" {{ $status == 'belum' ? 'selected' : '' }}>Belum Diinspeksi</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-6 py-2.5 bg-[#009B77] text-white font-bold rounded-xl shadow-md hover:bg-[#008264] hover:shadow-lg transition-all flex items-center gap-2">
                    <i class="ph-bold ph-funnel"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500">
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider w-16 text-center">No</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">APAR</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">Gedung</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">Lokasi</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">Status & Tgl Inspeksi</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">Inspektor</th>
                        <th class="py-4 px-6 text-xs font-bold uppercase tracking-wider">Kondisi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($paginatedData as $index => $row)
                        @php 
                            $apar = $row['apar'];
                            $inspeksi = $row['inspeksi'];
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-center font-semibold text-slate-400">
                                {{ $paginatedData->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800">{{ $apar->kode }}</div>
                                <div class="text-xs font-semibold text-slate-500">{{ $apar->jenis->nama ?? '-' }} - {{ $apar->kapasitas_id ?? '-' }} Kg</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-700">{{ $apar->lokasi->gedung->nama ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-600">{{ $apar->lokasi->nama ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @if($row['status'] == 'Sudah Diinspeksi')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-[#009B77]/10 text-[#009B77]">
                                        <i class="ph-bold ph-check-circle"></i> Sudah
                                    </span>
                                    <div class="text-xs font-medium text-slate-500 mt-1">
                                        {{ \Carbon\Carbon::parse($inspeksi->created_at)->translatedFormat('d M Y') }}
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 text-amber-600">
                                        <i class="ph-bold ph-clock"></i> Belum
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-700">
                                {{ $inspeksi->user->name ?? '-' }}
                            </td>
                            <td class="py-4 px-6">
                                @if($inspeksi)
                                    @if($inspeksi->status == 'layak')
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600">
                                            <i class="ph-fill ph-check-circle text-sm"></i> Layak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-red-500">
                                            <i class="ph-fill ph-warning-circle text-sm"></i> {{ ucfirst($inspeksi->status) }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-slate-400 font-medium">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500 font-semibold">
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
