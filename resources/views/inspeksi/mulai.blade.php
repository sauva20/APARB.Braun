@extends('layouts.app')

@section('title', 'Mulai Inspeksi - APAR Monitoring System')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto" x-data="{
    statusAkhir: 'layak'
}">
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Left: Page Context -->
        <div class="flex items-center gap-3">
            <a href="/inspection-schedule" class="w-10 h-10 rounded-xl bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-slate-500 hover:text-[#009B77] hover:border-[#009B77] transition-all" title="Kembali">
                <i class="ph-bold ph-arrow-left text-xl"></i>
            </a>
            <div>
                <h2 class="text-lg font-extrabold text-slate-800 leading-tight">Formulir Inspeksi APAR</h2>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">Catat hasil pengecekan fisik dan fungsi komponen APAR</p>
            </div>
        </div>
    </div>

    <!-- Info Card -->
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-slate-200/60 overflow-hidden p-6 flex flex-col md:flex-row md:items-center gap-6 justify-between relative">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#009B77]/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="flex items-center gap-5 relative z-10">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#009B77] to-teal-800 text-white flex items-center justify-center shadow-lg shadow-[#009B77]/30 flex-shrink-0 border border-white/20">
                <i class="ph-fill ph-fire-extinguisher text-4xl drop-shadow-md"></i>
            </div>
            <div>
                <h3 class="font-black text-slate-800 text-2xl tracking-tight mb-1">{{ $apar->kode ?? 'APAR-XXX' }}</h3>
                <p class="text-sm font-semibold text-slate-500 flex items-center gap-1.5"><i class="ph-fill ph-map-pin text-[#009B77]"></i> {{ $apar->lokasi->nama ?? '-' }}</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-4 relative z-10">
            <div class="px-5 py-3 bg-slate-50/80 rounded-xl border border-slate-100 flex flex-col items-center min-w-[100px] hover:bg-white hover:shadow-sm transition-all cursor-default">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Jenis</span>
                <span class="text-sm font-bold text-[#009B77]">{{ $apar->jenis->nama ?? '-' }}</span>
            </div>
            <div class="px-5 py-3 bg-slate-50/80 rounded-xl border border-slate-100 flex flex-col items-center min-w-[100px] hover:bg-white hover:shadow-sm transition-all cursor-default">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kapasitas</span>
                <span class="text-sm font-bold text-[#009B77]">{{ $apar->kapasitas->ukuran ?? '-' }}</span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-200 font-bold text-sm flex items-center gap-3">
            <i class="ph-fill ph-warning-circle text-xl"></i>
            Terdapat beberapa kesalahan. Mohon lengkapi semua isian (semua pertanyaan wajib dijawab).
        </div>
    @endif

    <!-- Checklist Section -->
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-slate-200/60 overflow-hidden">
        <form action="{{ route('inspeksi.store', $apar->id ?? 0) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="p-8">
                <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-3 border-b border-slate-100 pb-5">
                    <div class="w-10 h-10 rounded-xl bg-[#009B77]/10 flex items-center justify-center text-[#009B77]">
                        <i class="ph-bold ph-list-checks text-xl"></i>
                    </div>
                    <div>
                        <span class="block text-lg">Checklist Pemeriksaan</span>
                        <span class="block text-xs text-slate-500 font-medium mt-0.5">Jawab setiap poin dengan kondisi aktual APAR di lapangan.</span>
                    </div>
                </h4>
                
                <div class="space-y-4">
                    @foreach($pertanyaan as $index => $tanya)
                    <div x-data="{ jawaban: '{{ old('checklist.'.$index.'.jawaban') }}' }" 
                         class="group flex flex-col md:flex-row gap-5 p-5 rounded-2xl border-2 transition-all duration-300 hover:shadow-md" 
                         :class="jawaban === 'ya' ? 'bg-teal-50/50 border-teal-200/60' : (jawaban === 'tidak' ? 'bg-red-50/80 border-red-200' : 'bg-white border-slate-100 hover:border-slate-200')">
                        
                        <!-- Number & Question -->
                        <div class="flex-1 flex gap-4">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm flex-shrink-0 transition-colors"
                                 :class="jawaban === 'ya' ? 'bg-[#009B77] text-white' : (jawaban === 'tidak' ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-400 group-hover:bg-[#009B77] group-hover:text-white')">
                                {{ $index + 1 }}
                            </div>
                            <p class="font-bold text-slate-700 text-sm leading-relaxed pt-1">{{ $tanya }}</p>
                        </div>
                        
                        <!-- Toggle Ya / Tidak -->
                        <div class="flex flex-col gap-3 min-w-[240px]">
                            <div class="flex bg-slate-100/80 rounded-xl p-1.5 border border-slate-200 shadow-inner self-start w-full sm:w-auto">
                                <input type="hidden" name="checklist[{{ $index }}][jawaban]" x-model="jawaban">
                                <button type="button" @click="jawaban = 'ya'" 
                                        class="flex-1 px-6 py-2 rounded-lg text-xs font-bold transition-all duration-300 flex items-center justify-center gap-1.5" 
                                        :class="jawaban === 'ya' ? 'bg-[#009B77] text-white shadow-md scale-[1.02]' : 'text-slate-500 hover:bg-white hover:text-slate-700 hover:shadow-sm'">
                                    <i class="ph-bold ph-check text-sm"></i> Ya
                                </button>
                                <button type="button" @click="jawaban = 'tidak'" 
                                        class="flex-1 px-6 py-2 rounded-lg text-xs font-bold transition-all duration-300 flex items-center justify-center gap-1.5" 
                                        :class="jawaban === 'tidak' ? 'bg-red-500 text-white shadow-md scale-[1.02]' : 'text-slate-500 hover:bg-white hover:text-slate-700 hover:shadow-sm'">
                                    <i class="ph-bold ph-x text-sm"></i> Tidak
                                </button>
                            </div>
                            
                            <!-- Input Keterangan -->
                            <div x-show="jawaban === 'tidak'" x-collapse>
                                <div class="relative">
                                    <i class="ph-bold ph-warning-circle absolute left-3 top-2.5 text-red-400 text-lg"></i>
                                    <input type="text" name="checklist[{{ $index }}][keterangan]" value="{{ old('checklist.'.$index.'.keterangan') }}" 
                                           placeholder="Tulis keterangan kendala..." 
                                           class="w-full text-sm py-2.5 pl-10 pr-4 border-2 border-red-200 bg-white rounded-xl focus:border-red-400 focus:ring-4 focus:ring-red-100 outline-none transition-all placeholder:text-red-300 text-red-700 font-medium shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="p-8 border-t border-slate-100 bg-slate-50/50">
                <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="ph-bold ph-note text-[#009B77] text-xl"></i> Kesimpulan & Dokumentasi
                </h4>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status Akhir -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Keseluruhan</label>
                            <div x-data="{ open: false, options: { 'layak': 'Layak Pakai (Good Condition)', 'perbaikan': 'Perlu Perbaikan (Needs Repair)', 'isi_ulang': 'Perlu Isi Ulang (Needs Refill)', 'rusak': 'Rusak Total / Afkir' } }" class="relative">
                                <input type="hidden" name="status" :value="statusAkhir">
                                <i class="ph-bold ph-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg transition-colors z-10" :class="statusAkhir === 'layak' ? 'text-[#009B77]' : 'text-red-500'"></i>
                                <button type="button" @click="open = !open" @click.away="open = false" 
                                        class="w-full bg-white border-2 border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm font-bold text-slate-800 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                        :class="open ? 'border-[#009B77] ring-4 ring-[#009B77]/15' : ''">
                                    <span x-text="options[statusAkhir]" class="truncate block"></span>
                                    <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180 text-[#009B77]' : ''"></i>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     style="display: none;" 
                                     class="absolute left-0 bottom-full mb-2 z-50 w-full bg-white rounded-xl shadow-[0_-10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 overflow-hidden origin-bottom">
                                    <template x-for="(label, value) in options" :key="value">
                                        <button type="button" @click="statusAkhir = value; open = false" class="w-full text-left px-4 py-2.5 text-sm font-bold transition-colors flex items-center justify-between" :class="statusAkhir === value ? 'text-[#009B77] bg-[#009B77]/5' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                            <span x-text="label"></span>
                                            <i class="ph-bold ph-check text-[#009B77]" x-show="statusAkhir === value"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Foto -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto Kondisi (Opsional)</label>
                            <div class="relative h-[48px]" x-data="{ fileName: '' }">
                                <input type="file" id="foto" name="foto" class="hidden" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" accept="image/*">
                                <label for="foto" class="flex items-center justify-center gap-2 w-full h-full bg-white border-2 border-dashed border-slate-300 hover:border-[#009B77] hover:bg-[#009B77]/5 rounded-xl text-sm font-bold text-slate-500 hover:text-[#009B77] transition-all cursor-pointer px-4 text-center overflow-hidden">
                                    <i class="ph-bold ph-camera text-lg flex-shrink-0" x-show="!fileName"></i> 
                                    <i class="ph-bold ph-check-circle text-lg text-[#009B77] flex-shrink-0" x-show="fileName"></i>
                                    <span x-text="fileName ? fileName : 'Ambil Foto / Unggah'" class="truncate"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Catatan Tambahan (Opsional)</label>
                        <div class="relative">
                            <i class="ph-bold ph-note-pencil absolute left-4 top-4 text-slate-400 text-lg"></i>
                            <textarea rows="3" name="catatan_tambahan" placeholder="Tuliskan catatan observasi lainnya secara umum..."
                                      class="w-full bg-white border-2 border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm font-bold text-slate-800 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none placeholder:text-slate-400 placeholder:font-medium resize-none">{{ old('catatan_tambahan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100 flex items-center justify-end gap-3 bg-white">
                <a href="/inspection-schedule" class="px-6 py-2.5 rounded-xl font-bold text-slate-600 hover:text-slate-900 bg-white border-2 border-slate-200 hover:border-slate-300 transition-all text-sm">
                    Batal
                </a>
                <button type="submit" class="btn-smooth-ring bg-[#009B77] hover:bg-[#008264] text-white font-bold py-2.5 px-8 rounded-xl shadow-[0_4px_12px_rgba(0,155,119,0.25)] transition-all flex items-center gap-2 text-sm">
                    <i class="ph-bold ph-check-circle text-lg"></i>
                    Selesaikan Inspeksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
