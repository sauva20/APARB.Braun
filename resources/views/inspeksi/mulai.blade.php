<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Mulai Inspeksi - PFE Monitoring Control System</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Setup Rotis Sans Serif */
        @font-face {
            font-family: 'Rotis Sans Serif';
            src: url('/fonts/RotisSansSerif.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Rotis Sans Serif';
            src: url('/fonts/RotisSansSerif-Bold.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
            font-display: swap;
        }

        body {
            font-family: 'Rotis Sans Serif', sans-serif;
            background-color: #F0F0F0;
            color: #1A1A1A;
            -webkit-tap-highlight-color: transparent;
        }
        
        [x-cloak] { display: none !important; }
    </style>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#F0F0F0] text-[#1A1A1A] relative min-h-screen w-screen overflow-x-hidden flex flex-col pb-24">

    <!-- Decorative Background Elements -->
    <div class="fixed top-[-10%] left-[-10%] w-[60%] h-[40%] bg-[#009B77] rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none"></div>
    <div class="fixed bottom-10 right-[-10%] w-[50%] h-[40%] bg-[#8A4B9F] rounded-full mix-blend-multiply filter blur-[100px] opacity-15 pointer-events-none"></div>

    <div class="w-full max-w-xl mx-auto relative z-10 flex flex-col p-4 sm:p-6 space-y-6" x-data="{
    statusAkhir: 'layak',
    showModalEditApar: false,
    editApar: {
        id: {{ $apar->id }},
        kode: '{{ addslashes($apar->kode) }}',
        nomor_apar: '{{ addslashes($apar->kode) }}'.match(/\d+$/) ? '{{ addslashes($apar->kode) }}'.match(/\d+$/)[0] : '',
        gedung_id: '{{ $apar->lokasi->gedung_id ?? '' }}',
        lokasi: '{{ addslashes($apar->lokasi->nama ?? '') }}',
        jenis_id: '{{ $apar->jenis_id }}',
        kapasitas_id: '{{ $apar->kapasitas_id }}',
        vendor: '{{ addslashes($apar->vendor) }}',
        tgl_kedaluwarsa: '{{ $apar->tgl_kedaluwarsa ? $apar->tgl_kedaluwarsa->format('Y-m-d') : '' }}'
    }
}">
    <!-- Header Area -->
    <div class="flex items-center gap-2.5">
        <a href="{{ route('scan.apar', $apar->kode) }}" class="w-8 h-8 rounded-lg bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-slate-500 hover:text-[#009B77] hover:border-[#009B77] transition-all" title="Kembali">
            <i class="ph-bold ph-arrow-left text-lg"></i>
        </a>
        <div>
            <h2 class="text-base font-extrabold text-slate-800 leading-tight">Formulir Inspeksi APAR</h2>
            <p class="text-[10px] font-semibold text-slate-500 mt-0.5">Catat hasil pengecekan fisik dan fungsi komponen APAR</p>
        </div>
    </div>

    <!-- Info Card -->
    <div class="bg-white rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-slate-200/60 overflow-hidden p-4 flex flex-col md:flex-row md:items-center gap-4 justify-between relative">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-[#009B77]/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="flex items-center gap-4 relative z-10">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#009B77] to-teal-800 text-white flex items-center justify-center shadow-md shadow-[#009B77]/20 flex-shrink-0 border border-white/20">
                <i class="ph-fill ph-fire-extinguisher text-2xl drop-shadow-md"></i>
            </div>
            <div>
                <h3 class="font-black text-slate-800 text-lg tracking-tight mb-0.5">{{ $apar->kode ?? 'APAR-XXX' }}</h3>
                <p class="text-xs font-semibold text-slate-500 flex items-center gap-1"><i class="ph-fill ph-map-pin text-[#009B77]"></i> {{ $apar->lokasi->nama ?? 'n/a' }}</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-3 relative z-10">
            <div class="px-3 py-2 bg-slate-50/80 rounded-lg border border-slate-100 flex flex-col items-center min-w-[80px] hover:bg-white hover:shadow-sm transition-all cursor-default">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Jenis</span>
                <span class="text-xs font-bold text-[#009B77]">{{ $apar->jenis->nama ?? 'n/a' }}</span>
            </div>
            <div class="px-3 py-2 bg-slate-50/80 rounded-lg border border-slate-100 flex flex-col items-center min-w-[80px] hover:bg-white hover:shadow-sm transition-all cursor-default">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Kapasitas</span>
                <span class="text-xs font-bold text-[#009B77]">{{ $apar->kapasitas->ukuran ?? 'n/a' }}</span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 p-4 rounded-xl border border-red-200">
            <div class="flex items-center gap-3 text-red-600 font-bold text-sm mb-2">
                <i class="ph-fill ph-warning-circle text-xl"></i>
                Terdapat beberapa kesalahan:
            </div>
            <ul class="list-disc list-inside text-xs font-semibold text-red-500 ml-1 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Checklist Section -->
    <div class="bg-white rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-slate-200/60 overflow-hidden">
        <form action="{{ route('inspeksi.store', $apar->id ?? 0) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="p-4 sm:p-5">
                <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2.5 border-b border-slate-100 pb-3">
                    <div class="w-8 h-8 rounded-lg bg-[#009B77]/10 flex items-center justify-center text-[#009B77]">
                        <i class="ph-bold ph-list-checks text-lg"></i>
                    </div>
                    <div>
                        <span class="block text-base">Checklist Pemeriksaan</span>
                        <span class="block text-[10px] text-slate-500 font-medium mt-0.5">Jawab setiap poin dengan kondisi aktual APAR di lapangan.</span>
                    </div>
                </h4>
                
                <div class="space-y-3">
                    @foreach($pertanyaan as $index => $tanya)
                    <div x-data="{ jawaban: '{{ old('checklist.'.$index.'.jawaban', 'ya') }}' }" 
                         class="group flex flex-col sm:flex-row gap-3 p-3 rounded-xl border-2 transition-all duration-300 hover:shadow-sm" 
                         :class="jawaban === 'ya' ? 'bg-teal-50/50 border-teal-200/60' : (jawaban === 'tidak' ? 'bg-red-50/80 border-red-200' : 'bg-white border-slate-100 hover:border-slate-200')">
                         
                        <!-- Number & Question -->
                        <div class="flex-1 flex gap-3">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-black text-[10px] flex-shrink-0 transition-colors"
                                 :class="jawaban === 'ya' ? 'bg-[#009B77] text-white' : (jawaban === 'tidak' ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-400 group-hover:bg-[#009B77] group-hover:text-white')">
                                {{ $index + 1 }}
                            </div>
                            <p class="font-bold text-slate-700 text-xs leading-relaxed pt-0.5">{{ $tanya }}</p>
                        </div>
                        
                        <!-- Toggle Ya / Tidak -->
                        <div class="flex flex-col gap-2 min-w-[180px]">
                            <div class="flex bg-slate-100/80 rounded-lg p-1 border border-slate-200 shadow-inner self-start w-full sm:w-auto">
                                <input type="hidden" name="checklist[{{ $index }}][jawaban]" x-model="jawaban">
                                <button type="button" @click="jawaban = 'ya'" 
                                        class="flex-1 px-3 py-1.5 rounded text-[10px] font-bold transition-all duration-300 flex items-center justify-center gap-1.5" 
                                        :class="jawaban === 'ya' ? 'bg-[#009B77] text-white shadow-sm scale-[1.02]' : 'text-slate-500 hover:bg-white hover:text-slate-700 hover:shadow-sm'">
                                    <i class="ph-bold ph-check text-[10px]"></i> Ya
                                </button>
                                <button type="button" @click="jawaban = 'tidak'" 
                                        class="flex-1 px-3 py-1.5 rounded text-[10px] font-bold transition-all duration-300 flex items-center justify-center gap-1.5" 
                                        :class="jawaban === 'tidak' ? 'bg-red-500 text-white shadow-sm scale-[1.02]' : 'text-slate-500 hover:bg-white hover:text-slate-700 hover:shadow-sm'">
                                    <i class="ph-bold ph-x text-[10px]"></i> Tidak
                                </button>
                            </div>
                            
                            <!-- Input Keterangan -->
                            <div x-show="jawaban === 'tidak'" x-collapse>
                                <div class="relative">
                                    <i class="ph-bold ph-warning-circle absolute left-2.5 top-2.5 text-red-400 text-base"></i>
                                    <input type="text" name="checklist[{{ $index }}][keterangan]" value="{{ old('checklist.'.$index.'.keterangan') }}" 
                                           placeholder="Tulis keterangan..." 
                                           class="w-full text-[11px] py-2 pl-8 pr-3 border-2 border-red-200 bg-white rounded-lg focus:border-red-400 focus:ring-2 focus:ring-red-100 outline-none transition-all placeholder:text-red-300 text-red-700 font-medium shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50/50">
                <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="ph-bold ph-note text-[#009B77] text-lg"></i> Kesimpulan & Dokumentasi
                </h4>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status Akhir -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Keseluruhan</label>
                            <div x-data="{ open: false, statusAkhir: '{{ old('status', 'layak') }}', options: { 'layak': 'Layak Pakai (Good Condition)', 'perbaikan': 'Perlu Perbaikan (Needs Repair)', 'isi_ulang': 'Perlu Isi Ulang (Needs Refill)', 'rusak': 'Rusak Total / Afkir' } }" class="relative">
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
                        <!-- Qty & Tgl Kedaluwarsa -->
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Qty -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Qty</label>
                                <div class="relative">
                                    <i class="ph-bold ph-hash absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                                    <input type="number" name="qty" min="1" value="{{ old('qty', $apar->qty ?? 1) }}" required
                                           class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-2.5 pl-12 pr-4 text-sm font-bold text-slate-700 focus:bg-white focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none">
                                </div>
                            </div>
                            
                            <!-- Tgl Kedaluwarsa -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Expired</label>
                                <div class="relative">
                                    <input type="date" name="tgl_kedaluwarsa" value="{{ old('tgl_kedaluwarsa', $apar->tgl_kedaluwarsa ? $apar->tgl_kedaluwarsa->format('Y-m-d') : '') }}" required
                                           class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-700 focus:bg-white focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none appearance-none">
                                </div>
                            </div>
                        </div>

                        <!-- Upload Foto -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto Kondisi</label>
                            <div class="relative w-full rounded-xl overflow-hidden bg-slate-100 border-2 border-dashed border-slate-300 hover:border-[#009B77] transition-all aspect-video flex items-center justify-center group" x-data="{ fileName: '', photoPreview: null }">
                                <input type="hidden" name="foto_base64" :value="photoPreview">
                                <input type="file" id="foto" class="hidden" accept="image/*" capture="environment"
                                       @change="
                                           const file = $event.target.files[0];
                                           if(!file) { photoPreview = null; fileName = ''; return; }
                                           fileName = file.name;
                                           const reader = new FileReader();
                                           reader.onload = (e) => {
                                               const img = new Image();
                                               img.onload = () => {
                                                   const canvas = document.createElement('canvas');
                                                   let width = img.width;
                                                   let height = img.height;
                                                   const MAX_WIDTH = 1280;
                                                   if (width > MAX_WIDTH) {
                                                       height = Math.round(height *= MAX_WIDTH / width);
                                                       width = MAX_WIDTH;
                                                   }
                                                   canvas.width = width;
                                                   canvas.height = height;
                                                   const ctx = canvas.getContext('2d');
                                                   ctx.drawImage(img, 0, 0, width, height);
                                                   photoPreview = canvas.toDataURL('image/jpeg', 0.7);
                                               };
                                               img.src = e.target.result;
                                           };
                                           reader.readAsDataURL(file);
                                       ">
                                
                                <label for="foto" class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-2 cursor-pointer transition-all" x-show="!photoPreview">
                                    <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-[#009B77] group-hover:scale-110 transition-all">
                                        <i class="ph-bold ph-camera text-xl"></i>
                                    </div>
                                    <span class="text-sm font-bold text-slate-500 group-hover:text-[#009B77]">Ambil Foto</span>
                                    <span class="text-[10px] font-bold text-red-500 bg-red-50 px-2 py-1 border border-red-200 rounded-md uppercase tracking-widest mt-1">AMBIL FOTO SECARA LANDSCAPE</span>
                                </label>

                                <template x-if="photoPreview">
                                    <div class="absolute inset-0 w-full h-full">
                                        <img :src="photoPreview" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                                        <label for="foto" class="absolute bottom-3 right-3 bg-white/90 hover:bg-white backdrop-blur px-3 py-1.5 rounded-lg text-xs font-bold text-slate-700 shadow-sm cursor-pointer transition-colors flex items-center gap-1.5 z-20">
                                            <i class="ph-bold ph-arrows-clockwise"></i> Ganti Foto
                                        </label>
                                    </div>
                                </template>
                            </div>
                            @error('foto_base64')
                                <p class="text-xs text-red-500 font-bold mt-2"><i class="ph-bold ph-warning-circle mr-1"></i>{{ $message }}</p>
                            @enderror
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

            <div class="p-4 sm:p-5 border-t border-slate-100 flex items-center justify-end gap-2 bg-white sticky bottom-0 z-10 shadow-[0_-5px_15px_rgba(0,0,0,0.02)]">
                <a href="{{ route('scan.apar', $apar->kode) }}" class="px-5 py-2 rounded-lg font-bold text-slate-600 hover:text-slate-900 bg-white border-2 border-slate-200 hover:border-slate-300 transition-all text-xs">
                    Batal
                </a>
                <button type="submit" class="btn-smooth-ring bg-[#009B77] hover:bg-[#008264] text-white font-bold py-2 px-6 rounded-lg shadow-sm shadow-[#009B77]/25 transition-all flex items-center gap-1.5 text-xs">
                    <i class="ph-bold ph-check-circle text-base"></i>
                    Selesaikan Inspeksi
                </button>
            </div>
        </form>
    </div>

    <!-- Modal Edit Data APAR (Mobile Friendly) -->
    <div x-show="showModalEditApar" class="fixed inset-0 z-[100] flex flex-col bg-slate-50" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-full"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-full">
         
        <!-- Header Modal -->
        <div class="bg-white border-b border-slate-200 p-4 flex items-center gap-3 sticky top-0 z-10 shadow-sm">
            <button type="button" @click="showModalEditApar = false" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-100 transition-colors">
                <i class="ph-bold ph-x text-xl"></i>
            </button>
            <h2 class="text-lg font-bold text-slate-800">Update Data APAR</h2>
        </div>

        <!-- Form Content -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-slate-50">
            <form :action="`/master-data/apar/${editApar.id}`" method="POST" id="form-edit-apar-mobile">
                @csrf
                @method('PUT')
                
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 space-y-5" x-data="{
                        lokasiOptions: [
                            @foreach($gedungs->pluck('lokasi')->flatten() as $lok)
                            { id: '{{ $lok->id }}', nama: '{{ addslashes($lok->nama) }}', gedung_id: '{{ $lok->gedung_id }}' },
                            @endforeach
                        ],
                        get filteredLokasi() {
                            if (!editApar.gedung_id) return [];
                            return this.lokasiOptions.filter(l => l.gedung_id == editApar.gedung_id);
                        }
                    }">
                    
                    <!-- Nomor APAR -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor APAR <span class="text-[10px] text-slate-400 font-medium normal-case">(Otomatis)</span></label>
                        <div class="relative">
                            <i class="ph-bold ph-hash absolute left-3.5 top-1/2 -translate-y-1/2 text-amber-500 text-lg"></i>
                            <input type="number" name="nomor_apar" placeholder="Masukkan nomor urut..." required x-model="editApar.nomor_apar" class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium text-slate-700 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 transition-all outline-none">
                        </div>
                    </div>

                    <!-- Gedung -->
                    <div x-data="{
                        open: false,
                        search: '',
                        options: [
                            @foreach($gedungs as $gedung)
                            { id: '{{ $gedung->id }}', name: '{{ addslashes($gedung->nama) }}' },
                            @endforeach
                        ],
                        get selectedName() {
                            let sel = this.options.find(o => o.id == editApar.gedung_id);
                            return sel ? sel.name : 'Pilih Gedung';
                        },
                        get filteredOptions() {
                            if (this.search === '') return this.options;
                            return this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
                        }
                    }">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Gedung</label>
                        <div class="relative">
                            <input type="hidden" name="gedung_id" :value="editApar.gedung_id" required>
                            <i class="ph-bold ph-buildings absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg transition-colors z-10" :class="open ? 'text-amber-500' : 'peer-focus:text-amber-500'"></i>
                            <button type="button" @click="open = !open" @click.away="open = false" 
                                    class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium text-slate-700 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                    :class="open ? 'bg-white border-amber-500 ring-4 ring-amber-500/15' : ''">
                                <span x-text="selectedName" :class="editApar.gedung_id ? 'font-bold text-slate-700' : 'text-slate-400 font-medium'" class="truncate block"></span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180 text-amber-500' : ''"></i>
                            </button>
                            <div x-show="open" x-cloak class="absolute left-0 z-50 w-full mt-2 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 max-h-60 overflow-y-auto origin-top">
                                <div class="sticky top-0 bg-white p-2 border-b border-slate-100 z-10">
                                    <div class="relative">
                                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" x-model="search" placeholder="Cari..." class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 pl-9 pr-3 text-sm font-medium text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all" @click.stop @keydown.enter.prevent>
                                    </div>
                                </div>
                                <div class="py-1">
                                    <template x-for="option in filteredOptions" :key="option.id">
                                    <button type="button" @click="editApar.gedung_id = option.id; open = false" class="w-full text-left px-4 py-3 text-sm font-medium transition-colors flex items-center justify-between" :class="editApar.gedung_id == option.id ? 'text-amber-500 bg-amber-50' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                        <span x-text="option.name" class="font-bold"></span>
                                    </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div x-data="{
                        open: false,
                        search: '',
                        get filtered() {
                            if (this.search === '') return this.filteredLokasi;
                            return this.filteredLokasi.filter(l => l.nama.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        init() {
                            this.search = editApar.lokasi || '';
                            this.$watch('editApar.lokasi', val => this.search = val || '');
                        }
                    }">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Lokasi <span class="text-[10px] text-slate-400 font-medium normal-case">(Ketik)</span></label>
                        <div class="relative">
                            <input type="hidden" name="lokasi" :value="editApar.lokasi">
                            <i class="ph-bold ph-map-pin absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg transition-colors z-10" :class="open ? 'text-amber-500' : 'peer-focus:text-amber-500'"></i>
                            <input type="text" x-model="editApar.lokasi" @focus="open = true" @click.away="open = false" placeholder="Contoh: Corridor" required 
                                   class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium text-slate-700 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 transition-all outline-none"
                                   :class="open ? 'bg-white border-amber-500 ring-4 ring-amber-500/15' : ''">
                            <div x-show="open && filteredLokasi.length > 0" x-cloak class="absolute left-0 z-50 w-full mt-2 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 max-h-60 overflow-y-auto origin-top">
                                <div class="py-1">
                                    <template x-for="lok in filtered" :key="lok.id">
                                    <button type="button" @click="editApar.lokasi = lok.nama; open = false" class="w-full text-left px-4 py-3 text-sm font-medium transition-colors flex items-center justify-between" :class="editApar.lokasi == lok.nama ? 'text-amber-500 bg-amber-50' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                        <span x-text="lok.nama" class="font-bold"></span>
                                    </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis APAR -->
                    <div x-data="{
                        open: false,
                        search: '',
                        options: [
                            @foreach($jenisApars as $jenis)
                            { id: '{{ $jenis->id }}', name: '{{ addslashes($jenis->nama) }}' },
                            @endforeach
                        ],
                        get selectedName() {
                            let sel = this.options.find(o => o.id == editApar.jenis_id);
                            return sel ? sel.name : 'Pilih Jenis';
                        },
                        get filteredOptions() {
                            if (this.search === '') return this.options;
                            return this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
                        }
                    }">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis APAR</label>
                        <div class="relative">
                            <input type="hidden" name="jenis_id" :value="editApar.jenis_id" required>
                            <i class="ph-bold ph-fire-extinguisher absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg transition-colors z-10" :class="open ? 'text-amber-500' : 'peer-focus:text-amber-500'"></i>
                            <button type="button" @click="open = !open" @click.away="open = false" 
                                    class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium text-slate-700 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                    :class="open ? 'bg-white border-amber-500 ring-4 ring-amber-500/15' : ''">
                                <span x-text="selectedName" :class="editApar.jenis_id ? 'font-bold text-slate-700' : 'text-slate-400 font-medium'" class="truncate block"></span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180 text-amber-500' : ''"></i>
                            </button>
                            <div x-show="open" x-cloak class="absolute left-0 z-50 w-full mt-2 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 max-h-60 overflow-y-auto origin-top">
                                <div class="sticky top-0 bg-white p-2 border-b border-slate-100 z-10">
                                    <div class="relative">
                                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" x-model="search" placeholder="Cari..." class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 pl-9 pr-3 text-sm font-medium text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all" @click.stop @keydown.enter.prevent>
                                    </div>
                                </div>
                                <div class="py-1">
                                    <template x-for="option in filteredOptions" :key="option.id">
                                    <button type="button" @click="editApar.jenis_id = option.id; open = false" class="w-full text-left px-4 py-3 text-sm font-medium transition-colors flex items-center justify-between" :class="editApar.jenis_id == option.id ? 'text-amber-500 bg-amber-50' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                        <span x-text="option.name" class="font-bold"></span>
                                    </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kapasitas -->
                    <div x-data="{
                        open: false,
                        search: '',
                        options: [
                            @foreach($kapasitasApars as $kapasitas)
                            { id: '{{ $kapasitas->id }}', name: '{{ addslashes($kapasitas->ukuran) }}' },
                            @endforeach
                        ],
                        get selectedName() {
                            let sel = this.options.find(o => o.id == editApar.kapasitas_id);
                            return sel ? sel.name : 'Pilih Kapasitas';
                        },
                        get filteredOptions() {
                            if (this.search === '') return this.options;
                            return this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
                        }
                    }">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kapasitas</label>
                        <div class="relative">
                            <input type="hidden" name="kapasitas_id" :value="editApar.kapasitas_id" required>
                            <i class="ph-bold ph-scales absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg transition-colors z-10" :class="open ? 'text-amber-500' : 'peer-focus:text-amber-500'"></i>
                            <button type="button" @click="open = !open" @click.away="open = false" 
                                    class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium text-slate-700 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                    :class="open ? 'bg-white border-amber-500 ring-4 ring-amber-500/15' : ''">
                                <span x-text="selectedName" :class="editApar.kapasitas_id ? 'font-bold text-slate-700' : 'text-slate-400 font-medium'" class="truncate block"></span>
                                <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180 text-amber-500' : ''"></i>
                            </button>
                            <div x-show="open" x-cloak class="absolute left-0 z-50 w-full mt-2 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 max-h-60 overflow-y-auto origin-top">
                                <div class="sticky top-0 bg-white p-2 border-b border-slate-100 z-10">
                                    <div class="relative">
                                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" x-model="search" placeholder="Cari..." class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 pl-9 pr-3 text-sm font-medium text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all" @click.stop @keydown.enter.prevent>
                                    </div>
                                </div>
                                <div class="py-1">
                                    <template x-for="option in filteredOptions" :key="option.id">
                                    <button type="button" @click="editApar.kapasitas_id = option.id; open = false" class="w-full text-left px-4 py-3 text-sm font-medium transition-colors flex items-center justify-between" :class="editApar.kapasitas_id == option.id ? 'text-amber-500 bg-amber-50' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                        <span x-text="option.name" class="font-bold"></span>
                                    </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vendor -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Vendor</label>
                        <div class="relative">
                            <i class="ph-bold ph-storefront absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                            <input type="text" name="vendor" placeholder="Nama vendor (Opsional)" x-model="editApar.vendor" class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium text-slate-700 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 transition-all outline-none">
                        </div>
                    </div>

                    <!-- Tanggal Kedaluwarsa -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Kedaluwarsa</label>
                        <div class="relative">
                            <i class="ph-bold ph-calendar-blank absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg z-10"></i>
                            <input type="text" name="tgl_kedaluwarsa" required x-model="editApar.tgl_kedaluwarsa" class="datepicker w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-3 pl-10 pr-10 text-sm font-medium text-slate-700 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 transition-all outline-none appearance-none cursor-pointer">
                            <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Footer Button -->
        <div class="bg-white border-t border-slate-200 p-4 sticky bottom-0 z-10">
            <button type="submit" form="form-edit-apar-mobile" class="w-full py-3.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold text-[15px] shadow-lg shadow-amber-500/30 transition-all flex items-center justify-center gap-2">
                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Perubahan
            </button>
        </div>
    </div>

</body>
</html>
