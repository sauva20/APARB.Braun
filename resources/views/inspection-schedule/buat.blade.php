@extends('layouts.app')

@section('title', 'Buat Jadwal Inspeksi - PFE Monitoring Control System')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Left: Page Context -->
        <div class="flex items-center gap-3">
            <a href="/inspection-schedule" class="w-10 h-10 rounded-xl bg-white border border-slate-200/60 shadow-sm flex items-center justify-center text-slate-500 hover:text-[#009B77] hover:border-[#009B77] transition-all" title="Kembali">
                <i class="ph-bold ph-arrow-left text-xl"></i>
            </a>
            <div>
                <h2 class="text-lg font-extrabold text-slate-800 leading-tight">Buat Jadwal Inspeksi Baru</h2>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">Tentukan area, tanggal, dan petugas untuk inspeksi rutin/khusus</p>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-slate-200/60 overflow-hidden">
        <form action="#" method="POST" class="p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jenis Jadwal -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jenis Jadwal</label>
                    <div x-data="{ open: false, selected: '', options: ['Inspeksi Rutin Bulanan', 'Inspeksi Khusus / Temuan'] }" class="relative">
                        <input type="hidden" name="jenis_jadwal" :value="selected">
                        <i class="ph-bold ph-calendar-star absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg transition-colors z-10" :class="open ? 'text-[#009B77]' : ''"></i>
                        <button type="button" @click="open = !open" @click.away="open = false" 
                                class="w-full bg-slate-50/50 border-2 border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm font-bold text-slate-800 focus:bg-white focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                :class="open ? 'bg-white border-[#009B77] ring-4 ring-[#009B77]/15' : ''">
                            <span x-text="selected || 'Pilih Jenis Jadwal'" :class="!selected ? 'text-slate-400 font-medium' : ''" class="truncate block"></span>
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
                             class="absolute left-0 z-50 w-full mt-2 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 overflow-hidden origin-top">
                            <template x-for="option in options">
                                <button type="button" @click="selected = option; open = false" class="w-full text-left px-4 py-2.5 text-sm font-bold transition-colors flex items-center justify-between" :class="selected === option ? 'text-[#009B77] bg-[#009B77]/5' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                    <span x-text="option"></span>
                                    <i class="ph-bold ph-check text-[#009B77]" x-show="selected === option"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Tanggal Inspeksi -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Inspeksi</label>
                    <div class="relative" x-data x-init="flatpickr($refs.dateInput, { dateFormat: 'Y-m-d', minDate: 'today', locale: 'id' })">
                        <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg transition-colors peer-focus:text-[#009B77] z-10"></i>
                        <input x-ref="dateInput" type="text" placeholder="Pilih Tanggal"
                               class="peer w-full bg-slate-50/50 border-2 border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm font-bold text-slate-800 focus:bg-white focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none placeholder:text-slate-400 placeholder:font-medium appearance-none cursor-pointer bg-transparent">
                    </div>
                </div>

                <!-- Cakupan Lokasi/Gedung -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cakupan Area (Gedung)</label>
                    <div x-data="{ 
                        open: false, 
                        selected: '', 
                        options: [
                            { label: 'Semua Area', value: 'semua', disabled: false },
                            { label: 'Gedung Utama (Sudah dijadwalkan)', value: 'utama', disabled: true },
                            { label: 'Gedung Produksi', value: 'produksi', disabled: false },
                            { label: 'Gudang Logistik', value: 'gudang', disabled: false }
                        ] 
                    }" class="relative">
                        <input type="hidden" name="cakupan" :value="selected">
                        <i class="ph-bold ph-buildings absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg transition-colors z-10" :class="open ? 'text-[#009B77]' : ''"></i>
                        <button type="button" @click="open = !open" @click.away="open = false" 
                                class="w-full bg-slate-50/50 border-2 border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm font-bold text-slate-800 focus:bg-white focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                :class="open ? 'bg-white border-[#009B77] ring-4 ring-[#009B77]/15' : ''">
                            <span x-text="selected || 'Pilih Area Cakupan'" :class="!selected ? 'text-slate-400 font-medium' : ''" class="truncate block"></span>
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
                             class="absolute left-0 z-50 w-full mt-2 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 overflow-hidden origin-top">
                            <template x-for="option in options">
                                <button type="button" 
                                        :disabled="option.disabled"
                                        @click="if(!option.disabled) { selected = option.label; open = false }" 
                                        class="w-full text-left px-4 py-2.5 text-sm font-bold transition-colors flex items-center justify-between" 
                                        :class="[
                                            selected === option.label ? 'text-[#009B77] bg-[#009B77]/5' : (option.disabled ? 'text-slate-300 bg-slate-50 cursor-not-allowed' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900')
                                        ]">
                                    <span x-text="option.label"></span>
                                    <div class="flex items-center gap-2">
                                        <i class="ph-bold ph-check text-[#009B77]" x-show="selected === option.label"></i>
                                        <i class="ph-bold ph-lock-key text-slate-300" x-show="option.disabled"></i>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Petugas Inspeksi -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Petugas Inspeksi</label>
                    <div x-data="{ open: false, selected: '', options: ['Bebas / Siapa Saja', 'Budi Santoso', 'Dewi Wahyuni', 'Andi Rahman'] }" class="relative">
                        <input type="hidden" name="petugas" :value="selected">
                        <i class="ph-bold ph-user-circle absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg transition-colors z-10" :class="open ? 'text-[#009B77]' : ''"></i>
                        <button type="button" @click="open = !open" @click.away="open = false" 
                                class="w-full bg-slate-50/50 border-2 border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm font-bold text-slate-800 focus:bg-white focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none cursor-pointer text-left flex items-center justify-between"
                                :class="open ? 'bg-white border-[#009B77] ring-4 ring-[#009B77]/15' : ''">
                            <span x-text="selected || 'Pilih Petugas'" :class="!selected ? 'text-slate-400 font-medium' : ''" class="truncate block"></span>
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
                             class="absolute left-0 z-50 w-full mt-2 bg-white rounded-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100 py-2 overflow-hidden origin-top">
                            <template x-for="option in options">
                                <button type="button" @click="selected = option; open = false" class="w-full text-left px-4 py-2.5 text-sm font-bold transition-colors flex items-center justify-between" :class="selected === option ? 'text-[#009B77] bg-[#009B77]/5' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                    <span x-text="option"></span>
                                    <i class="ph-bold ph-check text-[#009B77]" x-show="selected === option"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Catatan Tambahan (Opsional)</label>
                    <div class="relative">
                        <i class="ph-bold ph-note-pencil absolute left-4 top-4 text-slate-400 text-lg transition-colors peer-focus:text-[#009B77]"></i>
                        <textarea rows="3" placeholder="Contoh: Fokuskan pada APAR di area dapur dan genset."
                                  class="peer w-full bg-slate-50/50 border-2 border-slate-200 rounded-xl py-3 pl-11 pr-4 text-sm font-bold text-slate-800 focus:bg-white focus:border-[#009B77] focus:ring-4 focus:ring-[#009B77]/15 transition-all outline-none placeholder:text-slate-400 placeholder:font-medium resize-none"></textarea>
                    </div>
                </div>

            </div>
            
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="/inspection-schedule" class="px-6 py-2.5 rounded-xl font-bold text-slate-600 hover:text-slate-900 bg-white border-2 border-slate-200 hover:border-slate-300 transition-all text-sm">
                    Batal
                </a>
                <button type="submit" class="btn-smooth-ring bg-[#009B77] hover:bg-[#008264] text-white font-bold py-2.5 px-8 rounded-xl shadow-[0_4px_12px_rgba(0,155,119,0.25)] transition-all flex items-center gap-2 text-sm">
                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
