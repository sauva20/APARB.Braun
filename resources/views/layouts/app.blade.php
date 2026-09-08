<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - APAR Monitoring System')</title>
    
    <!-- Tailwind CSS (via Vite or CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Keep JS Vite for Alpine and others if needed -->
    @vite(['resources/js/app.js'])

    
    <!-- Alpine Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <!-- Alpine Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Phosphor Icons -->
    <script src="https://cdn.jsdelivr.net/npm/@phosphor-icons/web"></script>
    
    <!-- Flatpickr (Datepicker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(event, message) {
            event.preventDefault();
            const form = event.target;
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: message || "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-slate-100',
                    title: 'text-slate-800 font-bold',
                    htmlContainer: 'text-slate-500 font-semibold',
                    confirmButton: 'rounded-xl font-bold px-6 py-2.5 transition-transform hover:-translate-y-0.5',
                    cancelButton: 'rounded-xl font-bold px-6 py-2.5 transition-transform hover:-translate-y-0.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>

    <style>
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
            background: #009B77 !important;
            border-color: #009B77 !important;
        }

        @font-face {
            font-family: 'Rotis Sans Serif';
            src: local('Rotis Sans Serif'), local('Arial');
        }
        
        body {
            font-family: 'Rotis Sans Serif', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        [x-cloak] { display: none !important; }
        
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Global UI Animations */
        .report-card {
            position: relative;
        }
        .report-card::after {
            content: '';
            position: absolute;
            top: -4px; right: -4px; bottom: -4px; left: -4px;
            border: 2px solid #009B77;
            border-radius: 20px;
            opacity: 0;
            transform: scale(0.96);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }
        .report-card:hover::after {
            opacity: 1;
            transform: scale(1);
        }
        
        .btn-smooth-ring {
            position: relative;
        }
        .btn-smooth-ring::after {
            content: '';
            position: absolute;
            top: -4px; right: -4px; bottom: -4px; left: -4px;
            border: 2px solid #009B77;
            border-radius: 16px;
            opacity: 1;
            transform: scale(1);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }
        .btn-smooth-ring:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 antialiased min-h-screen flex" x-data="{ sidebarOpen: false }">
    <!-- Global Toast Notification -->
    @if(session('success') || session('error') || $errors->any())
        <div x-data="{ show: false, type: '{{ session('success') ? 'success' : 'error' }}', message: '{{ addslashes(session('success') ?? session('error') ?? $errors->first()) }}' }" 
             x-init="setTimeout(() => show = true, 100); setTimeout(() => show = false, 4000)"
             x-show="show"
             style="display: none;"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="fixed bottom-6 right-6 z-[9999] flex items-center gap-3 px-5 py-4 rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.2)] border min-w-[300px] max-w-sm backdrop-blur-md"
             :class="type === 'success' ? 'bg-white/95 border-teal-100' : 'bg-white/95 border-red-100'">
             
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                 :class="type === 'success' ? 'bg-teal-50 text-[#009B77]' : 'bg-red-50 text-red-500'">
                <i class="ph-fill text-xl" :class="type === 'success' ? 'ph-check-circle' : 'ph-warning-circle'"></i>
            </div>
            
            <div class="flex flex-col">
                <span class="text-sm font-bold text-slate-800" x-text="type === 'success' ? 'Berhasil!' : 'Oops, Terjadi Kesalahan!'"></span>
                <span class="text-xs font-medium text-slate-500 mt-0.5" x-text="message"></span>
            </div>
            
            <button @click="show = false" class="ml-auto w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
    @endif
    
    <!-- Sidebar -->
    <aside @mouseenter="sidebarOpen = true" @mouseleave="sidebarOpen = false" 
           :class="sidebarOpen ? 'w-[260px]' : 'w-[80px]'" 
           class="w-[80px] bg-white flex-col hidden md:flex border-r border-slate-200/60 z-20 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] relative flex-shrink-0 shadow-[4px_0_24px_rgba(0,0,0,0.01)] will-change-[width] overflow-x-hidden">
        
        <!-- Logo Area -->
        <div class="px-0 justify-center h-[72px] flex items-center border-b border-slate-100 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] w-full overflow-hidden" :class="sidebarOpen ? 'px-6 justify-start' : 'px-0 justify-center'">
            <div class="w-[22px] relative h-6 transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden flex-shrink-0" :class="sidebarOpen ? 'w-[140px]' : 'w-[22px]'">
                <img src="{{ asset('images/logo.png') }}?v=3" alt="B|Braun Logo" class="absolute left-0 top-0 h-full max-w-none">
            </div>
        </div>
        
        <!-- User Profile -->
        <div class="justify-center p-4 border-b border-slate-50 flex items-center transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden" :class="sidebarOpen ? 'justify-start' : 'justify-center'">
            <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex-shrink-0 flex items-center justify-center">
                <span class="text-sm font-bold text-[#009B77]">SS</span>
            </div>
            <div class="opacity-0 w-0 ml-0 whitespace-nowrap transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden" :class="sidebarOpen ? 'opacity-100 w-32 ml-3' : 'opacity-0 w-0 ml-0'">
                <h3 class="font-bold text-sm text-slate-800">Safety Systems</h3>
                <p class="text-xs text-slate-500">EHSS, SM, OE, LPMO</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1.5">
            <a href="/dashboard" class="justify-center flex items-center p-3 overflow-hidden rounded-xl font-semibold transition-all {{ request()->is('dashboard') ? 'bg-[#009B77] text-white shadow-[0_4px_12px_rgba(0,155,119,0.25)] hover:bg-[#008264]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'" title="Dashboard">
                <i class="ph-fill ph-squares-four text-xl flex-shrink-0"></i>
                <span class="opacity-0 w-0 ml-0 text-sm whitespace-nowrap transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden" :class="sidebarOpen ? 'opacity-100 w-32 ml-3' : 'opacity-0 w-0 ml-0'">Dashboard</span>
            </a>
            
            <a href="/master-data" class="justify-center flex items-center p-3 overflow-hidden rounded-xl font-semibold transition-all {{ request()->is('master-data') ? 'bg-[#009B77] text-white shadow-[0_4px_12px_rgba(0,155,119,0.25)] hover:bg-[#008264]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'" title="Master Data APAR">
                <i class="ph-duotone ph-list-dashes text-xl flex-shrink-0"></i>
                <span class="opacity-0 w-0 ml-0 text-sm whitespace-nowrap transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden" :class="sidebarOpen ? 'opacity-100 w-32 ml-3' : 'opacity-0 w-0 ml-0'">Master Data APAR</span>
            </a>
            
            <a href="/inspection-schedule" class="justify-center flex items-center p-3 overflow-hidden rounded-xl font-semibold transition-all {{ request()->is('inspection-schedule') ? 'bg-[#009B77] text-white shadow-[0_4px_12px_rgba(0,155,119,0.25)] hover:bg-[#008264]' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'" title="Inspection Schedule">
                <i class="ph-duotone ph-calendar-blank text-xl flex-shrink-0"></i>
                <span class="opacity-0 w-0 ml-0 text-sm whitespace-nowrap transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden" :class="sidebarOpen ? 'opacity-100 w-32 ml-3' : 'opacity-0 w-0 ml-0'">Inspection Schedule</span>
            </a>
            
            <a href="#" class="justify-center flex items-center p-3 overflow-hidden rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-800 font-medium transition-colors" :class="sidebarOpen ? 'justify-start' : 'justify-center'" title="Reports">
                <i class="ph-duotone ph-file-text text-xl flex-shrink-0"></i>
                <span class="opacity-0 w-0 ml-0 text-sm whitespace-nowrap transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden" :class="sidebarOpen ? 'opacity-100 w-32 ml-3' : 'opacity-0 w-0 ml-0'">Reports</span>
            </a>
        </nav>
        <!-- Bottom Actions (Removed) -->
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden relative">
        
        <!-- Top Navbar -->
        <header class="h-[72px] bg-white border-b border-slate-200/60 flex items-center justify-between px-8 z-10 flex-shrink-0 shadow-[0_2px_10px_rgba(0,0,0,0.01)]">
            <!-- Left Title -->
            <div class="flex-1 flex items-center">
                <h2 class="text-md font-bold text-[#009B77] tracking-wider uppercase">
                    APAR MONITORING SYSTEM
                </h2>
            </div>

            <!-- Center Search Area (Dashboard only) -->
            @if(request()->is('dashboard'))
            <div class="flex-1 flex justify-center">
                <div class="relative w-full max-w-md hidden sm:block">
                    <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-[#009B77] text-lg"></i>
                    <input type="text" placeholder="Cari ID APAR, lokasi, atau status..." 
                           class="w-full bg-white border-2 border-[#009B77] rounded-xl py-2.5 pl-11 pr-4 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-[#009B77]/20 transition-all placeholder:text-slate-400 font-medium">
                </div>
            </div>
            @else
            <div class="flex-1"></div>
            @endif

            <!-- Right Actions -->
            <div class="flex-1 flex items-center justify-end gap-3">
                <button class="w-10 h-10 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-50 hover:text-[#009B77] transition-colors relative">
                    <i class="ph-bold ph-bell text-xl"></i>
                    <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                </button>
                <div class="w-px h-6 bg-slate-200 mx-1"></div>
                <button class="w-10 h-10 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-50 hover:text-[#009B77] transition-colors" title="Pengaturan">
                    <i class="ph-bold ph-gear text-xl"></i>
                </button>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Keluar">
                        <i class="ph-bold ph-sign-out text-xl"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <div class="p-8 overflow-y-auto flex-1">
            <div class="w-full max-w-[1400px] mx-auto">
                @yield('content')
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                locale: "id",
                allowInput: true,
                static: true
            });
        });
    </script>
</body>
</html>
