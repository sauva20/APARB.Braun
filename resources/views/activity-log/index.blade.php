@extends('layouts.app')

@section('title', 'Log Aktivitas - APAR Monitoring System')

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
                    <h2 class="text-base font-bold text-slate-800 leading-tight">Log Aktivitas</h2>
                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Riwayat tindakan pengguna dalam sistem</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="py-4 px-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu</th>
                        <th class="py-4 px-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Pengguna</th>
                        <th class="py-4 px-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                        <th class="py-4 px-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Modul</th>
                        <th class="py-4 px-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
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
                        <td class="py-4 px-5">
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
                            @if($activity->properties->count() > 0)
                            <div x-data="{ expanded: false }" class="mt-1">
                                <button @click="expanded = !expanded" class="text-xs text-[#009B77] font-semibold hover:underline focus:outline-none">
                                    <span x-show="!expanded">Lihat Detail</span>
                                    <span x-show="expanded">Tutup Detail</span>
                                </button>
                                <div x-show="expanded" class="mt-2 p-3 bg-slate-50 rounded-lg border border-slate-100 text-xs text-slate-600 overflow-x-auto" x-cloak x-collapse>
                                    <pre class="font-mono">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                            @endif
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
