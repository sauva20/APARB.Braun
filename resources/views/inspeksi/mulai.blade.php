<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ __('Start Inspection') }} - PFE Monitoring Control System</title>
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
    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Flatpickr (Datepicker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .flatpickr-wrapper {
            display: block !important;
            width: 100% !important;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected:hover {
            background: #009B77 !important;
            border-color: #009B77 !important;
        }
    </style>
</head>
<body class="bg-[#F0F0F0] text-[#1A1A1A] relative min-h-screen w-screen overflow-x-hidden flex flex-col pb-24">

    <!-- Decorative Background Elements -->
    <div class="fixed top-[-10%] left-[-10%] w-[60%] h-[40%] bg-[#009B77] rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none"></div>
    <div class="fixed bottom-10 right-[-10%] w-[50%] h-[40%] bg-[#8A4B9F] rounded-full mix-blend-multiply filter blur-[100px] opacity-15 pointer-events-none"></div>

    <div class="w-full max-w-xl mx-auto relative z-10 flex flex-col p-4 sm:p-6 space-y-6" x-data="{
    statusAkhir: $persist('{{ old('status', 'layak') }}').using(sessionStorage).as('inspeksi_status_{{ $apar->id }}'),
    qty: $persist('{{ old('qty', $apar->qty ?? 0) }}').using(sessionStorage).as('inspeksi_qty_{{ $apar->id }}'),
    tgl_kedaluwarsa: $persist('{{ old('tgl_kedaluwarsa', $apar->tgl_kedaluwarsa ? $apar->tgl_kedaluwarsa->format('Y-m-d') : '') }}').using(sessionStorage).as('inspeksi_tgl_{{ $apar->id }}'),
    catatan_tambahan: $persist('{{ addslashes(old('catatan_tambahan', '')) }}').using(sessionStorage).as('inspeksi_catatan_{{ $apar->id }}'),
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
}" x-init="$watch('qty', value => { if (value !== '' && value <= 0) statusAkhir = 'isi_ulang'; }); if (qty !== '' && qty <= 0) statusAkhir = 'isi_ulang';">
    <!-- Header Area -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <a href="{{ $backUrl }}" class="w-8 h-8 rounded-lg bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-slate-500 hover:text-[#009B77] hover:border-[#009B77] transition-all" title="{{ __('Back') }}">
                <i class="ph-bold ph-arrow-left text-lg"></i>
            </a>
            <div>
                <h2 class="text-base font-extrabold text-slate-800 leading-tight">{{ __('PFE Inspection Form') }}</h2>
                <p class="text-[10px] font-semibold text-slate-500 mt-0.5 hidden sm:block">{{ __('Record physical and functional check results of PFE components') }}</p>
            </div>
        </div>
        
        <!-- Language Switcher -->
        <div class="flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-200/60 shadow-sm shrink-0">
            <a href="{{ route('set-locale', 'id') }}" class="text-[10px] font-bold px-2 py-1 rounded {{ app()->getLocale() == 'id' ? 'bg-[#009B77] text-white' : 'bg-transparent text-slate-400 hover:text-slate-600' }} transition-colors">ID</a>
            <a href="{{ route('set-locale', 'en') }}" class="text-[10px] font-bold px-2 py-1 rounded {{ app()->getLocale() == 'en' ? 'bg-[#009B77] text-white' : 'bg-transparent text-slate-400 hover:text-slate-600' }} transition-colors">EN</a>
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
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">{{ __('Type') }}</span>
                <span class="text-xs font-bold text-[#009B77]">{{ $apar->jenis->nama ?? 'n/a' }}</span>
            </div>
            <div class="px-3 py-2 bg-slate-50/80 rounded-lg border border-slate-100 flex flex-col items-center min-w-[80px] hover:bg-white hover:shadow-sm transition-all cursor-default">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">{{ __('Capacity') }}</span>
                <span class="text-xs font-bold text-[#009B77]">{{ $apar->kapasitas->ukuran ?? 'n/a' }}</span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 p-4 rounded-xl border border-red-200">
            <div class="flex items-center gap-3 text-red-600 font-bold text-sm mb-2">
                <i class="ph-fill ph-warning-circle text-xl"></i>
                {{ __('There are some errors:') }}
            </div>
            <ul class="list-disc list-inside text-xs font-semibold text-red-500 ml-1 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Checklist Section -->
    <div class="bg-white rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-slate-200/60 overflow-hidden" x-data="{ step: $persist(1).using(sessionStorage).as('inspeksi_step_{{ $apar->id }}') }">
        <form action="{{ route('inspeksi.store', $apar->id ?? 0) }}" method="POST" enctype="multipart/form-data" 
              @submit.prevent="
                  let foto = $el.querySelector('input[name=\'foto_base64\']').value;
                  if ('{{ request('source') }}' !== 'schedule' && (!foto || foto.trim() === '')) {
                      Swal.fire({
                          icon: 'warning',
                          title: '{{ __('Peringatan') }}',
                          text: '{{ __('Lampirkan form saat inspeksi berlangsung!') }}',
                          confirmButtonColor: '#009B77',
                          confirmButtonText: '{{ __('OK') }}',
                          customClass: {
                              popup: 'rounded-2xl',
                              confirmButton: 'rounded-lg font-bold shadow-sm'
                          }
                      });
                      return;
                  }
                  Object.keys(sessionStorage).forEach(k => { if(k.startsWith('inspeksi_')) sessionStorage.removeItem(k); });
                  $el.submit();
              ">
            <input type="hidden" name="source" value="{{ request('source') }}">
            @csrf
            
            <!-- Step 1: Pemeriksaan Fisik -->
            <div x-show="step === 1">
                <div class="p-4 sm:p-5">
                    <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-[#009B77]/10 flex items-center justify-center text-[#009B77]">
                            <i class="ph-bold ph-magnifying-glass text-lg"></i>
                        </div>
                        <div>
                            <span class="block text-base">{{ __('Pemeriksaan Fisik (Langkah 1 dari 2)') }}</span>
                            <span class="block text-[10px] text-slate-500 font-medium mt-0.5">{{ __('Cek ketersediaan dan kondisi fisik tiap bagian APAR.') }}</span>
                        </div>
                    </h4>
                    
                    <div class="space-y-3">
                        @foreach($pertanyaan as $index => $tanya)
                        @if($index >= 15)
                        
                        @if($index === 15)
                        <div class="group flex flex-col sm:flex-row gap-3 p-3 rounded-xl border-2 transition-all duration-300 hover:shadow-sm" 
                             x-data="{ 
                                 selectedJenis: $persist('{{ old('jenis_id', $apar->jenis_id) }}').using(sessionStorage).as('inspeksi_jenis_{{ $apar->id }}'), 
                                 expectedJenis: '{{ $apar->jenis_id }}',
                                 keterangan: $persist('{{ addslashes(old('checklist.'.$index.'.keterangan', '')) }}').using(sessionStorage).as('inspeksi_ket_{{ $index }}_{{ $apar->id }}')
                             }"
                             :class="selectedJenis == expectedJenis ? 'bg-teal-50/50 border-teal-200/60' : 'bg-red-50/80 border-red-200'">
                            <!-- Number & Question -->
                            <div class="flex-1 flex gap-3">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center font-black text-[10px] flex-shrink-0 transition-colors"
                                     :class="selectedJenis == expectedJenis ? 'bg-[#009B77] text-white' : 'bg-red-500 text-white'">
                                    {{ $index - 14 }}
                                </div>
                                <p class="font-bold text-slate-700 text-xs leading-relaxed pt-0.5">{{ $tanya }}</p>
                            </div>
                            
                            <!-- Toggle Buttons -->
                            <div class="flex flex-col gap-2 min-w-[180px] sm:w-[320px]">
                                <div class="flex flex-wrap bg-slate-100/80 rounded-lg p-1 border border-slate-200 shadow-inner self-start w-full sm:w-auto">
                                    <input type="hidden" name="checklist[{{ $index }}][jawaban]" :value="selectedJenis == expectedJenis ? 'ada' : 'tidak ada'">
                                    <input type="hidden" name="jenis_id" :value="selectedJenis" required>
                                    
                                    @foreach($jenisApars as $jenis)
                                    <button type="button" @click="selectedJenis = '{{ $jenis->id }}'" 
                                            class="flex-1 px-3 py-1.5 rounded text-[10px] font-bold transition-all duration-300 flex items-center justify-center gap-1.5 whitespace-nowrap" 
                                            :class="selectedJenis == '{{ $jenis->id }}' ? (selectedJenis == expectedJenis ? 'bg-[#009B77] text-white shadow-sm scale-[1.02]' : 'bg-red-500 text-white shadow-sm scale-[1.02]') : 'text-slate-500 hover:bg-white hover:text-slate-700 hover:shadow-sm'">
                                        {{ $jenis->nama }}
                                    </button>
                                    @endforeach
                                </div>
                                
                                <!-- Input Keterangan -->
                                <div x-show="selectedJenis !== ''" x-collapse>
                                    <div class="relative">
                                        <i class="ph-bold absolute left-2.5 top-1/2 -translate-y-1/2 text-base" :class="selectedJenis != expectedJenis ? 'ph-warning-circle text-red-400' : 'ph-note-pencil text-slate-400'"></i>
                                        <input type="text" name="checklist[{{ $index }}][keterangan]" x-model="keterangan" 
                                               placeholder="{{ __('Kondisi / Keterangan (Opsional)...') }}" 
                                               class="w-full text-[11px] py-2 pl-8 pr-3 border-2 bg-white rounded-lg focus:ring-2 outline-none transition-all font-medium shadow-sm"
                                               :class="selectedJenis != expectedJenis ? 'border-red-200 focus:border-red-400 focus:ring-red-100 placeholder:text-red-300 text-red-700' : 'border-slate-200 focus:border-[#009B77] focus:ring-[#009B77]/15 placeholder:text-slate-300 text-slate-700'">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($index === 16)
                        <div class="group flex flex-col sm:flex-row gap-3 p-3 rounded-xl border-2 transition-all duration-300 hover:shadow-sm" 
                             x-data="{ 
                                 selectedKapasitas: $persist('{{ old('kapasitas_id', $apar->kapasitas_id) }}').using(sessionStorage).as('inspeksi_kapasitas_{{ $apar->id }}'), 
                                 expectedKapasitas: '{{ $apar->kapasitas_id }}',
                                 keterangan: $persist('{{ addslashes(old('checklist.'.$index.'.keterangan', '')) }}').using(sessionStorage).as('inspeksi_ket_{{ $index }}_{{ $apar->id }}')
                             }"
                             :class="selectedKapasitas == expectedKapasitas ? 'bg-teal-50/50 border-teal-200/60' : 'bg-red-50/80 border-red-200'">
                            <!-- Number & Question -->
                            <div class="flex-1 flex gap-3">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center font-black text-[10px] flex-shrink-0 transition-colors"
                                     :class="selectedKapasitas == expectedKapasitas ? 'bg-[#009B77] text-white' : 'bg-red-500 text-white'">
                                    {{ $index - 14 }}
                                </div>
                                <p class="font-bold text-slate-700 text-xs leading-relaxed pt-0.5">{{ $tanya }}</p>
                            </div>
                            
                            <!-- Toggle Buttons -->
                            <div class="flex flex-col gap-2 min-w-[180px] sm:w-[320px]">
                                <div class="flex flex-wrap bg-slate-100/80 rounded-lg p-1 border border-slate-200 shadow-inner self-start w-full sm:w-auto">
                                    <input type="hidden" name="checklist[{{ $index }}][jawaban]" :value="selectedKapasitas == expectedKapasitas ? 'ada' : 'tidak ada'">
                                    <input type="hidden" name="kapasitas_id" :value="selectedKapasitas" required>
                                    
                                    @foreach($kapasitasApars as $kapasitas)
                                    <button type="button" @click="selectedKapasitas = '{{ $kapasitas->id }}'" 
                                            class="flex-1 px-3 py-1.5 rounded text-[10px] font-bold transition-all duration-300 flex items-center justify-center gap-1.5 whitespace-nowrap" 
                                            :class="selectedKapasitas == '{{ $kapasitas->id }}' ? (selectedKapasitas == expectedKapasitas ? 'bg-[#009B77] text-white shadow-sm scale-[1.02]' : 'bg-red-500 text-white shadow-sm scale-[1.02]') : 'text-slate-500 hover:bg-white hover:text-slate-700 hover:shadow-sm'">
                                        {{ $kapasitas->ukuran }}
                                    </button>
                                    @endforeach
                                </div>
                                
                                <!-- Input Keterangan -->
                                <div x-show="selectedKapasitas !== ''" x-collapse>
                                    <div class="relative">
                                        <i class="ph-bold absolute left-2.5 top-1/2 -translate-y-1/2 text-base" :class="selectedKapasitas != expectedKapasitas ? 'ph-warning-circle text-red-400' : 'ph-note-pencil text-slate-400'"></i>
                                        <input type="text" name="checklist[{{ $index }}][keterangan]" x-model="keterangan" 
                                               placeholder="{{ __('Kondisi / Keterangan (Opsional)...') }}" 
                                               class="w-full text-[11px] py-2 pl-8 pr-3 border-2 bg-white rounded-lg focus:ring-2 outline-none transition-all font-medium shadow-sm"
                                               :class="selectedKapasitas != expectedKapasitas ? 'border-red-200 focus:border-red-400 focus:ring-red-100 placeholder:text-red-300 text-red-700' : 'border-slate-200 focus:border-[#009B77] focus:ring-[#009B77]/15 placeholder:text-slate-300 text-slate-700'">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($index === 17)
                        @php
                            $tglIsiUlangStr = $apar->tgl_isi_ulang ? $apar->tgl_isi_ulang->format('Y-m-d') : '';
                        @endphp
                        <div class="group flex flex-col sm:flex-row gap-3 p-3 rounded-xl border-2 transition-all duration-300 hover:shadow-sm" 
                             x-data="{ 
                                 selectedTgl: $persist('{{ old('tgl_isi_ulang', $tglIsiUlangStr) }}').using(sessionStorage).as('inspeksi_tglisiulang_{{ $apar->id }}'), 
                                 expectedTgl: '{{ $tglIsiUlangStr }}',
                                 keterangan: $persist('{{ addslashes(old('checklist.'.$index.'.keterangan', '')) }}').using(sessionStorage).as('inspeksi_ket_{{ $index }}_{{ $apar->id }}')
                             }"
                             :class="(selectedTgl == expectedTgl && expectedTgl !== '') ? 'bg-teal-50/50 border-teal-200/60' : 'bg-red-50/80 border-red-200'">
                            <!-- Number & Question -->
                            <div class="flex-1 flex gap-3">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center font-black text-[10px] flex-shrink-0 transition-colors"
                                     :class="(selectedTgl == expectedTgl && expectedTgl !== '') ? 'bg-[#009B77] text-white' : 'bg-red-500 text-white'">
                                    {{ $index - 14 }}
                                </div>
                                <p class="font-bold text-slate-700 text-xs leading-relaxed pt-0.5">{{ $tanya }}</p>
                            </div>
                            
                            <!-- Date Input -->
                            <div class="flex flex-col gap-2 min-w-[180px] sm:w-[320px]">
                                <div class="flex flex-wrap bg-slate-100/80 rounded-lg p-1 border border-slate-200 shadow-inner self-start w-full sm:w-auto">
                                    <input type="hidden" name="checklist[{{ $index }}][jawaban]" :value="(selectedTgl == expectedTgl && expectedTgl !== '') ? 'ada' : 'tidak ada'">
                                    <input type="text" name="tgl_isi_ulang" x-model="selectedTgl" required
                                           x-init="flatpickr($el, { dateFormat: 'Y-m-d', disableMobile: 'true', defaultDate: selectedTgl, onChange: (s, d) => { selectedTgl = d; } })"
                                           class="w-full sm:w-auto px-3 py-1.5 rounded text-xs font-bold transition-all duration-300 outline-none shadow-sm focus:ring-2 focus:ring-[#009B77]/20 border-2"
                                           :class="(selectedTgl == expectedTgl && expectedTgl !== '') ? 'bg-[#009B77] text-white border-[#009B77]' : 'bg-white text-slate-700 hover:bg-slate-50 border-slate-200'">
                                </div>
                                
                                <!-- Input Keterangan -->
                                <div x-show="selectedTgl !== ''" x-collapse>
                                    <div class="relative">
                                        <i class="ph-bold absolute left-2.5 top-1/2 -translate-y-1/2 text-base" :class="(selectedTgl != expectedTgl || expectedTgl === '') ? 'ph-warning-circle text-red-400' : 'ph-note-pencil text-slate-400'"></i>
                                        <input type="text" name="checklist[{{ $index }}][keterangan]" x-model="keterangan" 
                                               placeholder="{{ __('Kondisi / Keterangan (Opsional)...') }}" 
                                               class="w-full text-[11px] py-2 pl-8 pr-3 border-2 bg-white rounded-lg focus:ring-2 outline-none transition-all font-medium shadow-sm"
                                               :class="(selectedTgl != expectedTgl || expectedTgl === '') ? 'border-red-200 focus:border-red-400 focus:ring-red-100 placeholder:text-red-300 text-red-700' : 'border-slate-200 focus:border-[#009B77] focus:ring-[#009B77]/15 placeholder:text-slate-300 text-slate-700'">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        @php
                            $expected = ($index === 20) ? 'tidak ada' : 'ada';
                        @endphp
                        <div x-data="{ 
                                jawaban: $persist('{{ old('checklist.'.$index.'.jawaban', $expected) }}').using(sessionStorage).as('inspeksi_jawaban_{{ $index }}_{{ $apar->id }}'), 
                                keterangan: $persist('{{ addslashes(old('checklist.'.$index.'.keterangan', '')) }}').using(sessionStorage).as('inspeksi_ket_{{ $index }}_{{ $apar->id }}'),
                                expected: '{{ $expected }}' 
                             }" 
                             @if($index === 24)
                             x-effect="if ($refs.lokasiInput) {
                                if (jawaban === 'tidak ada' && keterangan.trim().toLowerCase() === '{{ strtolower($apar->lokasi->nama) }}') {
                                    $refs.lokasiInput.setCustomValidity('Lokasi yang dimasukkan sama dengan lokasi saat ini.');
                                } else {
                                    $refs.lokasiInput.setCustomValidity('');
                                }
                             }"
                             @endif
                             class="group flex flex-col sm:flex-row gap-3 p-3 rounded-xl border-2 transition-all duration-300 hover:shadow-sm" 
                             :class="jawaban === expected ? 'bg-teal-50/50 border-teal-200/60' : (jawaban !== '' ? 'bg-red-50/80 border-red-200' : 'bg-white border-slate-100 hover:border-slate-200')">
                             
                            <!-- Number & Question -->
                            <div class="flex-1 flex gap-3">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center font-black text-[10px] flex-shrink-0 transition-colors"
                                     :class="jawaban === expected ? 'bg-[#009B77] text-white' : (jawaban !== '' ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-400 group-hover:bg-[#009B77] group-hover:text-white')">
                                    {{ $index - 14 }}
                                </div>
                                <p class="font-bold text-slate-700 text-xs leading-relaxed pt-0.5">{{ $tanya }}</p>
                            </div>
                            
                            <!-- Toggle Ya / Tidak -->
                            <div class="flex flex-col gap-2 min-w-[180px] sm:w-[320px]">
                                <div class="flex bg-slate-100/80 rounded-lg p-1 border border-slate-200 shadow-inner self-start w-full sm:w-auto">
                                    <input type="hidden" name="checklist[{{ $index }}][jawaban]" x-model="jawaban">
                                    <button type="button" @click="jawaban = 'ada'" 
                                            class="flex-1 px-3 py-1.5 rounded text-[10px] font-bold transition-all duration-300 flex items-center justify-center gap-1.5" 
                                            :class="jawaban === 'ada' ? (expected === 'ada' ? 'bg-[#009B77] text-white shadow-sm scale-[1.02]' : 'bg-red-500 text-white shadow-sm scale-[1.02]') : 'text-slate-500 hover:bg-white hover:text-slate-700 hover:shadow-sm'">
                                        <i class="ph-bold ph-check text-[10px]"></i> {{ $index === 21 ? __('Padat') : ($index === 24 ? __('Sesuai') : __('Ada')) }}
                                    </button>
                                    <button type="button" @click="jawaban = 'tidak ada'" 
                                            class="flex-1 px-3 py-1.5 rounded text-[10px] font-bold transition-all duration-300 flex items-center justify-center gap-1.5" 
                                            :class="jawaban === 'tidak ada' ? (expected === 'tidak ada' ? 'bg-[#009B77] text-white shadow-sm scale-[1.02]' : 'bg-red-500 text-white shadow-sm scale-[1.02]') : 'text-slate-500 hover:bg-white hover:text-slate-700 hover:shadow-sm'">
                                        <i class="ph-bold ph-x text-[10px]"></i> {{ $index === 21 ? __('Tidak') : ($index === 24 ? __('Tidak Sesuai') : __('Tidak Ada')) }}
                                    </button>
                                </div>
                                
                                <!-- Input Keterangan -->
                                <div x-show="jawaban !== ''" x-collapse>
                                    <div class="relative">
                                        <i class="ph-bold absolute left-2.5 top-1/2 -translate-y-1/2 text-base" :class="jawaban !== expected ? 'ph-warning-circle text-red-400' : 'ph-note-pencil text-slate-400'"></i>
                                        <input type="text" name="checklist[{{ $index }}][keterangan]" x-model="keterangan" 
                                               @if($index === 24) x-ref="lokasiInput" @endif 
                                               placeholder="{{ $index === 24 ? __('Masukkan lokasi yang tepat...') : __('Kondisi / Keterangan (Opsional)...') }}" 
                                               :required="jawaban === 'tidak ada' && {{ $index === 24 ? 'true' : 'false' }}"
                                               class="w-full text-[11px] py-2 pl-8 pr-3 border-2 bg-white rounded-lg focus:ring-2 outline-none transition-all font-medium shadow-sm"
                                               :class="jawaban !== expected ? 'border-red-200 focus:border-red-400 focus:ring-red-100 placeholder:text-red-300 text-red-700' : 'border-slate-200 focus:border-[#009B77] focus:ring-[#009B77]/15 placeholder:text-slate-300 text-slate-700'">
                                    </div>
                                    @if($index === 24)
                                    <div x-show="jawaban === 'tidak ada' && keterangan.trim().toLowerCase() === '{{ strtolower($apar->lokasi->nama) }}'" x-collapse>
                                        <div class="mt-1.5 flex items-start gap-1.5 text-amber-600 bg-amber-50 p-2 rounded border border-amber-200">
                                            <i class="ph-bold ph-warning text-sm mt-0.5"></i>
                                            <p class="text-[10px] font-medium leading-tight">{{ __('Lokasi yang Anda masukkan sama dengan lokasi saat ini. Masukkan lokasi yang berbeda jika memang tidak sesuai.') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif
                        @endforeach
                    </div>
                </div>

                <!-- Lanjut Button Step 1 -->
                <div class="p-4 sm:p-5 border-t border-slate-100 flex items-center justify-end bg-white sticky bottom-0 z-10 shadow-[0_-5px_15px_rgba(0,0,0,0.02)]">
                    <button type="button" @click="
                        let isValid = true;
                        let inputs = $el.closest('div[x-show=\'step === 1\']').querySelectorAll('input, select, textarea');
                        for (let input of inputs) {
                            if (!input.checkValidity()) {
                                input.reportValidity();
                                isValid = false;
                                break;
                            }
                        }
                        if (isValid) step = 2;
                    " class="btn-smooth-ring bg-[#009B77] hover:bg-[#008264] text-white font-bold py-2 px-6 rounded-lg shadow-sm shadow-[#009B77]/25 transition-all flex items-center gap-1.5 text-xs">
                        {{ __('Lanjut (Fungsi & Kelayakan)') }} <i class="ph-bold ph-arrow-right text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: Pemeriksaan Fungsi & Kesimpulan -->
            <div x-show="step === 2" style="display: none;">
                <div class="p-4 sm:p-5">
                    <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2.5 border-b border-slate-100 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-[#009B77]/10 flex items-center justify-center text-[#009B77]">
                            <i class="ph-bold ph-list-checks text-lg"></i>
                        </div>
                        <div>
                            <span class="block text-base">{{ __('Pemeriksaan Fungsi (Langkah 2 dari 2)') }}</span>
                            <span class="block text-[10px] text-slate-500 font-medium mt-0.5">{{ __('Jawab setiap poin sesuai kondisi aktual di lapangan.') }}</span>
                        </div>
                    </h4>
                    
                    <div class="space-y-3">
                        @foreach($pertanyaan as $index => $tanya)
                        @if($index < 15)
                        <div x-data="{ 
                                jawaban: $persist('{{ old('checklist.'.$index.'.jawaban', 'ya') }}').using(sessionStorage).as('inspeksi_jawaban_{{ $index }}_{{ $apar->id }}'),
                                keterangan: $persist('{{ addslashes(old('checklist.'.$index.'.keterangan', '')) }}').using(sessionStorage).as('inspeksi_ket_{{ $index }}_{{ $apar->id }}')
                             }" 
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
                                        <i class="ph-bold ph-check text-[10px]"></i> {{ __('Yes') }}
                                    </button>
                                    <button type="button" @click="jawaban = 'tidak'" 
                                            class="flex-1 px-3 py-1.5 rounded text-[10px] font-bold transition-all duration-300 flex items-center justify-center gap-1.5" 
                                            :class="jawaban === 'tidak' ? 'bg-red-500 text-white shadow-sm scale-[1.02]' : 'text-slate-500 hover:bg-white hover:text-slate-700 hover:shadow-sm'">
                                        <i class="ph-bold ph-x text-[10px]"></i> {{ __('No') }}
                                    </button>
                                </div>
                                
                                <!-- Input Keterangan -->
                                <div x-show="jawaban !== ''" x-collapse>
                                    <div class="relative">
                                        <i class="ph-bold absolute left-2.5 top-1/2 -translate-y-1/2 text-base" :class="jawaban === 'tidak' ? 'ph-warning-circle text-red-400' : 'ph-note-pencil text-slate-400'"></i>
                                        <input type="text" name="checklist[{{ $index }}][keterangan]" x-model="keterangan" 
                                               placeholder="{{ __('Notes (Optional)...') }}" 
                                               class="w-full text-[11px] py-2 pl-8 pr-3 border-2 bg-white rounded-lg focus:ring-2 outline-none transition-all font-medium shadow-sm"
                                               :class="jawaban === 'tidak' ? 'border-red-200 focus:border-red-400 focus:ring-red-100 placeholder:text-red-300 text-red-700' : 'border-slate-200 focus:border-[#009B77] focus:ring-[#009B77]/15 placeholder:text-slate-300 text-slate-700'">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                
                <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50/50">
                <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="ph-bold ph-note text-[#009B77] text-lg"></i> {{ __('Conclusion & Documentation') }}
                </h4>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status Akhir -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Overall Status') }}</label>
                            <div x-data="{ open: false, options: { 'layak': '{{ __('Good Condition') }}', 'perbaikan': '{{ __('Needs Repair') }}', 'isi_ulang': '{{ __('Needs Refill') }}', 'rusak': '{{ __('Broken / Service') }}' } }" class="relative">
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
                        <!-- Qty -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Qty</label>
                            <div class="relative">
                                <i class="ph-bold ph-hash absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                                <input type="number" name="qty" min="0" x-model="qty" required
                                       class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-2.5 pl-12 pr-4 text-sm font-bold text-slate-700 focus:bg-white focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none">
                            </div>
                        </div>
                        
                        <!-- Tgl Kedaluwarsa -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Expired</label>
                            <div class="relative">
                                <input type="text" name="tgl_kedaluwarsa" x-model="tgl_kedaluwarsa" required
                                       x-init="flatpickr($el, { dateFormat: 'Y-m-d', disableMobile: 'true', defaultDate: tgl_kedaluwarsa, onChange: (s, d) => { tgl_kedaluwarsa = d; } })"
                                       class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-700 focus:bg-white focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none appearance-none">
                            </div>
                        </div>

                        <!-- Upload Foto -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">
                                {{ __('Condition Photo') }}
                                <span class="text-[10px] text-red-500 ml-1">
                                    {{ __('(Wajib)') }}
                                </span>
                            </label>
                            @if(request('source') === 'schedule')
                            <p class="text-[10px] text-slate-500 mb-3 leading-relaxed">
                                <i class="ph-bold ph-info text-[#009B77] mr-0.5"></i> {{ __('Silakan unggah foto bukti form ceklis (kertas) yang telah diisi secara manual di lapangan.') }}
                            </p>
                            @else
                            <p class="text-[10px] text-slate-500 mb-3 leading-relaxed">
                                <i class="ph-bold ph-info text-[#009B77] mr-0.5"></i> {{ __('Lampirkan form saat inspeksi berlangsung.') }}
                            </p>
                            @endif
                            <div class="relative w-full rounded-xl overflow-hidden bg-slate-100 border-2 border-dashed border-slate-300 hover:border-[#009B77] transition-all min-h-[180px] flex items-center justify-center group" x-data="{ fileName: '', photoPreview: null }">
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
                                
                                <label for="foto" class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-3 p-4 cursor-pointer transition-all" x-show="!photoPreview">
                                    <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-[#009B77] group-hover:scale-110 transition-all flex-shrink-0">
                                        <i class="ph-bold ph-camera text-xl"></i>
                                    </div>
                                    <span class="text-sm font-bold text-slate-500 group-hover:text-[#009B77] text-center leading-none">{{ __('Take Photo') }}</span>
                                    <span class="text-[10px] font-bold text-red-500 bg-red-50 px-3 py-1.5 border border-red-200 rounded-md uppercase tracking-widest text-center leading-tight">{{ __('TAKE PHOTO IN LANDSCAPE') }}</span>
                                </label>

                                <template x-if="photoPreview">
                                    <div class="absolute inset-0 w-full h-full">
                                        <img :src="photoPreview" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                                        <label for="foto" class="absolute bottom-3 right-3 bg-white/90 hover:bg-white backdrop-blur px-3 py-1.5 rounded-lg text-xs font-bold text-slate-700 shadow-sm cursor-pointer transition-colors flex items-center gap-1.5 z-20">
                                            <i class="ph-bold ph-arrows-clockwise"></i> {{ __('Change Photo') }}
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
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Additional Notes (Optional)') }}</label>
                        <div class="relative">
                            <i class="ph-bold ph-note-pencil absolute left-4 top-4 text-slate-400 text-lg"></i>
                            <textarea rows="3" name="catatan_tambahan" placeholder="{{ __('Write other general observation notes...') }}" x-model="catatan_tambahan"
                                      class="w-full bg-white border-2 border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm font-bold text-slate-800 focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none placeholder:text-slate-400 placeholder:font-medium resize-none"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-white sticky bottom-0 z-10 shadow-[0_-5px_15px_rgba(0,0,0,0.02)]">
                <button type="button" @click="step = 1" class="w-full sm:w-auto px-5 py-2 rounded-lg font-bold text-slate-600 hover:text-slate-900 bg-white border-2 border-slate-200 hover:border-slate-300 transition-all text-xs flex items-center justify-center gap-1.5">
                    <i class="ph-bold ph-arrow-left text-base"></i>
                    {{ __('Kembali') }}
                </button>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <a href="{{ $backUrl }}" class="flex-1 sm:flex-none px-5 py-2 text-center rounded-lg font-bold text-red-500 hover:text-red-600 bg-red-50 hover:bg-red-100 border border-transparent transition-all text-xs">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="flex-1 sm:flex-none btn-smooth-ring bg-[#009B77] hover:bg-[#008264] text-white font-bold py-2 px-6 rounded-lg shadow-sm shadow-[#009B77]/25 transition-all flex items-center justify-center gap-1.5 text-xs">
                        <i class="ph-bold ph-check-circle text-base"></i>
                        {{ __('Complete Inspection') }}
                    </button>
                </div>
            </div>
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
            <h2 class="text-lg font-bold text-slate-800">{{ __('Update PFE Data') }}</h2>
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
                    
                    <!-- {{ __('PFE Number') }} -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('PFE Number') }} <span class="text-[10px] text-slate-400 font-medium normal-case">{{ __('(Automatic)') }}</span></label>
                        <div class="relative">
                            <i class="ph-bold ph-hash absolute left-3.5 top-1/2 -translate-y-1/2 text-amber-500 text-lg"></i>
                            <input type="number" name="nomor_apar" placeholder="{{ __('Enter sequence number...') }}" required x-model="editApar.nomor_apar" class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium text-slate-700 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 transition-all outline-none">
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
                            return sel ? sel.name : '{{ __('Select Building') }}';
                        },
                        get filteredOptions() {
                            if (this.search === '') return this.options;
                            return this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
                        }
                    }">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Building') }}</label>
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
                                        <input type="text" x-model="search" placeholder="{{ __('Search...') }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 pl-9 pr-3 text-sm font-medium text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all" @click.stop @keydown.enter.prevent>
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
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Location') }} <span class="text-[10px] text-slate-400 font-medium normal-case">{{ __('(Type)') }}</span></label>
                        <div class="relative">
                            <input type="hidden" name="lokasi" :value="editApar.lokasi">
                            <i class="ph-bold ph-map-pin absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg transition-colors z-10" :class="open ? 'text-amber-500' : 'peer-focus:text-amber-500'"></i>
                            <input type="text" x-model="editApar.lokasi" @focus="open = true" @click.away="open = false" placeholder="{{ __('Example: Corridor') }}" required 
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
                            return sel ? sel.name : '{{ __('Select Type') }}';
                        },
                        get filteredOptions() {
                            if (this.search === '') return this.options;
                            return this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
                        }
                    }">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('PFE Type') }}</label>
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
                                        <input type="text" x-model="search" placeholder="{{ __('Search...') }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 pl-9 pr-3 text-sm font-medium text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all" @click.stop @keydown.enter.prevent>
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
                            return sel ? sel.name : '{{ __('Select Capacity') }}';
                        },
                        get filteredOptions() {
                            if (this.search === '') return this.options;
                            return this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
                        }
                    }">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Capacity') }}</label>
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
                                        <input type="text" x-model="search" placeholder="{{ __('Search...') }}" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 pl-9 pr-3 text-sm font-medium text-slate-700 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all" @click.stop @keydown.enter.prevent>
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
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Vendor') }}</label>
                        <div class="relative">
                            <i class="ph-bold ph-storefront absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                            <input type="text" name="vendor" placeholder="{{ __('Vendor name (Optional)') }}" x-model="editApar.vendor" class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-medium text-slate-700 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/15 transition-all outline-none">
                        </div>
                    </div>

                    <!-- Tanggal Kedaluwarsa -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">{{ __('Expiration Date') }}</label>
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
                <i class="ph-bold ph-floppy-disk text-lg"></i> {{ __('Save Changes') }}
            </button>
        </div>
    </div>

</body>
</html>
