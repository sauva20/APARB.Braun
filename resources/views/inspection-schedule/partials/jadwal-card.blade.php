<div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm transition-all hover:shadow-md {{ $isTerlewat ? 'ring-1 ring-red-100' : '' }}">
    <div class="flex items-start justify-between gap-4">
        <div class="flex gap-4">
            <div class="w-12 h-12 rounded-xl {{ $isTerlewat ? 'bg-red-50 border-red-200 text-red-500' : 'bg-[#009B77]/5 border-[#009B77]/20 text-[#009B77]' }} border flex items-center justify-center flex-shrink-0">
                @if($jadwal->tipe_area === 'gedung')
                    <i class="ph-duotone ph-buildings text-2xl" title="Inspeksi Seluruh Gedung"></i>
                @else
                    <i class="ph-duotone ph-map-pin text-2xl" title="Inspeksi Lokasi Spesifik"></i>
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 tracking-wider uppercase">{{ $jadwal->jenis_jadwal }}</span>
                    
                    @php
                        $jadwalDate = \Carbon\Carbon::parse($jadwal->tanggal_inspeksi)->startOfDay();
                        $today = \Carbon\Carbon::today();
                        
                        $isHariIni = $jadwalDate->equalTo($today);
                        
                        $dateBg = 'bg-blue-50 text-blue-600 border-blue-100';
                        $dateText = $jadwalDate->translatedFormat('d M Y');
                        
                        if ($isHariIni && $jadwal->status === 'menunggu') {
                            $dateBg = 'bg-indigo-50 text-indigo-600 border-indigo-200';
                            $dateText = 'HARI INI - ' . $dateText;
                        } elseif ($isTerlewat) {
                            $dateBg = 'bg-red-50 text-red-600 border-red-200';
                            $dateText = 'TERLEWAT - ' . $dateText;
                        }
                    @endphp
                    
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $dateBg }} border flex items-center gap-1 uppercase tracking-wider">
                        <i class="ph-bold ph-calendar-blank"></i> {{ $dateText }}
                    </span>
                </div>
                <h3 class="font-extrabold text-slate-800 text-base">
                    @if($jadwal->tipe_area === 'gedung')
                        {{ $jadwal->gedung->nama ?? 'Gedung Terhapus' }} <span class="text-xs font-semibold text-slate-400 ml-1">(Semua Area)</span>
                    @else
                        {{ $jadwal->lokasi->nama ?? 'Lokasi Terhapus' }} <span class="text-xs font-semibold text-slate-400 ml-1">({{ $jadwal->gedung->nama ?? '-' }})</span>
                    @endif
                </h3>
                <p class="text-[13px] font-medium text-slate-500 mt-1 flex items-center gap-1.5">
                    <i class="ph-bold ph-user-circle text-slate-400"></i> Petugas: {{ $jadwal->user ? $jadwal->user->name : 'Bebas / Siapa Saja' }}
                </p>
                @if($jadwal->catatan_tambahan)
                <div class="mt-3 bg-slate-50 p-3 rounded-xl border border-slate-100 flex items-start gap-2">
                    <i class="ph-bold ph-note-pencil text-slate-400 mt-0.5 text-sm"></i>
                    <p class="text-xs font-medium text-slate-600 italic leading-relaxed">
                        "{{ $jadwal->catatan_tambahan }}"
                    </p>
                </div>
                @endif
            </div>
        </div>
        <div class="flex-shrink-0 flex items-center gap-2">
            @if($isTerlewat)
                <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-lg bg-red-50 text-red-600 border border-red-200">
                    TERLEWAT
                </span>
            @else
                <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-lg {{ $jadwal->status === 'menunggu' ? 'bg-orange-50 text-orange-600 border border-orange-200' : 'bg-[#009B77]/10 text-[#009B77] border border-[#009B77]/20' }}">
                    {{ $jadwal->status }}
                </span>
            @endif
            <button type="button" @click="editData = { id: {{ $jadwal->id }}, jenis_jadwal: '{{ addslashes($jadwal->jenis_jadwal) }}', tanggal: '{{ \Carbon\Carbon::parse($jadwal->tanggal_inspeksi)->format('Y-m-d') }}', tipe_area: '{{ $jadwal->tipe_area }}', gedung_id: {{ $jadwal->gedung_id ?? 'null' }}, lokasi_id: {{ $jadwal->lokasi_id ?? 'null' }}, user_id: {{ $jadwal->user_id ?? 'null' }}, catatan_tambahan: '{{ addslashes(str_replace(PHP_EOL, ' ', $jadwal->catatan_tambahan)) }}' }; showModalEditJadwal = true" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-50 transition-colors" title="Edit Jadwal">
                <i class="ph-bold ph-pencil-simple text-lg"></i>
            </button>
            <form action="/inspection-schedule/{{ $jadwal->id }}" method="POST" class="inline-block"
                  onsubmit="event.preventDefault(); Swal.fire({ title: 'Hapus Jadwal?', text: 'Jadwal yang dihapus tidak dapat dikembalikan!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', customClass: { confirmButton: 'rounded-xl', cancelButton: 'rounded-xl' } }).then((result) => { if (result.isConfirmed) { this.submit(); } })">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Hapus Jadwal">
                    <i class="ph-bold ph-trash text-lg"></i>
                </button>
            </form>
        </div>
    </div>
</div>
