@extends('layouts.app')

@section('title', 'Jadwal Inspeksi - APAR Monitoring System')

@section('content')
<!-- We inject pertanyaan into a JS variable so Alpine can reference it -->
<script>
    window.pertanyaanApar = @json($pertanyaan);
</script>

<div x-data="{ showPanelHasil: false, showPanelDetail: false, showModalBuatJadwal: false, showModalEditJadwal: false, editData: null, selectedInspeksi: null }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-[#009B77]">
                <i class="ph-bold ph-calendar-check text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800 leading-tight">Jadwal Inspeksi</h2>
                <p class="text-sm font-semibold text-slate-500 mt-0.5">Kelola dan pantau rutinitas pengecekan APAR</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button class="bg-white border border-slate-200/60 text-slate-600 hover:text-slate-900 hover:border-slate-300 hover:bg-slate-50 font-bold py-2.5 px-4 rounded-xl shadow-sm transition-all flex items-center gap-2 text-sm hover:-translate-y-0.5">
                <i class="ph-bold ph-file-pdf text-lg"></i>
                <span class="hidden sm:inline">Cetak Jadwal</span>
            </button>
            <button @click="showModalBuatJadwal = true" class="btn-smooth-ring bg-[#009B77] hover:bg-[#008264] text-white font-bold py-2.5 px-5 rounded-xl shadow-[0_4px_12px_rgba(0,155,119,0.25)] transition-all flex items-center gap-2 text-sm hover:-translate-y-0.5">
                <i class="ph-bold ph-calendar-plus text-lg"></i>
                <span class="hidden sm:inline">Buat Jadwal</span>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:h-[calc(100vh-230px)]">
        
        <!-- Left Column: Calendar & Filters -->
        <div class="space-y-6 h-full flex flex-col">
            <!-- Stats Mini -->
            <div class="bg-[#009B77] rounded-2xl p-5 text-white shadow-[0_8px_30px_rgba(0,155,119,0.2)] relative overflow-hidden flex-shrink-0">
                <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full border-4 border-white/10"></div>
                <div class="absolute -right-4 -top-4 w-16 h-16 rounded-full bg-white/10 blur-xl"></div>
                
                <h3 class="text-sm font-bold text-white/80 uppercase tracking-wider mb-1 relative z-10">Total Inspeksi Bulan Ini</h3>
                <h2 class="text-4xl font-extrabold text-white mb-4 relative z-10">{{ $totalApar }}</h2>
                <div class="flex flex-col gap-2 relative z-10">
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-semibold text-white/90">Selesai</span>
                        <span class="font-bold">{{ $selesai }}</span>
                    </div>
                    <div class="w-full bg-white/20 rounded-full h-1.5">
                        <div class="bg-white h-1.5 rounded-full" style="width: {{ $totalApar > 0 ? ($selesai/$totalApar)*100 : 0 }}%"></div>
                    </div>
                    <div class="flex justify-between items-center text-sm mt-1">
                        <span class="font-semibold text-white/90">Menunggu</span>
                        <span class="font-bold">{{ $menunggu }}</span>
                    </div>
                </div>
            </div>

            <!-- Calendar Widget (Mockup) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 flex-1 min-h-[250px]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-800">{{ now()->translatedFormat('F Y') }}</h3>
                </div>
                <div class="grid grid-cols-7 gap-1 text-center text-xs font-bold text-slate-400 mb-2">
                    <div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
                </div>
                <!-- ... Mockup Calendar Content ... -->
                <div class="text-center text-xs text-slate-400 p-4 bg-slate-50 rounded-lg h-full flex flex-col items-center justify-center min-h-[120px]">
                    <i class="ph-bold ph-calendar text-3xl mb-2"></i>
                    <p>Kalender Inspeksi Interaktif <br>(Fitur Sedang Dalam Pengembangan)</p>
                </div>
            </div>
        </div>

        <!-- Right Column: Inspection List -->
        <div class="lg:col-span-2 flex flex-col space-y-4 lg:overflow-y-auto lg:pr-3 pb-8 custom-scrollbar">
            
            <!-- Section: Hari Ini / Bulan Ini -->
            <div class="flex-shrink-0">
                <h2 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-4 flex items-center gap-2 sticky top-0 bg-[#F8FAFC] z-10 py-2">
                    <i class="ph-bold ph-chart-line-up text-slate-500"></i> Progress Inspeksi Bulan Ini
                </h2>
                <div class="space-y-4">
                    
                    @forelse($gedungs as $gedung)
                    @php
                        $aparsInGedung = collect();
                        foreach($gedung->lokasi as $l) {
                            foreach($l->apar as $a) {
                                $aparsInGedung->push($a);
                            }
                        }
                        $countSelesai = $aparsInGedung->filter(fn($a) => $a->inspeksis->isNotEmpty())->count();
                        $countTotal = $aparsInGedung->count();
                    @endphp

                    @if($countTotal > 0)
                    <div x-data="{ expanded: false }" class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md overflow-hidden transition-all flex-shrink-0">
                        <!-- Header -->
                        <div @click="expanded = !expanded" class="cursor-pointer w-full flex items-center justify-between p-5 bg-white hover:bg-slate-50 transition-colors border-b border-slate-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-[#009B77] shadow-sm flex-shrink-0">
                                    <i class="ph-fill ph-buildings text-2xl"></i>
                                </div>
                                <div class="text-left">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-bold text-slate-800 text-base">{{ $gedung->nama }}</h4>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs font-semibold text-slate-500">
                                        <span class="flex items-center gap-1"><i class="ph-bold ph-fire-extinguisher text-slate-400"></i> {{ $countTotal }} APAR</span>
                                        <span class="flex items-center gap-1"><i class="ph-bold ph-check-circle text-[#009B77]"></i> {{ $countSelesai }}/{{ $countTotal }} Selesai</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''">
                                    <i class="ph-bold ph-caret-down text-sm"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Body -->
                        <div x-show="expanded" x-collapse>
                            <div class="p-5 bg-white space-y-3">
                                @foreach($aparsInGedung as $apar)
                                    @if($apar->inspeksis->isEmpty())
                                    <!-- APAR Menunggu -->
                                    <div class="p-4 rounded-xl border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:border-slate-200 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 flex-shrink-0">
                                                <i class="ph-fill ph-fire-extinguisher text-xl"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-bold text-slate-800 text-sm">{{ $apar->kode }}</h5>
                                                <p class="text-xs font-semibold text-slate-500 mb-1">{{ $apar->lokasi->nama ?? '-' }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 flex items-center gap-1"><i class="ph-bold ph-user"></i> Ditugaskan ke: Siapa Saja</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 w-full sm:w-auto">
                                            <span class="hidden sm:inline-flex px-2 py-1 rounded-md text-[10px] font-bold bg-amber-50 text-amber-500 border border-amber-100">Menunggu</span>
                                            <a href="{{ route('inspeksi.mulai', $apar->id) }}" class="w-full sm:w-auto text-center bg-white border-2 border-slate-200 hover:border-[#009B77] text-slate-600 hover:text-[#009B77] font-bold py-1.5 px-4 rounded-lg transition-colors text-xs flex items-center justify-center gap-1.5 shadow-sm">
                                                Mulai <i class="ph-bold ph-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @else
                                    <!-- APAR Selesai -->
                                    @php $inspeksi = $apar->inspeksis->first(); @endphp
                                    <div class="p-4 rounded-xl border border-[#009B77]/20 bg-[#009B77]/5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div class="flex items-start gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-white border border-[#009B77]/20 flex items-center justify-center text-[#009B77] flex-shrink-0">
                                                <i class="ph-fill ph-fire-extinguisher text-xl"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-bold text-slate-800 text-sm">{{ $apar->kode }}</h5>
                                                <p class="text-xs font-semibold text-slate-500 mb-1">{{ $apar->lokasi->nama ?? '-' }}</p>
                                                <p class="text-[10px] font-bold text-[#009B77] flex items-center gap-1"><i class="ph-bold ph-check-circle"></i> Selesai oleh {{ $inspeksi->user->name ?? 'User' }} ({{ $inspeksi->created_at->format('d M, H:i') }})</p>
                                            </div>
                                        </div>
                                        @php
                                            $inspeksiData = [
                                                'apar_kode' => $apar->kode,
                                                'lokasi' => $apar->lokasi->nama ?? '-',
                                                'waktu' => $inspeksi->created_at->format('d M Y, H:i'),
                                                'petugas' => $inspeksi->user->name ?? 'User',
                                                'status' => $inspeksi->status,
                                                'catatan' => $inspeksi->catatan_tambahan,
                                                'checklist' => is_string($inspeksi->checklist) ? json_decode($inspeksi->checklist, true) : $inspeksi->checklist
                                            ];
                                        @endphp
                                        <div class="flex items-center gap-2 w-full sm:w-auto">
                                            <button @click='selectedInspeksi = @json($inspeksiData); showPanelHasil = true' class="w-full sm:w-auto text-center bg-white border border-slate-200 hover:border-slate-300 text-slate-600 font-bold py-1.5 px-4 rounded-lg transition-colors text-xs flex items-center justify-center gap-1.5 shadow-sm">
                                                Lihat Hasil
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="p-8 text-center bg-white rounded-2xl border border-slate-200 border-dashed text-slate-500 flex-shrink-0">
                        <i class="ph-duotone ph-buildings text-4xl mb-3"></i>
                        <p class="font-bold">Belum ada data APAR atau Gedung.</p>
                    </div>
                    @endforelse
                </div>
                </div>

                <!-- Daftar Jadwal Mendatang -->
                @php
                    $jadwalTerlewat = collect();
                    $jadwalHariIni = collect();
                    $jadwalMendatang = collect();
                    $today = \Carbon\Carbon::today();

                    foreach($jadwals as $jadwal) {
                        $jadwalDate = \Carbon\Carbon::parse($jadwal->tanggal_inspeksi)->startOfDay();
                        if ($jadwalDate->lessThan($today) && $jadwal->status === 'menunggu') {
                            $jadwalTerlewat->push($jadwal);
                        } elseif ($jadwalDate->equalTo($today) && $jadwal->status === 'menunggu') {
                            $jadwalHariIni->push($jadwal);
                        } else {
                            $jadwalMendatang->push($jadwal);
                        }
                    }
                @endphp

                @if($jadwalHariIni->isNotEmpty())
                <div class="mt-10">
                    <h2 class="text-[11px] font-bold text-indigo-500 uppercase tracking-wider flex items-center gap-2 mb-4"><i class="ph-bold ph-calendar-star"></i> Jadwal Hari Ini</h2>
                    
                    <div class="space-y-4">
                        @foreach($jadwalHariIni as $jadwal)
                            @php
                                $isTerlewat = false;
                            @endphp
                            @include('inspection-schedule.partials.jadwal-card')
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-10">
                    <h2 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-2 mb-4"><i class="ph-bold ph-calendar-check"></i> Daftar Jadwal Mendatang</h2>
                    
                    <div class="space-y-4">
                        @forelse($jadwalMendatang as $jadwal)
                            @php
                                $isTerlewat = false;
                            @endphp
                            @include('inspection-schedule.partials.jadwal-card')
                        @empty
                        <div class="p-8 text-center bg-white rounded-2xl border border-slate-200 border-dashed text-slate-400 flex-shrink-0">
                            <i class="ph-duotone ph-calendar-x text-4xl mb-3 text-slate-300"></i>
                            <p class="font-bold text-sm">Belum ada jadwal inspeksi mendatang.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                @if($jadwalTerlewat->isNotEmpty())
                <div class="mt-10">
                    <h2 class="text-[11px] font-bold text-red-500 uppercase tracking-wider flex items-center gap-2 mb-4"><i class="ph-bold ph-warning-circle"></i> Jadwal Terlewat</h2>
                    
                    <div class="space-y-4">
                        @foreach($jadwalTerlewat as $jadwal)
                            @php
                                $isTerlewat = true;
                            @endphp
                            @include('inspection-schedule.partials.jadwal-card')
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
    </div>

    <!-- Slide-over Panel: Lihat Hasil -->
    <div x-show="showPanelHasil" class="relative z-[100]" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" style="display: none;">
        <div x-show="showPanelHasil" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/40 transition-opacity" @click="showPanelHasil = false"></div>
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                    <div x-show="showPanelHasil" @click.away="showPanelHasil = false" x-transition:enter="transform transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="pointer-events-auto w-screen max-w-md">
                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-2xl" x-data="{ tabs: 'checklist' }">
                            <template x-if="selectedInspeksi">
                                <div>
                                    <div class="p-6 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white/90 backdrop-blur-md z-10">
                                        <div>
                                            <h2 class="text-lg font-extrabold text-slate-800" id="slide-over-title">Hasil Inspeksi APAR</h2>
                                            <p class="text-xs font-semibold text-[#009B77] mt-0.5"><i class="ph-bold ph-check-circle mr-1"></i>Selesai pada <span x-text="selectedInspeksi.waktu"></span></p>
                                        </div>
                                        <button type="button" @click="showPanelHasil = false" class="relative rounded-xl w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                                            <i class="ph-bold ph-x text-lg"></i>
                                        </button>
                                    </div>
                                    <div class="p-6 space-y-6">
                                        <!-- Info Header -->
                                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                            <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 flex-shrink-0">
                                                <i class="ph-fill ph-fire-extinguisher text-2xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-slate-800" x-text="selectedInspeksi.apar_kode"></h3>
                                                <p class="text-xs font-semibold text-slate-500" x-text="selectedInspeksi.lokasi"></p>
                                            </div>
                                        </div>

                                        <!-- Status Akhir -->
                                        <div class="p-4 rounded-xl border flex items-center gap-3"
                                             :class="selectedInspeksi.status === 'layak' ? 'border-[#009B77]/20 bg-[#009B77]/5' : 'border-red-200 bg-red-50'">
                                            <i class="text-2xl" :class="selectedInspeksi.status === 'layak' ? 'ph-fill ph-shield-check text-[#009B77]' : 'ph-fill ph-warning-circle text-red-500'"></i>
                                            <div>
                                                <span class="block text-[10px] font-bold uppercase tracking-wider" :class="selectedInspeksi.status === 'layak' ? 'text-[#009B77]' : 'text-red-500'">Status Akhir</span>
                                                <span class="font-bold text-slate-800 text-sm capitalize" x-text="selectedInspeksi.status.replace('_', ' ')"></span>
                                            </div>
                                        </div>
                                        
                                        <!-- Tabs -->
                                        <div class="flex gap-2 p-1 bg-slate-100 rounded-xl">
                                            <button @click="tabs = 'checklist'" class="flex-1 py-1.5 text-xs font-bold rounded-lg transition-all" :class="tabs === 'checklist' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'">Checklist</button>
                                            <button @click="tabs = 'catatan'" class="flex-1 py-1.5 text-xs font-bold rounded-lg transition-all" :class="tabs === 'catatan' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'">Catatan & Petugas</button>
                                        </div>

                                        <!-- Checklist Results -->
                                        <div x-show="tabs === 'checklist'">
                                            <div class="space-y-3">
                                                <template x-for="(item, index) in selectedInspeksi.checklist" :key="index">
                                                    <div class="flex items-start gap-3 p-3 rounded-xl border" :class="item.jawaban === 'ya' ? 'border-slate-100 bg-white' : 'border-red-200 bg-red-50'">
                                                        <i class="text-xl mt-0.5 flex-shrink-0" :class="item.jawaban === 'ya' ? 'ph-fill ph-check-circle text-[#009B77]' : 'ph-fill ph-x-circle text-red-500'"></i>
                                                        <div>
                                                            <p class="font-bold text-slate-800 text-xs leading-relaxed" x-text="window.pertanyaanApar[index]"></p>
                                                            <template x-if="item.jawaban === 'tidak'">
                                                                <p class="text-xs font-bold text-red-600 mt-1.5 bg-white px-2 py-1 rounded border border-red-100 inline-block">
                                                                    Kendala: <span class="font-medium" x-text="item.keterangan || '-'"></span>
                                                                </p>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Inspector Info -->
                                        <div x-show="tabs === 'catatan'" style="display: none;">
                                            <div class="p-4 rounded-2xl border border-slate-100 space-y-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                                        <i class="ph-fill ph-user text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-800 text-sm" x-text="selectedInspeksi.petugas"></p>
                                                        <p class="text-xs font-medium text-slate-500">Petugas Pemeriksa</p>
                                                    </div>
                                                </div>
                                                <div class="border-t border-slate-100 pt-4">
                                                    <p class="text-xs font-bold text-slate-500 mb-2">Catatan Tambahan:</p>
                                                    <p class="text-sm font-medium text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-100" x-text="selectedInspeksi.catatan || 'Tidak ada catatan khusus.'"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Buat Jadwal -->
    <div x-show="showModalBuatJadwal" 
         class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 sm:p-6 lg:p-8"
         style="display: none;">
        
        <!-- Backdrop -->
        <div x-show="showModalBuatJadwal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showModalBuatJadwal = false"
             class="fixed inset-0 bg-slate-900/40 z-[-1]">
        </div>

        <!-- Modal Content -->
        <div x-show="showModalBuatJadwal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative w-full max-w-2xl bg-white rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] border border-slate-100 overflow-hidden my-auto">
             
            <!-- Header Modal -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-[#009B77]">
                        <i class="ph-bold ph-calendar-plus text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 leading-tight">Buat Jadwal Inspeksi</h3>
                        <p class="text-xs font-semibold text-slate-500">Tentukan area, tanggal, dan petugas inspeksi.</p>
                    </div>
                </div>
                <button @click="showModalBuatJadwal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <!-- Body Modal -->
            <form action="/inspection-schedule" method="POST" class="p-6" x-data="{
                formJenis: '',
                formTanggal: '',
                formTipeArea: 'gedung',
                formGedungId: '',
                formLokasiId: '',
                formUserId: '',
                get isFormValid() {
                    if (!this.formJenis) return false;
                    if (!this.formTanggal) return false;
                    if (this.formTipeArea === 'gedung' && !this.formGedungId) return false;
                    if (this.formTipeArea === 'lokasi' && !this.formLokasiId) return false;
                    if (!this.formUserId) return false;
                    return true;
                }
            }">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <!-- Jenis Jadwal -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis Jadwal</label>
                        <div x-data="{ open: false, options: ['Inspeksi Rutin Bulanan', 'Inspeksi Khusus / Temuan'] }" class="relative">
                            <input type="hidden" name="jenis_jadwal" :value="formJenis">
                            <i class="ph-bold ph-calendar-star absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base transition-colors z-10" :class="open ? 'text-[#009B77]' : ''"></i>
                            <button type="button" @click="open = !open" @click.away="open = false" 
                                    class="w-full bg-white border-2 border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm font-medium text-slate-700 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                    :class="open ? 'border-[#009B77] ring-4 ring-[#009B77]/15' : ''">
                                <span x-text="formJenis || 'Pilih Jenis'" :class="!formJenis ? 'text-slate-400 font-medium' : ''" class="truncate block"></span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180 text-[#009B77]' : ''"></i>
                            </button>
                            <!-- Dropdown -->
                            <div x-show="open" style="display: none;" class="absolute left-0 z-50 w-full mt-1.5 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 overflow-hidden origin-top">
                                <template x-for="option in options">
                                    <button type="button" @click="formJenis = option; open = false" class="w-full text-left px-4 py-2 text-sm transition-colors flex items-center justify-between" :class="formJenis === option ? 'text-[#009B77] bg-[#009B77]/5 font-bold' : 'text-slate-600 font-medium hover:bg-slate-50'">
                                        <span x-text="option"></span>
                                        <i class="ph-bold ph-check text-[#009B77]" x-show="formJenis === option"></i>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Inspeksi -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Inspeksi</label>
                        <div class="relative" x-data x-init="flatpickr($refs.dateInput, { dateFormat: 'Y-m-d', minDate: 'today', locale: 'id', onChange: function(s, d) { formTanggal = d; } })">
                            <i class="ph-bold ph-calendar-blank absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base transition-colors peer-focus:text-[#009B77] z-10"></i>
                            <input x-ref="dateInput" name="tanggal" type="text" placeholder="Pilih Tanggal" required
                                   class="peer w-full bg-white border-2 border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm font-medium text-slate-700 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none placeholder:text-slate-400 placeholder:font-medium appearance-none cursor-pointer">
                        </div>
                    </div>

                    <!-- Cakupan Lokasi/Gedung -->
                    <div class="col-span-1" x-data="{
                        optionsGedung: [
                            @foreach($gedungs as $gedung)
                            { id: {{ $gedung->id }}, nama: '{{ addslashes($gedung->nama) }}' },
                            @endforeach
                        ],
                        optionsLokasi: [
                            @foreach($gedungs as $gedung)
                                @foreach($gedung->lokasi as $lokasi)
                                { id: {{ $lokasi->id }}, nama: '{{ addslashes($lokasi->nama) }}', gedung_nama: '{{ addslashes($gedung->nama) }}', gedung_id: {{ $gedung->id }} },
                                @endforeach
                            @endforeach
                        ],
                        
                        get selectedGedungName() {
                            let g = this.optionsGedung.find(x => x.id === this.formGedungId);
                            return g ? g.nama : '';
                        },
                        get selectedLokasiName() {
                            let l = this.optionsLokasi.find(x => x.id === this.formLokasiId);
                            return l ? l.nama + ' (' + l.gedung_nama + ')' : '';
                        }
                    }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Cakupan Area</label>
                            
                            <!-- Toggle Tipe Area -->
                            <div class="flex items-center gap-3">
                                <label class="flex items-center gap-1.5 cursor-pointer group">
                                    <input type="radio" x-model="formTipeArea" value="gedung" name="tipe_area" class="w-3.5 h-3.5 text-[#009B77] border-slate-300 focus:ring-[#009B77] cursor-pointer">
                                    <span class="text-[10px] font-bold text-slate-500 group-hover:text-slate-800 transition-colors uppercase tracking-wider">Gedung</span>
                                </label>
                                <label class="flex items-center gap-1.5 cursor-pointer group">
                                    <input type="radio" x-model="formTipeArea" value="lokasi" name="tipe_area" class="w-3.5 h-3.5 text-[#009B77] border-slate-300 focus:ring-[#009B77] cursor-pointer">
                                    <span class="text-[10px] font-bold text-slate-500 group-hover:text-slate-800 transition-colors uppercase tracking-wider">Spesifik</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Dropdown Gedung -->
                        <div x-show="formTipeArea === 'gedung'" x-data="{ open: false, search: '' }" class="relative">
                            <input type="hidden" name="gedung_id" :value="formGedungId">
                            <i class="ph-bold ph-buildings absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base transition-colors z-10" :class="open ? 'text-[#009B77]' : ''"></i>
                            <button type="button" @click="open = !open" @click.away="open = false; search = ''" 
                                    class="w-full bg-white border-2 border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm font-medium text-slate-700 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                    :class="open ? 'border-[#009B77] ring-4 ring-[#009B77]/15' : ''">
                                <span x-text="selectedGedungName || 'Pilih Gedung'" :class="!selectedGedungName ? 'text-slate-400 font-medium' : ''" class="truncate block"></span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180 text-[#009B77]' : ''"></i>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute left-0 z-50 w-full mt-1.5 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 overflow-hidden origin-top max-h-56 flex flex-col">
                                <div class="px-2 pb-1.5 mb-1.5 border-b border-slate-100 flex-shrink-0">
                                    <div class="relative">
                                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" x-model="search" placeholder="Cari gedung..." class="w-full bg-slate-50 border-none rounded-lg py-1.5 pl-9 pr-3 text-xs font-medium text-slate-700 focus:ring-0 placeholder:text-slate-400" @click.stop>
                                    </div>
                                </div>
                                <div class="overflow-y-auto">
                                    <template x-if="optionsGedung.length === 0">
                                        <div class="px-4 py-3 text-sm text-slate-500 font-medium text-center">Belum ada gedung.</div>
                                    </template>
                                    <template x-for="option in optionsGedung" :key="option.id">
                                        <button type="button" x-show="option.nama.toLowerCase().includes(search.toLowerCase())" @click="formGedungId = option.id; open = false; search = ''" class="w-full text-left px-4 py-2 text-sm transition-colors flex items-center justify-between" :class="formGedungId === option.id ? 'text-[#009B77] bg-[#009B77]/5 font-bold' : 'text-slate-600 font-medium hover:bg-slate-50'">
                                            <span x-text="option.nama"></span>
                                            <i class="ph-bold ph-check text-[#009B77]" x-show="formGedungId === option.id"></i>
                                        </button>
                                    </template>
                                    <div x-show="optionsGedung.length > 0 && optionsGedung.filter(o => o.nama.toLowerCase().includes(search.toLowerCase())).length === 0" class="px-4 py-3 text-sm text-slate-500 font-medium text-center">Tidak ditemukan.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Dropdown Lokasi -->
                        <div x-show="formTipeArea === 'lokasi'" x-data="{ open: false, search: '' }" class="relative" style="display:none;">
                            <input type="hidden" name="lokasi_id" :value="formLokasiId">
                            <i class="ph-bold ph-map-pin absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base transition-colors z-10" :class="open ? 'text-[#009B77]' : ''"></i>
                            <button type="button" @click="open = !open" @click.away="open = false; search = ''" 
                                    class="w-full bg-white border-2 border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm font-medium text-slate-700 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                    :class="open ? 'border-[#009B77] ring-4 ring-[#009B77]/15' : ''">
                                <span x-text="selectedLokasiName || 'Pilih Lokasi'" :class="!selectedLokasiName ? 'text-slate-400 font-medium' : ''" class="truncate block"></span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180 text-[#009B77]' : ''"></i>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute left-0 z-50 w-full mt-1.5 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 overflow-hidden origin-top max-h-60 flex flex-col">
                                <div class="px-2 pb-1.5 mb-1.5 border-b border-slate-100 flex-shrink-0">
                                    <div class="relative">
                                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" x-model="search" placeholder="Cari lokasi..." class="w-full bg-slate-50 border-none rounded-lg py-1.5 pl-9 pr-3 text-xs font-medium text-slate-700 focus:ring-0 placeholder:text-slate-400" @click.stop>
                                    </div>
                                </div>
                                <div class="overflow-y-auto">
                                    <template x-if="optionsLokasi.length === 0">
                                        <div class="px-4 py-3 text-sm text-slate-500 font-medium text-center">Belum ada lokasi.</div>
                                    </template>
                                    <template x-for="option in optionsLokasi" :key="option.id">
                                        <button type="button" x-show="option.nama.toLowerCase().includes(search.toLowerCase()) || option.gedung_nama.toLowerCase().includes(search.toLowerCase())" @click="formLokasiId = option.id; open = false; search = ''" class="w-full text-left px-4 py-2.5 transition-colors flex items-center justify-between" :class="formLokasiId === option.id ? 'bg-[#009B77]/5' : 'hover:bg-slate-50'">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold" :class="formLokasiId === option.id ? 'text-[#009B77]' : 'text-slate-700'" x-text="option.nama"></span>
                                                <span class="text-[11px] font-medium text-slate-400 mt-0.5" x-text="option.gedung_nama"></span>
                                            </div>
                                            <i class="ph-bold ph-check text-[#009B77]" x-show="formLokasiId === option.id"></i>
                                        </button>
                                    </template>
                                    <div x-show="optionsLokasi.length > 0 && optionsLokasi.filter(o => o.nama.toLowerCase().includes(search.toLowerCase()) || o.gedung_nama.toLowerCase().includes(search.toLowerCase())).length === 0" class="px-4 py-3 text-sm text-slate-500 font-medium text-center">Tidak ditemukan.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Petugas Inspeksi -->
                    <!-- Petugas Inspeksi -->
                    <div x-data="{
                        optionsUser: [
                            @foreach($users as $user)
                            { id: {{ $user->id }}, nama: '{{ addslashes($user->name) }} ({{ addslashes($user->role) }})' },
                            @endforeach
                        ],
                        get selectedUserName() {
                            let u = this.optionsUser.find(x => x.id === this.formUserId);
                            return u ? u.nama : '';
                        }
                    }">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Petugas Inspeksi</label>
                        <div x-data="{ open: false, search: '' }" class="relative">
                            <input type="hidden" name="user_id" :value="formUserId">
                            <i class="ph-bold ph-user-circle absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base transition-colors z-10" :class="open ? 'text-[#009B77]' : ''"></i>
                            <button type="button" @click="open = !open" @click.away="open = false; search = ''" 
                                    class="w-full bg-white border-2 border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm font-medium text-slate-700 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                    :class="open ? 'border-[#009B77] ring-4 ring-[#009B77]/15' : ''">
                                <span x-text="selectedUserName || 'Pilih Petugas'" :class="!selectedUserName ? 'text-slate-400 font-medium' : ''" class="truncate block"></span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180 text-[#009B77]' : ''"></i>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute left-0 z-50 w-full mt-1.5 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 overflow-hidden origin-top max-h-56 flex flex-col">
                                <div class="px-2 pb-1.5 mb-1.5 border-b border-slate-100 flex-shrink-0">
                                    <div class="relative">
                                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" x-model="search" placeholder="Cari petugas..." class="w-full bg-slate-50 border-none rounded-lg py-1.5 pl-9 pr-3 text-xs font-medium text-slate-700 focus:ring-0 placeholder:text-slate-400" @click.stop>
                                    </div>
                                </div>
                                <div class="overflow-y-auto">
                                    <template x-for="option in optionsUser" :key="option.id">
                                        <button type="button" x-show="option.nama.toLowerCase().includes(search.toLowerCase())" @click="formUserId = option.id; open = false; search = ''" class="w-full text-left px-4 py-2 text-sm transition-colors flex items-center justify-between" :class="formUserId === option.id ? 'text-[#009B77] bg-[#009B77]/5 font-bold' : 'text-slate-600 font-medium hover:bg-slate-50'">
                                            <span x-text="option.nama"></span>
                                            <i class="ph-bold ph-check text-[#009B77]" x-show="formUserId === option.id"></i>
                                        </button>
                                    </template>
                                    <div x-show="optionsUser.filter(o => o.nama.toLowerCase().includes(search.toLowerCase())).length === 0" class="px-4 py-3 text-sm text-slate-500 font-medium text-center">Tidak ditemukan.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Catatan Tambahan (Opsional)</label>
                        <div class="relative">
                            <i class="ph-bold ph-note-pencil absolute left-3.5 top-3.5 text-slate-400 text-base transition-colors peer-focus:text-[#009B77]"></i>
                            <textarea name="catatan_tambahan" rows="2" placeholder="Fokuskan pada area..." class="peer w-full bg-white border-2 border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm font-medium text-slate-700 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none placeholder:text-slate-400 placeholder:font-medium resize-none"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showModalBuatJadwal = false" class="px-5 py-2 rounded-xl font-bold text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" 
                            :disabled="!isFormValid"
                            :class="isFormValid ? 'bg-[#009B77] hover:bg-[#008264] text-white shadow-sm' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                            class="font-bold py-2.5 px-6 rounded-xl transition-all text-sm flex items-center justify-center gap-2">
                        <i class="ph-bold ph-floppy-disk text-lg"></i>
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

        <!-- Modal Edit Jadwal -->
    <div x-show="showModalEditJadwal" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-cloak>
        <!-- Backdrop -->
        <div x-show="showModalEditJadwal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showModalEditJadwal = false"
             class="fixed inset-0 bg-slate-900/40 z-[-1]">
        </div>

        <!-- Modal Content -->
        <div x-show="showModalEditJadwal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative w-full max-w-2xl bg-white rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] border border-slate-100 overflow-hidden my-auto">
             
            <!-- Header Modal -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-[#009B77]">
                        <i class="ph-bold ph-pencil-simple text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 leading-tight">Edit Jadwal Inspeksi</h3>
                        <p class="text-xs font-semibold text-slate-500">Perbarui area, tanggal, dan petugas inspeksi.</p>
                    </div>
                </div>
                <button @click="showModalEditJadwal = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <!-- Body Modal -->
            <form :action="'/inspection-schedule/' + editData?.id" method="POST" class="p-6" x-data="{
                get formJenis() { return editData?.jenis_jadwal || '' },
                set formJenis(val) { if(editData) editData.jenis_jadwal = val },
                get formTanggal() { return editData?.tanggal || '' },
                set formTanggal(val) { if(editData) editData.tanggal = val },
                get formTipeArea() { return editData?.tipe_area || 'gedung' },
                set formTipeArea(val) { if(editData) editData.tipe_area = val },
                get formGedungId() { return editData?.gedung_id || '' },
                set formGedungId(val) { if(editData) editData.gedung_id = val },
                get formLokasiId() { return editData?.lokasi_id || '' },
                set formLokasiId(val) { if(editData) editData.lokasi_id = val },
                get formUserId() { return editData?.user_id || '' },
                set formUserId(val) { if(editData) editData.user_id = val },
                get formCatatan() { return editData?.catatan_tambahan || '' },
                set formCatatan(val) { if(editData) editData.catatan_tambahan = val },
                get isFormValid() {
                    if (!this.formJenis) return false;
                    if (!this.formTanggal) return false;
                    if (this.formTipeArea === 'gedung' && !this.formGedungId) return false;
                    if (this.formTipeArea === 'lokasi' && !this.formLokasiId) return false;
                    if (!this.formUserId) return false;
                    return true;
                }
            }">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <!-- Jenis Jadwal -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis Jadwal</label>
                        <div x-data="{ open: false, options: ['Inspeksi Rutin Bulanan', 'Inspeksi Khusus / Temuan'], get selected() { return formJenis }, set selected(val) { formJenis = val } }" class="relative">
                            <input type="hidden" name="jenis_jadwal" :value="formJenis">
                            <i class="ph-bold ph-calendar-star absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base transition-colors z-10" :class="open ? 'text-[#009B77]' : ''"></i>
                            <button type="button" @click="open = !open" @click.away="open = false" 
                                    class="w-full bg-white border-2 border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm font-medium text-slate-700 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                    :class="open ? 'border-[#009B77] ring-4 ring-[#009B77]/15' : ''">
                                <span x-text="formJenis || 'Pilih Jenis'" :class="!formJenis ? 'text-slate-400 font-medium' : ''" class="truncate block"></span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180 text-[#009B77]' : ''"></i>
                            </button>
                            <!-- Dropdown -->
                            <div x-show="open" style="display: none;" class="absolute left-0 z-50 w-full mt-1.5 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 overflow-hidden origin-top">
                                <template x-for="option in options">
                                    <button type="button" @click="formJenis = option; open = false" class="w-full text-left px-4 py-2 text-sm transition-colors flex items-center justify-between" :class="formJenis === option ? 'text-[#009B77] bg-[#009B77]/5 font-bold' : 'text-slate-600 font-medium hover:bg-slate-50'">
                                        <span x-text="option"></span>
                                        <i class="ph-bold ph-check text-[#009B77]" x-show="formJenis === option"></i>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Inspeksi -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Inspeksi</label>
                        <div class="relative" x-data x-init="let fp = flatpickr($refs.dateInput, { dateFormat: 'Y-m-d', locale: 'id', onChange: function(s, d) { formTanggal = d; } }); $watch('formTanggal', val => { if(val) fp.setDate(val) })">
                            <i class="ph-bold ph-calendar-blank absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base transition-colors peer-focus:text-[#009B77] z-10"></i>
                            <input x-ref="dateInput" name="tanggal" type="text" placeholder="Pilih Tanggal" required
                                   class="peer w-full bg-white border-2 border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm font-medium text-slate-700 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none placeholder:text-slate-400 placeholder:font-medium appearance-none cursor-pointer">
                        </div>
                    </div>

                    <!-- Cakupan Lokasi/Gedung -->
                    <div class="col-span-1" x-data="{
                        optionsGedung: [
                            @foreach($gedungs as $gedung)
                            { id: {{ $gedung->id }}, nama: '{{ addslashes($gedung->nama) }}' },
                            @endforeach
                        ],
                        optionsLokasi: [
                            @foreach($gedungs as $gedung)
                                @foreach($gedung->lokasi as $lokasi)
                                { id: {{ $lokasi->id }}, nama: '{{ addslashes($lokasi->nama) }}', gedung_nama: '{{ addslashes($gedung->nama) }}', gedung_id: {{ $gedung->id }} },
                                @endforeach
                            @endforeach
                        ],
                        
                        get selectedGedungName() {
                            let g = this.optionsGedung.find(x => x.id === this.formGedungId);
                            return g ? g.nama : '';
                        },
                        get selectedLokasiName() {
                            let l = this.optionsLokasi.find(x => x.id === this.formLokasiId);
                            return l ? l.nama + ' (' + l.gedung_nama + ')' : '';
                        }
                    }">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider">Cakupan Area</label>
                            
                            <!-- Toggle Tipe Area -->
                            <div class="flex items-center gap-3">
                                <label class="flex items-center gap-1.5 cursor-pointer group">
                                    <input type="radio" x-model="formTipeArea" value="gedung" name="tipe_area" class="w-3.5 h-3.5 text-[#009B77] border-slate-300 focus:ring-[#009B77] cursor-pointer">
                                    <span class="text-[10px] font-bold text-slate-500 group-hover:text-slate-800 transition-colors uppercase tracking-wider">Gedung</span>
                                </label>
                                <label class="flex items-center gap-1.5 cursor-pointer group">
                                    <input type="radio" x-model="formTipeArea" value="lokasi" name="tipe_area" class="w-3.5 h-3.5 text-[#009B77] border-slate-300 focus:ring-[#009B77] cursor-pointer">
                                    <span class="text-[10px] font-bold text-slate-500 group-hover:text-slate-800 transition-colors uppercase tracking-wider">Spesifik</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Dropdown Gedung -->
                        <div x-show="formTipeArea === 'gedung'" x-data="{ open: false, search: '' }" class="relative">
                            <input type="hidden" name="gedung_id" :value="formGedungId">
                            <i class="ph-bold ph-buildings absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base transition-colors z-10" :class="open ? 'text-[#009B77]' : ''"></i>
                            <button type="button" @click="open = !open" @click.away="open = false; search = ''" 
                                    class="w-full bg-white border-2 border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm font-medium text-slate-700 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                    :class="open ? 'border-[#009B77] ring-4 ring-[#009B77]/15' : ''">
                                <span x-text="selectedGedungName || 'Pilih Gedung'" :class="!selectedGedungName ? 'text-slate-400 font-medium' : ''" class="truncate block"></span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180 text-[#009B77]' : ''"></i>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute left-0 z-50 w-full mt-1.5 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 overflow-hidden origin-top max-h-56 flex flex-col">
                                <div class="px-2 pb-1.5 mb-1.5 border-b border-slate-100 flex-shrink-0">
                                    <div class="relative">
                                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" x-model="search" placeholder="Cari gedung..." class="w-full bg-slate-50 border-none rounded-lg py-1.5 pl-9 pr-3 text-xs font-medium text-slate-700 focus:ring-0 placeholder:text-slate-400" @click.stop>
                                    </div>
                                </div>
                                <div class="overflow-y-auto">
                                    <template x-if="optionsGedung.length === 0">
                                        <div class="px-4 py-3 text-sm text-slate-500 font-medium text-center">Belum ada gedung.</div>
                                    </template>
                                    <template x-for="option in optionsGedung" :key="option.id">
                                        <button type="button" x-show="option.nama.toLowerCase().includes(search.toLowerCase())" @click="formGedungId = option.id; open = false; search = ''" class="w-full text-left px-4 py-2 text-sm transition-colors flex items-center justify-between" :class="formGedungId === option.id ? 'text-[#009B77] bg-[#009B77]/5 font-bold' : 'text-slate-600 font-medium hover:bg-slate-50'">
                                            <span x-text="option.nama"></span>
                                            <i class="ph-bold ph-check text-[#009B77]" x-show="formGedungId === option.id"></i>
                                        </button>
                                    </template>
                                    <div x-show="optionsGedung.length > 0 && optionsGedung.filter(o => o.nama.toLowerCase().includes(search.toLowerCase())).length === 0" class="px-4 py-3 text-sm text-slate-500 font-medium text-center">Tidak ditemukan.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Dropdown Lokasi -->
                        <div x-show="formTipeArea === 'lokasi'" x-data="{ open: false, search: '' }" class="relative" style="display:none;">
                            <input type="hidden" name="lokasi_id" :value="formLokasiId">
                            <i class="ph-bold ph-map-pin absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base transition-colors z-10" :class="open ? 'text-[#009B77]' : ''"></i>
                            <button type="button" @click="open = !open" @click.away="open = false; search = ''" 
                                    class="w-full bg-white border-2 border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm font-medium text-slate-700 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                    :class="open ? 'border-[#009B77] ring-4 ring-[#009B77]/15' : ''">
                                <span x-text="selectedLokasiName || 'Pilih Lokasi'" :class="!selectedLokasiName ? 'text-slate-400 font-medium' : ''" class="truncate block"></span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180 text-[#009B77]' : ''"></i>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute left-0 z-50 w-full mt-1.5 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 overflow-hidden origin-top max-h-60 flex flex-col">
                                <div class="px-2 pb-1.5 mb-1.5 border-b border-slate-100 flex-shrink-0">
                                    <div class="relative">
                                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" x-model="search" placeholder="Cari lokasi..." class="w-full bg-slate-50 border-none rounded-lg py-1.5 pl-9 pr-3 text-xs font-medium text-slate-700 focus:ring-0 placeholder:text-slate-400" @click.stop>
                                    </div>
                                </div>
                                <div class="overflow-y-auto">
                                    <template x-if="optionsLokasi.length === 0">
                                        <div class="px-4 py-3 text-sm text-slate-500 font-medium text-center">Belum ada lokasi.</div>
                                    </template>
                                    <template x-for="option in optionsLokasi" :key="option.id">
                                        <button type="button" x-show="option.nama.toLowerCase().includes(search.toLowerCase()) || option.gedung_nama.toLowerCase().includes(search.toLowerCase())" @click="formLokasiId = option.id; open = false; search = ''" class="w-full text-left px-4 py-2.5 transition-colors flex items-center justify-between" :class="formLokasiId === option.id ? 'bg-[#009B77]/5' : 'hover:bg-slate-50'">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold" :class="formLokasiId === option.id ? 'text-[#009B77]' : 'text-slate-700'" x-text="option.nama"></span>
                                                <span class="text-[11px] font-medium text-slate-400 mt-0.5" x-text="option.gedung_nama"></span>
                                            </div>
                                            <i class="ph-bold ph-check text-[#009B77]" x-show="formLokasiId === option.id"></i>
                                        </button>
                                    </template>
                                    <div x-show="optionsLokasi.length > 0 && optionsLokasi.filter(o => o.nama.toLowerCase().includes(search.toLowerCase()) || o.gedung_nama.toLowerCase().includes(search.toLowerCase())).length === 0" class="px-4 py-3 text-sm text-slate-500 font-medium text-center">Tidak ditemukan.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Petugas Inspeksi -->
                    <div x-data="{
                        optionsUser: [
                            @foreach($users as $user)
                            { id: {{ $user->id }}, nama: '{{ addslashes($user->name) }} ({{ addslashes($user->role) }})' },
                            @endforeach
                        ],
                        get selectedUserName() {
                            let u = this.optionsUser.find(x => x.id === this.formUserId);
                            return u ? u.nama : '';
                        }
                    }">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Petugas Inspeksi</label>
                        <div x-data="{ open: false, search: '' }" class="relative">
                            <input type="hidden" name="user_id" :value="formUserId">
                            <i class="ph-bold ph-user-circle absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base transition-colors z-10" :class="open ? 'text-[#009B77]' : ''"></i>
                            <button type="button" @click="open = !open" @click.away="open = false; search = ''" 
                                    class="w-full bg-white border-2 border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm font-medium text-slate-700 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                    :class="open ? 'border-[#009B77] ring-4 ring-[#009B77]/15' : ''">
                                <span x-text="selectedUserName || 'Pilih Petugas'" :class="!selectedUserName ? 'text-slate-400 font-medium' : ''" class="truncate block"></span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180 text-[#009B77]' : ''"></i>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute left-0 z-50 w-full mt-1.5 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 overflow-hidden origin-top max-h-56 flex flex-col">
                                <div class="px-2 pb-1.5 mb-1.5 border-b border-slate-100 flex-shrink-0">
                                    <div class="relative">
                                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" x-model="search" placeholder="Cari petugas..." class="w-full bg-slate-50 border-none rounded-lg py-1.5 pl-9 pr-3 text-xs font-medium text-slate-700 focus:ring-0 placeholder:text-slate-400" @click.stop>
                                    </div>
                                </div>
                                <div class="overflow-y-auto">
                                    <template x-for="option in optionsUser" :key="option.id">
                                        <button type="button" x-show="option.nama.toLowerCase().includes(search.toLowerCase())" @click="formUserId = option.id; open = false; search = ''" class="w-full text-left px-4 py-2 text-sm transition-colors flex items-center justify-between" :class="formUserId === option.id ? 'text-[#009B77] bg-[#009B77]/5 font-bold' : 'text-slate-600 font-medium hover:bg-slate-50'">
                                            <span x-text="option.nama"></span>
                                            <i class="ph-bold ph-check text-[#009B77]" x-show="formUserId === option.id"></i>
                                        </button>
                                    </template>
                                    <div x-show="optionsUser.filter(o => o.nama.toLowerCase().includes(search.toLowerCase())).length === 0" class="px-4 py-3 text-sm text-slate-500 font-medium text-center">Tidak ditemukan.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Catatan Tambahan (Opsional)</label>
                        <div class="relative">
                            <i class="ph-bold ph-note-pencil absolute left-3.5 top-3.5 text-slate-400 text-base transition-colors peer-focus:text-[#009B77]"></i>
                            <textarea name="catatan_tambahan" x-model="formCatatan" rows="2" placeholder="Fokuskan pada area..." class="peer w-full bg-white border-2 border-slate-200 rounded-xl py-2.5 pl-10 pr-3 text-sm font-medium text-slate-700 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none placeholder:text-slate-400 placeholder:font-medium resize-none"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showModalEditJadwal = false" class="px-5 py-2 rounded-xl font-bold text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" 
                            :disabled="!isFormValid"
                            :class="isFormValid ? 'bg-[#009B77] hover:bg-[#008264] text-white shadow-sm' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                            class="font-bold py-2.5 px-6 rounded-xl transition-all text-sm flex items-center justify-center gap-2">
                        <i class="ph-bold ph-floppy-disk text-lg"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>
@endsection
