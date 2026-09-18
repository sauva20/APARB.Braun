<table class="w-full text-left border-collapse">
    <thead>
        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500">
            <th class="py-3 px-5 text-xs font-bold uppercase tracking-wider w-12 text-center">No</th>
            <th class="py-3 px-5 text-xs font-bold uppercase tracking-wider">{{ __('PFE ID') }}</th>
            <th class="py-3 px-5 text-xs font-bold uppercase tracking-wider">{{ __('Location') }}</th>
            <th class="py-3 px-5 text-xs font-bold uppercase tracking-wider">{{ __('Building') }}</th>
            @if(auth()->user() && auth()->user()->role !== 'Staff')
            <th class="py-3 px-5 text-xs font-bold uppercase tracking-wider text-center">{{ __('Action') }}</th>
            @endif
        </tr>
    </thead>
    <tbody class="text-sm divide-y divide-slate-100">
        @forelse($belumDiinspeksiApars as $index => $apar)
        <tr class="hover:bg-slate-50/50 transition-colors">
            <td class="py-3 px-5 text-center font-semibold text-slate-400">{{ $index + 1 }}</td>
            <td class="py-3 px-5 font-bold text-slate-700">{{ $apar->kode }}</td>
            <td class="py-3 px-5 font-medium text-slate-600">{{ $apar->lokasi->nama ?? 'n/a' }}</td>
            <td class="py-3 px-5 font-medium text-slate-600">{{ $apar->lokasi->gedung->nama ?? 'n/a' }}</td>
            @if(auth()->user() && auth()->user()->role !== 'Staff')
            <td class="py-3 px-5 text-center">
                <a href="/scan/{{ $apar->kode }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#009B77] bg-[#009B77]/10 hover:bg-[#009B77]/20 px-3 py-1.5 rounded-lg transition-colors">
                    <i class="ph-bold ph-scan"></i> {{ __('Scan') }}
                </a>
            </td>
            @endif
        </tr>
        @empty
        <tr>
            <td colspan="{{ (auth()->user() && auth()->user()->role !== 'Staff') ? '5' : '4' }}" class="py-8 text-center text-slate-500 font-semibold">{{ __('All PFE have been inspected this month! 🎉') }}</td>
        </tr>
        @endforelse
    </tbody>
</table>
