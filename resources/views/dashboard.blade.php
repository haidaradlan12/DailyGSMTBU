@extends('layouts.app')

@section('title', 'Daily Monitoring GS MTBU')

@section('content')
<div class="flex-1 flex flex-col xl:h-screen xl:max-h-screen xl:overflow-hidden px-2.5 py-2 sm:px-4 sm:py-2.5 2xl:px-6 2xl:py-3 max-w-[1920px] mx-auto w-full gap-2 2xl:gap-2.5">

    <!-- Top Navigation & Header Bar -->
    <header class="glass-card rounded-xl sm:rounded-2xl px-3 py-2 sm:px-4 sm:py-2 flex flex-wrap items-center justify-between gap-2.5 sm:gap-4 border border-slate-800 shadow-xl shrink-0">
        <!-- 3 Logo Berjejer (PAMA - SRGS - MTBU) & Title -->
        <div class="flex items-center gap-2.5 sm:gap-3.5">
            <!-- 3 Logo Berjejer -->
            <div class="flex items-center gap-2 sm:gap-2.5 bg-white/95 px-2.5 py-1 sm:py-1.5 rounded-xl border border-slate-600 shadow-md shrink-0">
                <img src="{{ asset('images/logos/pama.jpg') }}" alt="Logo PAMA" title="PAMA - member of ASTRA" class="h-6 sm:h-7 2xl:h-8 w-auto object-contain">
                <div class="h-4 sm:h-5 w-px bg-slate-300"></div>
                <img src="{{ asset('images/logos/srgs.jpg') }}" alt="Logo SRGS" title="SRGS DIVISION" class="h-5 sm:h-6 2xl:h-7 w-auto object-contain">
                <div class="h-4 sm:h-5 w-px bg-slate-300"></div>
                <img src="{{ asset('images/logos/mtbu.jpg') }}" alt="Logo MTBU" title="MTBU BANGKIT UNTUK HEBAT" class="h-6 sm:h-7 2xl:h-8 w-auto object-contain">
            </div>

            <div>
                <div class="flex items-center gap-2">
                    <h1 class="font-extrabold text-xs sm:text-sm 2xl:text-base tracking-tight text-white uppercase leading-tight">
                        Daily Monitoring GS MTBU
                    </h1>
                    <!-- Kedip Live: Merah saat konek, Abu-abu jika tidak -->
                    <span id="live-indicator-badge" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] font-bold {{ ($isConnected ?? true) ? 'bg-rose-500/10 text-rose-400 border border-rose-500/30' : 'bg-slate-800 text-slate-400 border border-slate-700' }}" title="{{ ($isConnected ?? true) ? 'Terhubung Live' : 'Terputus / Offline' }}">
                        <span id="live-indicator-dot" class="w-1.5 h-1.5 rounded-full {{ ($isConnected ?? true) ? 'bg-rose-500 animate-ping' : 'bg-slate-500' }}"></span>
                        <span id="live-indicator-text">{{ ($isConnected ?? true) ? 'LIVE' : 'OFFLINE' }}</span>
                    </span>
                </div>
                <p class="text-[10px] sm:text-[11px] text-slate-400 font-medium">General Services Muara Tiga Besar Utara</p>
            </div>
        </div>

        <!-- Date Picker & Controls -->
        <div class="flex items-center flex-wrap gap-2">
            <!-- Tanggal Harian Selector -->
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-1.5 sm:gap-2 bg-slate-900/90 border border-slate-700/80 rounded-xl px-2.5 py-1 sm:px-3 sm:py-1.5 shadow-inner">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <label for="date-selector" class="text-[11px] sm:text-xs font-semibold text-slate-300 shrink-0 hidden xs:inline">Tanggal:</label>
                <input 
                    type="date" 
                    id="date-selector" 
                    name="date" 
                    value="{{ $selectedDate }}" 
                    onchange="this.form.submit()"
                    class="bg-transparent text-white text-[11px] sm:text-xs font-medium focus:outline-none cursor-pointer"
                >
            </form>

            <!-- Live Digital Clock -->
            <div class="flex items-center gap-1.5 sm:gap-2 px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-xl bg-slate-900/90 border border-slate-800 text-slate-300 font-mono-num text-[11px] sm:text-xs">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span id="live-clock" class="font-bold text-slate-100">--:--:-- WIB</span>
            </div>

            <!-- Fullscreen Button -->
            <button onclick="toggleFullscreen()" title="Mode Layar Penuh (TV Display)" class="p-1.5 sm:p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 transition-colors">
                <svg id="fullscreen-icon" class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
            </button>

            <!-- Admin / Login Button -->
            @auth
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-[11px] sm:text-xs font-bold shadow-md shadow-blue-600/30 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Admin</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="px-3 py-1 sm:px-4 sm:py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-[11px] sm:text-xs font-bold transition-all">
                    Login
                </a>
            @endauth
        </div>
    </header>

    <!-- Mobile Quick Navigation Chips (HP only) -->
    <nav class="flex xl:hidden items-center gap-1.5 overflow-x-auto pb-0.5 scrollbar-none text-[11px] -mx-0.5 px-0.5 shrink-0">
        <button onclick="scrollToSection('section-cost')" class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 whitespace-nowrap font-medium active:scale-95 transition-all">
            💰 Cost
        </button>
        <button onclick="scrollToSection('section-dorcat')" class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 whitespace-nowrap font-medium active:scale-95 transition-all">
            🏨 Dormitory & Catering
        </button>
        <button onclick="scrollToSection('section-transport')" class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 whitespace-nowrap font-medium active:scale-95 transition-all">
            🚗 Transport
        </button>
        <button onclick="scrollToSection('section-mep')" class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 whitespace-nowrap font-medium active:scale-95 transition-all">
            ⚡ MEP & Instrument
        </button>
        <button onclick="scrollToSection('section-building')" class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-800 text-slate-300 hover:text-white hover:bg-slate-800 whitespace-nowrap font-medium active:scale-95 transition-all">
            🏗️ Building Const
        </button>
    </nav>

    <!-- BAGIAN 1: TABEL REALISASI COST (5 KARTU DONUT DIAGRAM SEJAJAR - COMPACT & RESPONSIVE) -->
    <section id="section-cost" class="shrink-0">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-2 2xl:gap-2.5">
            @foreach($costs as $catKey => $cost)
                @php
                    $isOver = $cost['percentage'] >= 100;
                    $isWarning = $cost['percentage'] >= 85 && $cost['percentage'] < 100;
                    $accentColor = $isOver ? '#f43f5e' : ($isWarning ? '#f59e0b' : '#3b82f6');
                @endphp
                <div class="glass-card rounded-xl p-2.5 2xl:p-3 flex flex-col justify-between relative overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5">
                    <!-- Category Name Header -->
                    <div class="flex items-center justify-between border-b border-slate-800/80 pb-1.5 mb-1.5">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <div class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $accentColor }};"></div>
                            <h3 class="font-bold text-xs 2xl:text-sm text-slate-100 tracking-tight truncate">{{ $cost['label'] }}</h3>
                        </div>
                        <span class="text-[10px] 2xl:text-[11px] font-bold px-1.5 py-0.5 rounded-full shrink-0 {{ $isOver ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : ($isWarning ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30') }}">
                            {{ $cost['percentage'] }}%
                        </span>
                    </div>

                    <!-- Donut Chart & Breakdown in Compact Side-by-Side on Desktop / Mobile -->
                    <div class="flex items-center justify-between gap-2">
                        <!-- Compact Donut Canvas Container -->
                        <div class="relative w-16 h-16 2xl:w-20 2xl:h-20 flex items-center justify-center shrink-0">
                            <canvas id="chart-{{ $catKey }}" width="80" height="80"></canvas>
                            <!-- Inside Donut Label -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                                <span class="text-[7px] 2xl:text-[8px] uppercase font-semibold text-slate-400 tracking-wider">Realisasi</span>
                                <span class="text-[11px] 2xl:text-xs font-extrabold font-mono-num text-white leading-tight">
                                    {{ $cost['percentage'] }}%
                                </span>
                            </div>
                        </div>

                        <!-- Plan & Actual Breakdown (Plan di atas, Aktual di bawah, Sisa di akhir) -->
                        <div class="flex-1 min-w-0 bg-slate-900/90 rounded-lg p-1.5 2xl:p-2 border border-slate-800/80 space-y-0.5 text-[10px] 2xl:text-[11px] font-mono-num">
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-slate-400 font-sans font-medium text-[9px] 2xl:text-[10px]">Plan:</span>
                                <span class="font-bold text-slate-200 truncate">Rp {{ number_format($cost['plan'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-1 border-t border-slate-800/60 pt-0.5">
                                <span class="text-slate-400 font-sans font-medium text-[9px] 2xl:text-[10px]">Aktual:</span>
                                <span class="font-bold text-cyan-400 truncate">Rp {{ number_format($cost['actual'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-1 border-t border-slate-800/40 pt-0.5">
                                <span class="text-slate-400 font-sans text-[9px] 2xl:text-[10px]">Sisa:</span>
                                <span class="font-semibold {{ $cost['remaining'] < 0 ? 'text-rose-400 font-bold' : 'text-emerald-400' }} truncate">
                                    {{ $cost['remaining'] < 0 ? '-Rp ' . number_format(abs($cost['remaining']), 0, ',', '.') : 'Rp ' . number_format($cost['remaining'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- AREA GRID UTAMA: 4 KOLOM SEJAJAR (DORMINOTY & CATERING, TRANSPORT, MEP & INSTRUMENT, BUILDING CONST) -->
    <section class="flex-1 min-h-0 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-2 2xl:gap-2.5">

        <!-- ================= KOLOM 1: BAGIAN 2 DORMINOTY & CATERING ================= -->
        <div id="section-dorcat" class="glass-card rounded-xl 2xl:rounded-2xl p-3 2xl:p-3.5 flex flex-col justify-between border border-slate-800/90 shadow-xl h-full min-h-0">
            <!-- Header -->
            <div class="flex items-center justify-between pb-2 border-b border-slate-800 shrink-0">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center gap-1">
                        <!-- Kasur (Bed) -->
                        <svg class="w-3.5 h-3.5 2xl:w-4 2xl:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v11m0-4h18m0 4v-8a2 2 0 00-2-2H9a2 2 0 00-2 2v4M7 10a1 1 0 100-2 1 1 0 000 2z"/>
                        </svg>
                        <!-- Sendok & Garpu (Cutlery) -->
                        <svg class="w-3.5 h-3.5 2xl:w-4 2xl:h-4 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 2v6a2 2 0 01-2 2v12M14 2v4a2 2 0 002 2M6 2v6a2 2 0 002 2v12M10 2v6a2 2 0 01-2 2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xs 2xl:text-sm text-white tracking-wide">Dorminoty & Catering</h3>
                    </div>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] 2xl:text-[11px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                    {{ $dorcatStats['total'] }} Issue
                </span>
            </div>

            <!-- Issue Summary Metric -->
            <div class="grid grid-cols-3 gap-1.5 my-2 text-center text-xs shrink-0">
                <div class="bg-slate-900/80 p-1.5 rounded-lg border border-slate-800/80">
                    <span class="text-[9px] text-slate-400 uppercase font-semibold">Open</span>
                    <p class="text-sm 2xl:text-base font-bold text-rose-400 font-mono-num">{{ $dorcatStats['open_count'] }}</p>
                </div>
                <div class="bg-slate-900/80 p-1.5 rounded-lg border border-slate-800/80">
                    <span class="text-[9px] text-slate-400 uppercase font-semibold">Progress</span>
                    <p class="text-sm 2xl:text-base font-bold text-amber-400 font-mono-num">{{ $dorcatStats['progress_count'] }}</p>
                </div>
                <div class="bg-slate-900/80 p-1.5 rounded-lg border border-slate-800/80">
                    <span class="text-[9px] text-slate-400 uppercase font-semibold">Selesai</span>
                    <p class="text-sm 2xl:text-base font-bold text-emerald-400 font-mono-num">{{ $dorcatStats['resolved_count'] }}</p>
                </div>
            </div>

            <!-- Issues List with Scroll inside card -->
            <div class="flex-1 min-h-0 space-y-2 overflow-y-auto pr-1">
                @forelse($dorcatIssues as $issue)
                    @php
                        $statusColor = match($issue->status) {
                            'resolved' => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/30',
                            'in_progress' => 'text-amber-400 bg-amber-500/10 border-amber-500/30',
                            default => 'text-rose-400 bg-rose-500/10 border-rose-500/30',
                        };
                        $statusLabel = match($issue->status) {
                            'resolved' => 'Selesai',
                            'in_progress' => 'Progress',
                            default => 'Open',
                        };
                        $priorityBadge = match($issue->priority) {
                            'critical' => 'bg-rose-600 text-white',
                            'high' => 'bg-amber-600 text-white',
                            default => 'bg-slate-700 text-slate-200',
                        };
                    @endphp
                    <div class="bg-slate-900/70 hover:bg-slate-900 p-2.5 rounded-xl border border-slate-800/80 transition-colors">
                        <div class="flex items-start justify-between gap-1.5">
                            <span class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase {{ $priorityBadge }}">
                                {{ $issue->priority }}
                            </span>
                            <span class="text-[9px] px-1.5 py-0.5 rounded-full border font-semibold {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <h4 class="font-semibold text-xs text-slate-100 mt-1.5 line-clamp-2">
                            {{ $issue->issue_title }}
                        </h4>

                        @if($issue->description)
                            <p class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">{{ $issue->description }}</p>
                        @endif

                        <!-- Progress Bar & Percentage -->
                        <div class="mt-2">
                            <div class="flex items-center justify-between text-[10px] font-mono-num mb-0.5">
                                <span class="text-slate-400 font-sans">Progres:</span>
                                <span class="font-bold text-amber-400">{{ $issue->percentage }}%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-gradient-to-r from-amber-500 to-emerald-400 h-1.5 rounded-full transition-all duration-500" style="width: {{ $issue->percentage }}%"></div>
                            </div>
                        </div>

                        @if($issue->pic_name)
                            <div class="mt-1.5 pt-1.5 border-t border-slate-800/60 flex items-center justify-between text-[10px] text-slate-400">
                                <span>PIC:</span>
                                <span class="font-medium text-slate-300">{{ $issue->pic_name }}</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-4 text-center text-xs text-slate-400 flex flex-col items-center justify-center h-full">
                        <svg class="w-7 h-7 text-slate-600 mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Belum ada Hot Issue pada tanggal ini.</span>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ================= KOLOM 2: BAGIAN 3 TRANSPORT ================= -->
        <div id="section-transport" class="glass-card rounded-xl 2xl:rounded-2xl p-3 2xl:p-3.5 flex flex-col justify-between border border-slate-800/90 shadow-xl h-full min-h-0">
            <!-- Header -->
            <div class="flex items-center justify-between pb-2 border-b border-slate-800 shrink-0">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-400">
                        <!-- Mobil (Car) Icon -->
                        <svg class="w-4 h-4 2xl:w-5 2xl:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 17H3V9a2 2 0 012-2h10l4 4v6h-2M5 17h10M5 12h14"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xs 2xl:text-sm text-white tracking-wide">Transport</h3>
                    </div>
                </div>
            </div>

            @php
                $evalTransportMetric = function($val, $target) {
                    $v = (float) ($val ?? 0);
                    $t = (float) $target;
                    if ($v > $t) {
                        return ['label' => 'Excellent', 'class' => 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30'];
                    } elseif (abs($v - $t) < 0.05) {
                        return ['label' => 'Tercapai', 'class' => 'bg-blue-500/15 text-blue-300 border border-blue-500/30'];
                    } else {
                        return ['label' => 'Belum Tercapai', 'class' => 'bg-rose-500/15 text-rose-300 border border-rose-500/30'];
                    }
                };
                $stBus = $evalTransportMetric($transport->pa_bus ?? 0, 99);
                $stLv = $evalTransportMetric($transport->pa_lv ?? 0, 98);
                $stCov = $evalTransportMetric($transport->coverage_pengantaran ?? 0, 100);
                $stFrBus = $evalTransportMetric($transport->fuel_ratio_bus ?? 0, 3.5);
                $stFrLv = $evalTransportMetric($transport->fuel_ratio_lv ?? 0, 7.0);
            @endphp

            <!-- 3 Donut Charts: PA Bus, PA LV, Coverage Pengantaran -->
            <div class="space-y-2 my-1.5 flex-1 flex flex-col justify-around">
                <!-- 1. PA Bus (Donut Percentage) -->
                <div class="flex items-center gap-2.5 bg-slate-900/80 p-2 2xl:p-2.5 rounded-xl border border-slate-800/80 hover:border-emerald-500/30 transition-all">
                    <!-- Donut Canvas Container -->
                    <div class="relative w-14 h-14 2xl:w-16 2xl:h-16 flex items-center justify-center shrink-0">
                        <canvas id="chart-transport-bus" width="64" height="64"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center text-center pointer-events-none">
                            <span class="text-[10px] 2xl:text-[11px] font-extrabold font-mono-num text-emerald-400">
                                {{ number_format($transport->pa_bus ?? 0, 1) }}%
                            </span>
                        </div>
                    </div>
                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-xs 2xl:text-sm text-white truncate">PA Bus</h4>
                            <span class="text-[9px] px-1.5 py-0.5 rounded-full font-bold {{ $stBus['class'] }}">
                                {{ $stBus['label'] }}
                            </span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-0.5">Physical Availability Bus</p>
                        <div class="flex items-center justify-between text-[10px] text-slate-300 font-mono-num mt-1">
                            <span class="text-slate-400">Target 99%</span>
                            <span>Ready: <strong class="text-emerald-400">{{ $transport->unit_ready_bus ?? 0 }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- 2. PA LV (Donut Percentage) -->
                <div class="flex items-center gap-2.5 bg-slate-900/80 p-2 2xl:p-2.5 rounded-xl border border-slate-800/80 hover:border-cyan-500/30 transition-all">
                    <!-- Donut Canvas Container -->
                    <div class="relative w-14 h-14 2xl:w-16 2xl:h-16 flex items-center justify-center shrink-0">
                        <canvas id="chart-transport-lv" width="64" height="64"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center text-center pointer-events-none">
                            <span class="text-[10px] 2xl:text-[11px] font-extrabold font-mono-num text-cyan-400">
                                {{ number_format($transport->pa_lv ?? 0, 1) }}%
                            </span>
                        </div>
                    </div>
                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-xs 2xl:text-sm text-white truncate">PA LV</h4>
                            <span class="text-[9px] px-1.5 py-0.5 rounded-full font-bold {{ $stLv['class'] }}">
                                {{ $stLv['label'] }}
                            </span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-0.5">Physical Availability Light Vehicle</p>
                        <div class="flex items-center justify-between text-[10px] text-slate-300 font-mono-num mt-1">
                            <span class="text-slate-400">Target 98%</span>
                            <span>Ready: <strong class="text-cyan-400">{{ $transport->unit_ready_lv ?? 0 }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- 3. Coverage Pengantaran (Donut Percentage) -->
                <div class="flex items-center gap-2.5 bg-slate-900/80 p-2 2xl:p-2.5 rounded-xl border border-slate-800/80 hover:border-purple-500/30 transition-all">
                    <!-- Donut Canvas Container -->
                    <div class="relative w-14 h-14 2xl:w-16 2xl:h-16 flex items-center justify-center shrink-0">
                        <canvas id="chart-transport-coverage" width="64" height="64"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center text-center pointer-events-none">
                            <span class="text-[10px] 2xl:text-[11px] font-extrabold font-mono-num text-purple-400">
                                {{ number_format($transport->coverage_pengantaran ?? 0, 1) }}%
                            </span>
                        </div>
                    </div>
                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-xs 2xl:text-sm text-white truncate">Coverage Pengantaran</h4>
                            <span class="text-[9px] px-1.5 py-0.5 rounded-full font-bold {{ $stCov['class'] }}">
                                {{ $stCov['label'] }}
                            </span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-0.5">Cakupan Antar Jemput Karyawan</p>
                        <div class="flex items-center justify-between text-[10px] text-slate-300 font-mono-num mt-1">
                            <span class="text-slate-400">Target 100%</span>
                            <span class="text-purple-300 font-semibold">On Time Trip</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metrik Fuel Rasio (Bus & LV) -->
            <div class="grid grid-cols-2 gap-1.5 my-1.5 shrink-0">
                <div class="bg-slate-900/80 p-2 rounded-xl border border-slate-800/80 hover:border-amber-500/30 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-300">Fuel Rasio Bus</span>
                        <span class="text-[9px] px-1.5 py-0.2 rounded-full font-bold {{ $stFrBus['class'] }}">
                            {{ $stFrBus['label'] }}
                        </span>
                    </div>
                    <div class="flex items-baseline justify-between mt-1">
                        <span class="text-sm 2xl:text-base font-extrabold font-mono-num text-amber-400">
                            {{ number_format($transport->fuel_ratio_bus ?? 0, 2) }}
                            <span class="text-[9px] font-sans font-normal text-slate-400">km/l</span>
                        </span>
                        <span class="text-[9px] text-slate-400 font-mono-num">&ge; 3,5 km/l</span>
                    </div>
                </div>

                <div class="bg-slate-900/80 p-2 rounded-xl border border-slate-800/80 hover:border-cyan-500/30 transition-all">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-300">Fuel Rasio LV</span>
                        <span class="text-[9px] px-1.5 py-0.2 rounded-full font-bold {{ $stFrLv['class'] }}">
                            {{ $stFrLv['label'] }}
                        </span>
                    </div>
                    <div class="flex items-baseline justify-between mt-1">
                        <span class="text-sm 2xl:text-base font-extrabold font-mono-num text-cyan-400">
                            {{ number_format($transport->fuel_ratio_lv ?? 0, 2) }}
                            <span class="text-[9px] font-sans font-normal text-slate-400">km/l</span>
                        </span>
                        <span class="text-[9px] text-slate-400 font-mono-num">&ge; 7 km/l</span>
                    </div>
                </div>
            </div>

            <!-- Ketersediaan Unit & Hot Issue -->
            <div class="space-y-1.5 shrink-0">
                <div class="bg-slate-900/90 rounded-xl p-2 border border-slate-800 text-[10px]">
                    <div class="grid grid-cols-2 gap-1.5 text-[10px]">
                        <div class="bg-slate-800/60 p-1.5 rounded-lg flex items-center justify-between">
                            <span class="text-slate-300">Total LV:</span>
                            <span class="font-bold text-cyan-400 font-mono-num">{{ ($transport->unit_ready_lv ?? 0) + ($transport->unit_breakdown_lv ?? 0) }} Unit</span>
                        </div>
                        <div class="bg-slate-800/60 p-1.5 rounded-lg flex items-center justify-between">
                            <span class="text-slate-300">Total Bus:</span>
                            <span class="font-bold text-emerald-400 font-mono-num">{{ ($transport->unit_ready_bus ?? 0) + ($transport->unit_breakdown_bus ?? 0) }} Unit</span>
                        </div>
                    </div>
                </div>

                <!-- Hot Issue Transport -->
                @if(!empty($transport?->notes))
                    <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-2 text-[10px] 2xl:text-[11px] text-amber-200 flex items-start gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div class="flex-1 min-w-0">
                            <span class="font-bold text-amber-300 block">Hot Issue:</span>
                            <p class="text-slate-200 line-clamp-2 leading-tight">{{ $transport->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- ================= KOLOM 3: BAGIAN 4 MEP & INSTRUMENT ================= -->
        <div id="section-mep" class="glass-card rounded-xl 2xl:rounded-2xl p-3 2xl:p-3.5 flex flex-col justify-between border border-slate-800/90 shadow-xl h-full min-h-0">
            <!-- Header -->
            <div class="flex items-center justify-between pb-2 border-b border-slate-800 shrink-0">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 rounded-lg bg-purple-500/10 border border-purple-500/20 text-purple-400">
                        <!-- Petir (Lightning Bolt) Icon -->
                        <svg class="w-4 h-4 2xl:w-5 2xl:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xs 2xl:text-sm text-white tracking-wide">MEP & Instrument</h3>
                    </div>
                </div>
                <a href="https://umsticket.artyc.id" target="_blank" rel="noopener noreferrer" title="Buka website umsticket.artyc.id" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] 2xl:text-[11px] font-bold bg-purple-500/10 hover:bg-purple-500/20 text-purple-300 hover:text-white border border-purple-500/30 transition-all group">
                    <span>umsticket.artyc.id</span>
                    <svg class="w-3 h-3 text-purple-400 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>

            <!-- Top 3 Status Number Cards -->
            <div class="grid grid-cols-3 gap-1.5 my-2 shrink-0">
                <div class="bg-slate-900/90 p-2 rounded-xl border border-slate-800 text-center">
                    <span class="text-[9px] 2xl:text-[10px] font-semibold text-slate-400 block truncate">Menunggu</span>
                    <span class="text-base 2xl:text-lg font-extrabold text-amber-400 font-mono-num">{{ $mepOrders['totals']['waiting_schedule'] }}</span>
                </div>
                <div class="bg-slate-900/90 p-2 rounded-xl border border-slate-800 text-center">
                    <span class="text-[9px] 2xl:text-[10px] font-semibold text-slate-400 block truncate">OnProgress</span>
                    <span class="text-base 2xl:text-lg font-extrabold text-sky-400 font-mono-num">{{ $mepOrders['totals']['in_progress'] }}</span>
                </div>
                <div class="bg-slate-900/90 p-2 rounded-xl border border-slate-800 text-center">
                    <span class="text-[9px] 2xl:text-[10px] font-semibold text-slate-400 block truncate">Selesai</span>
                    <span class="text-base 2xl:text-lg font-extrabold text-emerald-400 font-mono-num">{{ $mepOrders['totals']['completed'] }}</span>
                </div>
            </div>

            <!-- Matrix Breakdown Table: Minor, Mayor, Project, Belum Diidentifikasi -->
            <div class="bg-slate-900/70 rounded-xl border border-slate-800/80 p-2.5 shrink-0">
                <div class="flex items-center justify-between text-[11px] font-semibold text-slate-400 pb-1.5 border-b border-slate-800 shrink-0">
                    <span>Kategori</span>
                    <div class="grid grid-cols-3 gap-2 text-center w-36 sm:w-40 font-mono-num text-[10px]">
                        <span class="text-amber-400">Tunggu</span>
                        <span class="text-sky-400">Prog</span>
                        <span class="text-emerald-400">Done</span>
                    </div>
                </div>

                <div class="divide-y divide-slate-800/60 font-mono-num text-[11px] 2xl:text-xs">
                    @foreach($mepOrders['rows'] as $typeKey => $row)
                        <div class="py-1 2xl:py-1.5 flex items-center justify-between hover:bg-slate-800/30 px-1 rounded transition-colors">
                            <span class="font-sans font-semibold text-slate-200">{{ $row['label'] }}</span>
                            <div class="grid grid-cols-3 gap-2 text-center w-36 sm:w-40">
                                <span class="bg-amber-500/10 text-amber-300 py-0.5 rounded font-bold">{{ $row['waiting_schedule'] }}</span>
                                <span class="bg-sky-500/10 text-sky-300 py-0.5 rounded font-bold">{{ $row['in_progress'] }}</span>
                                <span class="bg-emerald-500/10 text-emerald-300 py-0.5 rounded font-bold">{{ $row['completed'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Total Tickets Summary (Dinaikkan tepat di bawah baris kategori) -->
                <div class="mt-1.5 pt-1.5 border-t border-slate-800 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400 font-sans">Total Pekerjaan Tiket:</span>
                    <span class="font-extrabold text-white font-mono-num">{{ $mepOrders['totals']['overall'] }} Tiket</span>
                </div>
            </div>

            <!-- Hot Issue MEP & Instrument (Diperlebar & Lebih Luas) -->
            <div class="mt-1.5 bg-amber-500/10 border border-amber-500/30 rounded-xl p-2.5 text-[11px] text-amber-200 flex-1 min-h-0 flex flex-col overflow-hidden">
                <div class="flex items-center gap-1.5 font-bold text-amber-300 text-xs shrink-0 mb-1">
                    <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Hot Issue:</span>
                </div>
                <div class="flex-1 min-h-0 overflow-y-auto pr-1 text-slate-200 leading-relaxed text-xs">
                    {{ $mepHotIssue ?: 'Belum ada Hot Issue pada tanggal ini.' }}
                </div>
            </div>
        </div>

        <!-- ================= KOLOM 4: BAGIAN 5 BUILDING CONST ================= -->
        <div id="section-building" class="glass-card rounded-xl 2xl:rounded-2xl p-3 2xl:p-3.5 flex flex-col justify-between border border-slate-800/90 shadow-xl h-full min-h-0">
            <!-- Header -->
            <div class="flex items-center justify-between pb-2 border-b border-slate-800 shrink-0">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                        <svg class="w-4 h-4 2xl:w-5 2xl:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xs 2xl:text-sm text-white tracking-wide">Building Const</h3>
                    </div>
                </div>
                <a href="https://umsticket.artyc.id" target="_blank" rel="noopener noreferrer" title="Buka website umsticket.artyc.id" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] 2xl:text-[11px] font-bold bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 hover:text-white border border-emerald-500/30 transition-all group">
                    <span>umsticket.artyc.id</span>
                    <svg class="w-3 h-3 text-emerald-400 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>

            <!-- Top 3 Status Number Cards -->
            <div class="grid grid-cols-3 gap-1.5 my-2 shrink-0">
                <div class="bg-slate-900/90 p-2 rounded-xl border border-slate-800 text-center">
                    <span class="text-[9px] 2xl:text-[10px] font-semibold text-slate-400 block truncate">Menunggu</span>
                    <span class="text-base 2xl:text-lg font-extrabold text-amber-400 font-mono-num">{{ $sipilOrders['totals']['waiting_schedule'] }}</span>
                </div>
                <div class="bg-slate-900/90 p-2 rounded-xl border border-slate-800 text-center">
                    <span class="text-[9px] 2xl:text-[10px] font-semibold text-slate-400 block truncate">OnProgress</span>
                    <span class="text-base 2xl:text-lg font-extrabold text-sky-400 font-mono-num">{{ $sipilOrders['totals']['in_progress'] }}</span>
                </div>
                <div class="bg-slate-900/90 p-2 rounded-xl border border-slate-800 text-center">
                    <span class="text-[9px] 2xl:text-[10px] font-semibold text-slate-400 block truncate">Selesai</span>
                    <span class="text-base 2xl:text-lg font-extrabold text-emerald-400 font-mono-num">{{ $sipilOrders['totals']['completed'] }}</span>
                </div>
            </div>

            <!-- Matrix Breakdown Table: Minor, Mayor, Project, Belum Diidentifikasi -->
            <div class="bg-slate-900/70 rounded-xl border border-slate-800/80 p-2.5 shrink-0">
                <div class="flex items-center justify-between text-[11px] font-semibold text-slate-400 pb-1.5 border-b border-slate-800 shrink-0">
                    <span>Kategori</span>
                    <div class="grid grid-cols-3 gap-2 text-center w-36 sm:w-40 font-mono-num text-[10px]">
                        <span class="text-amber-400">Tunggu</span>
                        <span class="text-sky-400">Prog</span>
                        <span class="text-emerald-400">Done</span>
                    </div>
                </div>

                <div class="divide-y divide-slate-800/60 font-mono-num text-[11px] 2xl:text-xs">
                    @foreach($sipilOrders['rows'] as $typeKey => $row)
                        <div class="py-1 2xl:py-1.5 flex items-center justify-between hover:bg-slate-800/30 px-1 rounded transition-colors">
                            <span class="font-sans font-semibold text-slate-200">{{ $row['label'] }}</span>
                            <div class="grid grid-cols-3 gap-2 text-center w-36 sm:w-40">
                                <span class="bg-amber-500/10 text-amber-300 py-0.5 rounded font-bold">{{ $row['waiting_schedule'] }}</span>
                                <span class="bg-sky-500/10 text-sky-300 py-0.5 rounded font-bold">{{ $row['in_progress'] }}</span>
                                <span class="bg-emerald-500/10 text-emerald-300 py-0.5 rounded font-bold">{{ $row['completed'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Total Tickets Summary (Dinaikkan tepat di bawah baris kategori) -->
                <div class="mt-1.5 pt-1.5 border-t border-slate-800 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400 font-sans">Total Pekerjaan Tiket:</span>
                    <span class="font-extrabold text-white font-mono-num">{{ $sipilOrders['totals']['overall'] }} Tiket</span>
                </div>
            </div>

            <!-- Hot Issue Building Const (Diperlebar & Lebih Luas) -->
            <div class="mt-1.5 bg-amber-500/10 border border-amber-500/30 rounded-xl p-2.5 text-[11px] text-amber-200 flex-1 min-h-0 flex flex-col overflow-hidden">
                <div class="flex items-center gap-1.5 font-bold text-amber-300 text-xs shrink-0 mb-1">
                    <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Hot Issue:</span>
                </div>
                <div class="flex-1 min-h-0 overflow-y-auto pr-1 text-slate-200 leading-relaxed text-xs">
                    {{ $sipilHotIssue ?: 'Belum ada Hot Issue pada tanggal ini.' }}
                </div>
            </div>
        </div>

    </section>
</div>
@endsection

@push('scripts')
<script>
    // Smooth Scroll for Mobile Quick Nav
    function scrollToSection(id) {
        const el = document.getElementById(id);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Live Clock WIB
    function updateClock() {
        const now = new Date();
        const options = { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        const timeStr = new Intl.DateTimeFormat('id-ID', options).format(now);
        const clockEl = document.getElementById('live-clock');
        if (clockEl) {
            clockEl.textContent = timeStr + ' WIB';
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Fullscreen Toggle for Big TV screens
    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => console.log(err));
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }

    // Chart.js Donut Diagrams for Realisasi Cost
    const costData = @json($costs);

    document.addEventListener('DOMContentLoaded', () => {
        Object.keys(costData).forEach(key => {
            const item = costData[key];
            const canvas = document.getElementById('chart-' + key);
            if (!canvas) return;

            const percentage = Math.min(100, item.percentage);
            const remainingPercentage = Math.max(0, 100 - percentage);

            let mainColor = '#3b82f6'; // default blue
            if (item.percentage >= 100) {
                mainColor = '#f43f5e'; // rose
            } else if (item.percentage >= 85) {
                mainColor = '#f59e0b'; // amber
            }

            new Chart(canvas, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [percentage, remainingPercentage],
                        backgroundColor: [
                            mainColor,
                            'rgba(30, 41, 59, 0.6)'
                        ],
                        borderWidth: 0,
                        hoverOffset: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (context.dataIndex === 0) {
                                        return ` Terealisasi: ${item.percentage}% (Rp ${new Intl.NumberFormat('id-ID').format(item.actual)})`;
                                    } else {
                                        const isMinus = item.remaining < 0;
                                        return (isMinus ? ' Defisit Budget: -Rp ' : ' Sisa Budget: Rp ') + new Intl.NumberFormat('id-ID').format(Math.abs(item.remaining));
                                    }
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        });

        // Chart.js Donut Diagrams for Transport (PA Bus, PA LV, Coverage Pengantaran)
        const transportCharts = [
            { id: 'chart-transport-bus', val: {{ $transport->pa_bus ?? 0 }}, color: '#10b981' },
            { id: 'chart-transport-lv', val: {{ $transport->pa_lv ?? 0 }}, color: '#06b6d4' },
            { id: 'chart-transport-coverage', val: {{ $transport->coverage_pengantaran ?? 0 }}, color: '#a855f7' }
        ];

        transportCharts.forEach(tc => {
            const canvas = document.getElementById(tc.id);
            if (!canvas) return;

            const val = Math.min(100, Math.max(0, tc.val));
            const rem = Math.max(0, 100 - val);

            new Chart(canvas, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [val, rem],
                        backgroundColor: [
                            tc.color,
                            'rgba(30, 41, 59, 0.6)'
                        ],
                        borderWidth: 0,
                        hoverOffset: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        });
    });

    // Auto-refresh interval (every 60 seconds) with Red/Gray Live indicator update
    let autoRefreshTimer = setInterval(() => {
        const currentDate = document.getElementById('date-selector')?.value;
        const today = new Date().toISOString().split('T')[0];
        // Only auto refresh if viewing today
        if (currentDate === today) {
            fetch(`/api/dashboard-data?date=${currentDate}`)
                .then(res => {
                    if (!res.ok) throw new Error('Network error');
                    return res.json();
                })
                .then(data => {
                    const badge = document.getElementById('live-indicator-badge');
                    const dot = document.getElementById('live-indicator-dot');
                    const text = document.getElementById('live-indicator-text');
                    if (badge && dot && text) {
                        badge.className = 'inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/30';
                        dot.className = 'w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping';
                        text.textContent = 'LIVE';
                    }
                    // Smooth reload if changes detected
                    window.location.reload();
                })
                .catch(err => {
                    console.log('Auto refresh offline / disconnected', err);
                    const badge = document.getElementById('live-indicator-badge');
                    const dot = document.getElementById('live-indicator-dot');
                    const text = document.getElementById('live-indicator-text');
                    if (badge && dot && text) {
                        badge.className = 'inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] font-bold bg-slate-800 text-slate-400 border border-slate-700';
                        dot.className = 'w-1.5 h-1.5 rounded-full bg-slate-500';
                        text.textContent = 'OFFLINE';
                    }
                });
        }
    }, 60000);
</script>
@endpush
