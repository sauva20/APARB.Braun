@extends('layouts.app')

@section('title', 'Audit Trail - PFE Monitoring Control System')

@section('content')
<div class="space-y-6">

    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200/60">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#009B77]/10 border border-[#009B77]/20 shadow-sm flex items-center justify-center text-[#009B77]">
                <i class="ph-bold ph-clock-counter-clockwise text-xl"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-[#007A5E] leading-tight">Audit Trail</h2>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">{{ __('History of user actions in the system') }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('activity-log.export-pdf', request()->all()) }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 text-sm bg-red-50 text-red-600 hover:bg-red-100 font-bold rounded-xl transition-colors">
                <i class="ph-bold ph-file-pdf text-lg"></i>
                {{ __('Export PDF') }}
            </a>

        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#009B77] text-white divide-x divide-white/20">
                        <th class="py-4 px-5 text-xs font-bold uppercase tracking-wider">{{ __('Time') }}</th>
                        <th class="py-4 px-5 text-xs font-bold uppercase tracking-wider">{{ __('User') }}</th>
                        <th class="py-4 px-5 text-xs font-bold uppercase tracking-wider text-center">{{ __('Action') }}</th>
                        <th class="py-4 px-5 text-xs font-bold uppercase tracking-wider">{{ __('Module') }}</th>
                        <th class="py-4 px-5 text-xs font-bold uppercase tracking-wider">{{ __('Description') }}</th>
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
                                <span class="text-sm font-bold text-slate-700">{{ $activity->causer->name ?? __('System / Guest') }}</span>
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
                                {{ __(preg_replace('/([a-z])([A-Z])/s', '$1 $2', class_basename($activity->subject_type))) }}
                            </span>
                        </td>
                        <td class="py-4 px-5">
                            <p class="text-sm text-slate-600 font-medium line-clamp-2 max-w-sm">
                                {{ __($activity->description) }}
                            </p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-500 font-semibold">{{ __('No activity history yet.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $activities->onEachSide(0)->links() }}
        </div>
    </div>
</div>
    <x-excel-preview-modal />
@endsection
