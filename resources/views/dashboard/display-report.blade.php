@extends('layouts.display')

@section('title', 'Dashboard Overview - APAR Monitoring System')

@section('content')
<style>
@media print {
    @page { size: landscape; margin: 10mm; }
    html, body { 
        background: white !important; 
        -webkit-print-color-adjust: exact !important; 
        print-color-adjust: exact !important; 
        height: auto !important;
        overflow: visible !important;
    }
    
    * { overflow: visible !important; }

    /* Hide UI elements and tables */
    .sidebar, header, nav, .btn-smooth-ring { display: none !important; }
    
    /* Target only the tables */
    [x-show="tab === 'belum'"], 
    [x-show="tab === 'sudah'"],
    #recentInspectionsTable {
        display: none !important;
    }

    main { 
        padding: 0 !important; 
        margin: 0 !important; 
        height: auto !important; 
        overflow: visible !important;
    }
    
    /* Frame scale for 1 page fit (landscape) */
    .space-y-4 { margin: 0 !important; padding: 0 !important; zoom: 0.85; transform-origin: top center; display: flex; flex-direction: column; gap: 1rem; }
    
    /* Force desktop grid layouts for print */
    .grid-cols-1.xl\:grid-cols-6 {
        grid-template-columns: repeat(6, minmax(0, 1fr)) !important;
    }
    .grid-cols-1.lg\:grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    }
    .print-col-span-2 {
        grid-column: span 2 / span 2 !important;
    }
    
    /* Force green box grid layout for print */
    .md\:grid-cols-12 {
        grid-template-columns: repeat(12, minmax(0, 1fr)) !important;
    }
    .md\:col-span-3 {
        grid-column: span 3 / span 3 !important;
    }
    .md\:col-span-6 {
        grid-column: span 6 / span 6 !important;
    }
    
    .report-card, .chart-container {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }

    /* Hide the tabs dropdown in print */
    .max-w-md, [x-data="{ tab: null }"] > div:nth-child(4) { display: none !important; }
}
</style>

<!-- Include ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="flex flex-col flex-1 h-full gap-3">
    
    <!-- Header Area -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 print:hidden bg-[#009B77]/10 p-3 rounded-2xl border border-[#009B77]/20">
        <div class="flex items-center gap-3 px-1">
            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-[#009B77]">
                <i class="ph-bold ph-calendar-blank text-xl"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-emerald-900">{{ __('Today\'s Statistics') }}</h2>
                <p class="text-[11px] font-semibold text-emerald-700">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
    </div>


    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-3">
        
        <!-- Card 1: {{ __('TOTAL PFE') }} -->
        <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-1 border border-[#009B77]/40 flex flex-col justify-between transition-all duration-300 group cursor-pointer report-card relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-[#009B77]/5 rounded-full blur-2xl group-hover:bg-[#009B77]/10 transition-colors"></div>
            <div class="flex items-center justify-between mb-3 relative z-10">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('TOTAL PFE') }}</h3>
                <div class="w-8 h-8 rounded-lg bg-[#009B77]/10 group-hover:bg-[#009B77]/20 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-[#009B77]">
                    <i class="ph-fill ph-fire-extinguisher text-lg"></i>
                </div>
            </div>
            <div class="flex items-end justify-between relative z-10">
                <h2 class="text-2xl font-extrabold text-slate-800 transition-colors group-hover:text-[#009B77]">{{ $totalApar }}</h2>
            </div>
        </div>

        <!-- Card 2: {{ __('GOOD CONDITION') }} -->
        <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-1 border border-[#009B77]/40 flex flex-col justify-between transition-all duration-300 group cursor-pointer report-card relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-teal-500/5 rounded-full blur-2xl group-hover:bg-teal-500/10 transition-colors"></div>
            <div class="flex items-center justify-between mb-3 relative z-10">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('GOOD CONDITION') }}</h3>
                <div class="w-8 h-8 rounded-lg bg-teal-50 group-hover:bg-teal-100 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-teal-600">
                    <i class="ph-bold ph-check-circle text-lg"></i>
                </div>
            </div>
            <div class="relative z-10">
                <div class="flex items-end justify-between mb-2">
                    <h2 class="text-2xl font-extrabold text-slate-800 transition-colors group-hover:text-teal-600">{{ $kondisiBaik }}</h2>
                    <span class="text-xs font-bold text-teal-600 mb-1.5">{{ $totalApar > 0 ? round(($kondisiBaik / $totalApar) * 100) : 0 }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-teal-500 h-1.5 rounded-full transition-all duration-1000" style="width: {{ $totalApar > 0 ? round(($kondisiBaik / $totalApar) * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Card 3: {{ __('BROKEN / SERVICE') }} -->
        <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-1 border border-[#009B77]/40 flex flex-col justify-between transition-all duration-300 group cursor-pointer report-card relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-500/5 rounded-full blur-2xl group-hover:bg-red-500/10 transition-colors"></div>
            <div class="flex items-center justify-between mb-3 relative z-10">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('BROKEN / SERVICE') }}</h3>
                <div class="w-8 h-8 rounded-lg bg-red-50 group-hover:bg-red-100 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-red-500">
                    <i class="ph-fill ph-wrench text-lg"></i>
                </div>
            </div>
            <div class="flex items-end justify-between relative z-10">
                <h2 class="text-2xl font-extrabold text-red-500 transition-transform group-hover:scale-105 origin-left">{{ $rusakServis }}</h2>
                @if($rusakServis > 0)
                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-600 bg-red-50 border border-red-100 px-2 py-1 rounded-lg mb-1.5 uppercase tracking-wider">
                    <i class="ph-bold ph-warning"></i> {{ __('Immediate') }}
                </span>
                @endif
            </div>
        </div>

        <!-- Card 4: {{ __('EXPIRING SOON') }} -->
        <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-1 border border-[#009B77]/40 flex flex-col justify-between transition-all duration-300 group cursor-pointer report-card relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-500/5 rounded-full blur-2xl group-hover:bg-amber-500/10 transition-colors"></div>
            <div class="flex items-center justify-between mb-3 relative z-10">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('EXPIRING SOON') }}</h3>
                <div class="w-8 h-8 rounded-lg bg-amber-50 group-hover:bg-amber-100 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-amber-500">
                    <i class="ph-bold ph-hourglass-high text-lg"></i>
                </div>
            </div>
            <div class="flex items-end justify-between relative z-10">
                <h2 class="text-2xl font-extrabold text-amber-500 transition-transform group-hover:scale-105 origin-left">{{ $akanKedaluwarsa }}</h2>
                @if($akanKedaluwarsa > 0)
                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-100 px-2 py-1 rounded-lg mb-1.5 uppercase tracking-wider">
                    <i class="ph-bold ph-calendar-blank"></i> &lt; 30 hr
                </span>
                @endif
            </div>
        </div>

        <!-- Card 5: Sudah {{ __('EXPIRED') }} -->
        <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-1 border border-[#009B77]/40 flex flex-col justify-between transition-all duration-300 group cursor-pointer report-card relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-600/5 rounded-full blur-2xl group-hover:bg-red-600/10 transition-colors"></div>
            <div class="flex items-center justify-between mb-3 relative z-10">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('EXPIRED') }}</h3>
                <div class="w-8 h-8 rounded-lg bg-red-50 group-hover:bg-red-100 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-red-600">
                    <i class="ph-bold ph-x-circle text-lg"></i>
                </div>
            </div>
            <div class="flex items-end justify-between relative z-10">
                <h2 class="text-2xl font-extrabold text-red-600 transition-transform group-hover:scale-105 origin-left">{{ $sudahKedaluwarsa }}</h2>
                @if($sudahKedaluwarsa > 0)
                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-700 bg-red-50 border border-red-100 px-2 py-1 rounded-lg mb-1.5 uppercase tracking-wider">
                    <i class="ph-bold ph-warning"></i> {{ __('Danger') }}
                </span>
                @endif
            </div>
        </div>

        <!-- Card 6: {{ __('EMPTY PFE') }} -->
        <div class="bg-white rounded-2xl p-4 shadow-sm hover:shadow-[0_8px_30px_rgba(0,0,0,0.04)] hover:-translate-y-1 border border-[#009B77]/40 flex flex-col justify-between transition-all duration-300 group cursor-pointer report-card relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-rose-500/5 rounded-full blur-2xl group-hover:bg-rose-500/10 transition-colors"></div>
            <div class="flex items-center justify-between mb-3 relative z-10">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('EMPTY PFE') }}</h3>
                <div class="w-8 h-8 rounded-lg bg-rose-50 group-hover:bg-rose-100 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-rose-500">
                    <i class="ph-bold ph-warning-octagon text-lg"></i>
                </div>
            </div>
            <div class="flex items-end justify-between relative z-10">
                <h2 class="text-2xl font-extrabold text-rose-500 transition-transform group-hover:scale-105 origin-left">{{ $aparKosong }}</h2>
                @if($aparKosong > 0)
                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600 bg-rose-50 border border-rose-100 px-2 py-1 rounded-lg mb-1.5 uppercase tracking-wider">
                    <i class="ph-bold ph-warning"></i> {{ __('Empty') }}
                </span>
                @endif
            </div>
        </div>

    </div>

    <!-- Progress Inspeksi Section -->
    <div class="bg-[#009B77] rounded-2xl shadow-[0_8px_30px_rgba(0,155,119,0.2)] mb-4 overflow-hidden" x-data="{ tab: null }">
        <div class="p-4 grid grid-cols-1 md:grid-cols-12 gap-5 items-center border-b border-[#008264]">
            <!-- Left: Pie Chart -->
            <div class="md:col-span-3 lg:col-span-3 flex justify-center">
                <div id="progressPieChart" class="w-full max-w-[160px]"></div>
            </div>
            
            <!-- Middle: Info & Tabs -->
            <div class="md:col-span-6 lg:col-span-6">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-lg font-extrabold text-white mb-0.5">{{ __('Inspection Progress for') }} {{ __(\Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->format('F')) }} {{ $selectedYear }}</h3>
                        <p class="text-[13px] font-medium text-emerald-100">{{ __('Monitor monthly inspection task completion') }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-5 mb-5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-white">
                            <i class="ph-bold ph-check-circle text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-emerald-100 uppercase tracking-wider">{{ __('INSPECTED') }}</p>
                            <p class="text-lg font-extrabold text-white">{{ $sudahDiinspeksiBulanIni }} <span class="text-xs font-semibold text-emerald-100">APAR</span></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-emerald-100">
                            <i class="ph-bold ph-clock text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-emerald-100 uppercase tracking-wider">{{ __('UNINSPECTED') }}</p>
                            <p class="text-lg font-extrabold text-white">{{ $belumDiinspeksiBulanIni }} <span class="text-xs font-semibold text-emerald-100">APAR</span></p>
                        </div>
                    </div>
                </div>

                <!-- Tabs removed for Display mode -->
            </div>

            <!-- Right: Sisa Waktu Box -->
            <div class="md:col-span-3 lg:col-span-3 flex h-full items-center">
                @php
                    $now = \Carbon\Carbon::now();
                    $endOfMonth = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();
                    $isCurrentMonth = $now->format('Y-m') === $endOfMonth->format('Y-m');
                    
                    if ($isCurrentMonth) {
                        $daysLeft = intval(floor($now->diffInDays($endOfMonth, false)));
                    } elseif ($endOfMonth->isPast()) {
                        $daysLeft = 0;
                    } else {
                        $daysLeft = $endOfMonth->daysInMonth;
                    }
                @endphp
                
                <div class="bg-transparent rounded-2xl p-5 border-2 border-white/50 w-full min-h-[140px] flex flex-col justify-center relative overflow-hidden transition-colors duration-300 hover:bg-white/5">
                    <div class="flex items-center justify-between mb-3 relative z-10">
                        <h3 class="text-[11px] font-bold text-emerald-100 uppercase tracking-wider">{{ __('MONTHLY INSPECTION DEADLINE') }}</h3>
                        <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/20 text-white flex items-center justify-center">
                            <i class="ph-bold ph-hourglass-high text-xl"></i>
                        </div>
                    </div>
                    
                    <div class="relative z-10">
                        @if($isCurrentMonth && $daysLeft > 0)
                            <div class="flex items-baseline gap-1">
                                <span class="text-5xl font-extrabold text-white tracking-tight">{{ $daysLeft }}</span>
                                <span class="text-sm font-semibold text-emerald-100">{{ __('Days') }}</span>
                            </div>
                        @elseif($isCurrentMonth && $daysLeft === 0)
                            <span class="text-2xl font-extrabold text-rose-200">{{ __('Days') }} {{ __('Last!') }}</span>
                        @elseif($endOfMonth->isPast())
                            <span class="text-xl font-extrabold text-emerald-100">{{ __('Month Ended') }}</span>
                        @else
                            <span class="text-xl font-extrabold text-emerald-100">{{ __('Coming Soon') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables removed for Display mode -->
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 flex-1 min-h-0 pb-1">
        <!-- Monthly Inspections Chart -->
        <div class="lg:col-span-2 print-col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200/60 p-4 flex flex-col h-full chart-container">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">{{ __('Inspection Trend Year') }} {{ $selectedYear }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ __('Comparison of INSPECTED and UNINSPECTED PFE every month') }}</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500">
                    <i class="ph-bold ph-chart-bar text-lg"></i>
                </div>
            </div>
            <div class="flex-1 w-full relative min-h-0">
                <div id="monthlyChart" class="absolute inset-0"></div>
            </div>
        </div>

        <!-- APAR by Type Donut Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-4 flex flex-col h-full chart-container">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">{{ __('PFE Type Distribution') }}</h3>
                    <p class="text-xs font-semibold text-slate-500">{{ __('Based on media / agent') }}</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-500">
                    <i class="ph-bold ph-chart-pie-slice text-lg"></i>
                </div>
            </div>
            <div class="flex-1 w-full relative min-h-0 flex items-center justify-center">
                <div id="typeChart" class="w-full absolute inset-0 flex items-center justify-center"></div>
            </div>
        </div>
    </div>

        <!-- Recent Inspections Table Section Removed for Display mode -->
</div>

<!-- Script to initialize ApexCharts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Data for Monthly Inspections Chart
        const yearlyData = @json($yearlyInspections);
        const monthCategories = yearlyData.map(item => item.month);
        const monthSudah = yearlyData.map(item => item.sudah);
        const monthBelum = yearlyData.map(item => item.belum);

        const monthlyOptions = {
            series: [{
                name: '{{ __('INSPECTED') }}',
                data: monthSudah
            }, {
                name: '{{ __('UNINSPECTED') }}',
                data: monthBelum
            }],
            chart: {
                type: 'bar',
                height: '100%',
                fontFamily: 'inherit',
                toolbar: { show: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            colors: ['#009B77', '#F59E0B'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4
                },
            },
            dataLabels: { enabled: false },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: monthCategories,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: {
                        colors: '#64748b',
                        fontWeight: 600,
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#64748b',
                        fontWeight: 600,
                    }
                }
            },
            grid: {
                borderColor: '#cbd5e1',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } },
                padding: { top: 0, right: 0, bottom: 0, left: 10 }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                markers: { radius: 12 }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                theme: 'light',
                y: { formatter: function (val) { return val + " APAR" } }
            }
        };

        if(document.getElementById("monthlyChart") && monthCategories.length > 0) {
            const monthlyChart = new ApexCharts(document.querySelector("#monthlyChart"), monthlyOptions);
            monthlyChart.render();
        } else if (document.getElementById("monthlyChart")) {
            document.getElementById("monthlyChart").innerHTML = '<div class="flex items-center justify-center h-full text-slate-400 font-semibold text-sm">{{ __('No chart data available') }}</div>';
        }

        // Data for APAR Types Donut Chart
        const typesData = @json($jenisData);
        const typeLabels = Object.keys(typesData);
        const typeSeries = Object.values(typesData);
        
        // Elegant Color Palette
        const colors = ['#009B77', '#8b5cf6', '#f59e0b', '#3b82f6', '#ef4444', '#14b8a6'];

        const typeOptions = {
            series: typeSeries,
            chart: {
                type: 'pie',
                height: '100%',
                fontFamily: 'inherit',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            labels: typeLabels,
            colors: colors,
            dataLabels: { enabled: false },
            stroke: {
                show: true,
                colors: '#ffffff',
                width: 3
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                markers: { radius: 12 },
                itemMargin: { horizontal: 10, vertical: 5 },
                formatter: function(seriesName, opts) {
                    return ["<span style='font-weight: 600; color: #475569;'>", seriesName, "</span> <span style='font-weight: 800; color: #1e293b; margin-left: 4px;'>", opts.w.globals.seriesTotals[opts.seriesIndex], "</span>"].join('')
                }
            },
            tooltip: {
                theme: 'light',
                fillSeriesColor: false
            }
        };

        if(document.getElementById("typeChart") && typeSeries.length > 0) {
            const typeChart = new ApexCharts(document.querySelector("#typeChart"), typeOptions);
            typeChart.render();
        } else if (document.getElementById("typeChart")) {
            document.getElementById("typeChart").innerHTML = '<div class="flex items-center justify-center h-full text-slate-400 font-semibold text-sm">{{ __('No PFE data available') }}</div>';
        }

        // Data for Progress Donut Chart
        const progressSudah = {{ $sudahDiinspeksiBulanIni }};
        const progressBelum = {{ $belumDiinspeksiBulanIni }};
        const totalProgress = progressSudah + progressBelum;
        const progressPercentage = totalProgress > 0 ? Math.round((progressSudah / totalProgress) * 100) : 0;

        const progressOptions = {
            series: [progressSudah, progressBelum],
            chart: {
                type: 'donut',
                height: 160,
                fontFamily: 'inherit',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            labels: ['{{ __('INSPECTED') }}', '{{ __('UNINSPECTED') }}'],
            colors: ['#ffffff', '#a7f3d0'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '10px',
                                fontWeight: 700,
                                color: '#a7f3d0',
                                offsetY: -5
                            },
                            value: {
                                show: true,
                                fontSize: '22px',
                                fontWeight: 800,
                                color: '#ffffff',
                                offsetY: 5,
                                formatter: function (val) {
                                    return progressPercentage + "%";
                                }
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: '{{ __('PROGRESS') }}',
                                fontSize: '10px',
                                fontWeight: 700,
                                color: '#a7f3d0',
                                formatter: function (w) {
                                    return progressPercentage + "%";
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            stroke: {
                show: false,
                width: 0
            },
            legend: { show: false },
            tooltip: {
                theme: 'light',
                fillSeriesColor: false,
                y: {
                    formatter: function (val) {
                        return val + " APAR"
                    }
                }
            }
        };

        if(document.getElementById("progressPieChart")) {
            const progressChart = new ApexCharts(document.querySelector("#progressPieChart"), progressOptions);
            progressChart.render();
        }
    });
</script>
@endsection

