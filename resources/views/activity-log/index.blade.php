@extends('layouts.app')

@section('title', 'Audit Trail - APAR Monitoring System')

@section('content')
<div class="space-y-6">

    <!-- Header Area -->
    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-[#009B77]">
                    <i class="ph-bold ph-clock-counter-clockwise text-xl"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-800 leading-tight">Audit Trail</h2>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Riwayat tindakan pengguna dalam sistem</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="relative z-50">
                    <a href="{{ route('activity-log.export-pdf', request()->all()) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-md hover:shadow-lg shadow-red-600/20 transition-all flex items-center gap-2 text-sm hover:-translate-y-0.5">
                        <i class="ph-bold ph-file-pdf text-lg"></i>
                        <span class="hidden sm:inline">Export PDF</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#009B77] text-white divide-x divide-white/20">
                        <th class="py-4 px-5 text-xs font-bold uppercase tracking-wider">Waktu</th>
                        <th class="py-4 px-5 text-xs font-bold uppercase tracking-wider">Pengguna</th>
                        <th class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                        <th class="py-4 px-5 text-xs font-bold uppercase tracking-wider">Modul</th>
                        <th class="py-4 px-5 text-xs font-bold uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-slate-50 transition-colors divide-x divide-slate-100">
                        <td class="py-4 px-5">
                            <span class="text-sm font-bold text-slate-700 block">{{ $activity->created_at->format('d M Y') }}</span>
                            <span class="text-xs font-medium text-slate-400">{{ $activity->created_at->format('H:i:s') }}</span>
                        </td>
                        <td class="py-4 px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                                    <i class="ph-fill ph-user"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-700">{{ $activity->causer->name ?? 'Sistem / Guest' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-5 text-center">
                            @php
                                $eventColor = 'slate';
                                if ($activity->event == 'created') $eventColor = 'blue';
                                if ($activity->event == 'updated') $eventColor = 'amber';
                                if ($activity->event == 'deleted') $eventColor = 'red';
                            @endphp
                            <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-{{ $eventColor }}-50 text-{{ $eventColor }}-600 border border-{{ $eventColor }}-100 uppercase tracking-wide">
                                {{ $activity->event }}
                            </span>
                        </td>
                        <td class="py-4 px-5">
                            <span class="text-sm font-semibold text-slate-600">
                                {{ class_basename($activity->subject_type) }}
                            </span>
                        </td>
                        <td class="py-4 px-5">
                            <p class="text-sm text-slate-600 font-medium line-clamp-2 max-w-sm">
                                {{ $activity->description }}
                            </p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500 font-semibold">Belum ada riwayat aktivitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection
