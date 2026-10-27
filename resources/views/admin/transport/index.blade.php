@extends('layouts.admin')

@section('page-title', 'Bagian 3: Manajemen Transport')

@section('admin-content')
<div class="space-y-6 max-w-5xl">

    <!-- Header & Date Filter Bar -->
    <div class="glass-card rounded-2xl p-5 border border-slate-800 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-white tracking-tight">Input Metrik Transport</h3>
            <p class="text-xs text-slate-400">Pengisian manual Physical Availability (PA) dan Asset Turn Around (ATR) LV & Bus</p>
        </div>

        <form method="GET" action="{{ route('admin.transport.index') }}" class="flex items-center gap-2 bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5">
            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="text-xs text-slate-400 font-medium">Pilih Tanggal:</span>
            <input 
                type="date" 
                name="date" 
                value="{{ $selectedDate }}" 
                onchange="this.form.submit()" 
                class="bg-transparent text-white text-xs font-semibold focus:outline-none cursor-pointer"
            >
        </form>
    </div>

    <!-- Form Input Transport -->
    <form method="POST" action="{{ route('admin.transport.store') }}" class="glass-card rounded-2xl p-6 border border-slate-800 space-y-6">
        @csrf
        <input type="hidden" name="date" value="{{ $selectedDate }}">

        <!-- 3 Metrik Utama -->
        <div>
            <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                <span>Metrik Utama Transport (Donut Chart)</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- 1. PA Bus -->
                <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-200 uppercase">PA Bus (%)</label>
                        <span class="text-[11px] text-emerald-400 font-bold">Target 99%</span>
                    </div>
                    <div class="relative">
                        <input 
                            type="number" 
                            step="0.01" 
                            name="pa_bus" 
                            id="pa_bus"
                            value="{{ old('pa_bus', $transport->pa_bus ?? 96.00) }}" 
                            required 
                            min="0" 
                            max="100" 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-600 text-emerald-400 font-mono-num font-extrabold text-lg focus:border-emerald-400 focus:outline-none"
                        >
                        <span class="absolute right-4 top-2.5 text-slate-400 font-bold">%</span>
                    </div>
                    <p class="text-[11px] text-slate-400">Physical Availability Bus Karyawan (Target 99%)</p>
                </div>

                <!-- 2. PA LV -->
                <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-200 uppercase">PA LV (%)</label>
                        <span class="text-[11px] text-cyan-400 font-bold">Target 98%</span>
                    </div>
                    <div class="relative">
                        <input 
                            type="number" 
                            step="0.01" 
                            name="pa_lv" 
                            id="pa_lv"
                            value="{{ old('pa_lv', $transport->pa_lv ?? 94.50) }}" 
                            required 
                            min="0" 
                            max="100" 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-600 text-cyan-400 font-mono-num font-extrabold text-lg focus:border-cyan-400 focus:outline-none"
                        >
                        <span class="absolute right-4 top-2.5 text-slate-400 font-bold">%</span>
                    </div>
                    <p class="text-[11px] text-slate-400">Physical Availability Light Vehicle (Target 98%)</p>
                </div>

                <!-- 3. Coverage Pengantaran -->
                <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-200 uppercase">Coverage Pengantaran (%)</label>
                        <span class="text-[11px] text-purple-400 font-bold">Target 100%</span>
                    </div>
                    <div class="relative">
                        <input 
                            type="number" 
                            step="0.01" 
                            name="coverage_pengantaran" 
                            id="coverage_pengantaran"
                            value="{{ old('coverage_pengantaran', $transport->coverage_pengantaran ?? 98.00) }}" 
                            required 
                            min="0" 
                            max="100" 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-600 text-purple-400 font-mono-num font-extrabold text-lg focus:border-purple-400 focus:outline-none"
                        >
                        <span class="absolute right-4 top-2.5 text-slate-400 font-bold">%</span>
                    </div>
                    <p class="text-[11px] text-slate-400">Persentase Cakupan Pengantaran & Penjemputan (Target 100%)</p>
                </div>
            </div>
        </div>

        <!-- Metrik Fuel Rasio (Bus & LV) -->
        <div class="pt-4 border-t border-slate-800">
            <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                <span>Fuel Rasio Operasional (Bahan Bakar)</span>
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- 1. Fuel Rasio Bus -->
                <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-200 uppercase">Fuel Rasio Bus (km/l)</label>
                        <span class="text-[11px] text-amber-400 font-bold">Target &ge; 3,5 km/l</span>
                    </div>
                    <div class="relative">
                        <input 
                            type="number" 
                            step="0.01" 
                            name="fuel_ratio_bus" 
                            id="fuel_ratio_bus"
                            value="{{ old('fuel_ratio_bus', $transport->fuel_ratio_bus ?? 3.60) }}" 
                            min="0" 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-600 text-amber-400 font-mono-num font-extrabold text-lg focus:border-amber-400 focus:outline-none"
                        >
                        <span class="absolute right-4 top-2.5 text-slate-400 font-bold">km/l</span>
                    </div>
                    <p class="text-[11px] text-slate-400">Efisiensi konsumsi bahan bakar Bus Karyawan (Target &ge; 3,5 km/l)</p>
                </div>

                <!-- 2. Fuel Rasio LV -->
                <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-700/80 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-200 uppercase">Fuel Rasio LV (km/l)</label>
                        <span class="text-[11px] text-cyan-400 font-bold">Target &ge; 7 km/l</span>
                    </div>
                    <div class="relative">
                        <input 
                            type="number" 
                            step="0.01" 
                            name="fuel_ratio_lv" 
                            id="fuel_ratio_lv"
                            value="{{ old('fuel_ratio_lv', $transport->fuel_ratio_lv ?? 7.20) }}" 
                            min="0" 
                            class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-600 text-cyan-400 font-mono-num font-extrabold text-lg focus:border-cyan-400 focus:outline-none"
                        >
                        <span class="absolute right-4 top-2.5 text-slate-400 font-bold">km/l</span>
                    </div>
                    <p class="text-[11px] text-slate-400">Efisiensi konsumsi bahan bakar Light Vehicle (Target &ge; 7 km/l)</p>
                </div>
            </div>
        </div>

        <!-- Rincian Jumlah Unit (Ready vs Breakdown) -->
        <div class="pt-4 border-t border-slate-800">
            <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                <span>Rincian Armada Unit (Jumlah Kendaraan)</span>
            </h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">LV Ready (Unit)</label>
                    <input 
                        type="number" 
                        name="unit_ready_lv" 
                        value="{{ old('unit_ready_lv', $transport->unit_ready_lv ?? 0) }}" 
                        min="0"
                        class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-emerald-400 font-mono-num font-bold text-sm focus:border-emerald-500 focus:outline-none"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">LV Breakdown (Unit)</label>
                    <input 
                        type="number" 
                        name="unit_breakdown_lv" 
                        value="{{ old('unit_breakdown_lv', $transport->unit_breakdown_lv ?? 0) }}" 
                        min="0"
                        class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-rose-400 font-mono-num font-bold text-sm focus:border-rose-500 focus:outline-none"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Bus Ready (Unit)</label>
                    <input 
                        type="number" 
                        name="unit_ready_bus" 
                        value="{{ old('unit_ready_bus', $transport->unit_ready_bus ?? 0) }}" 
                        min="0"
                        class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-emerald-400 font-mono-num font-bold text-sm focus:border-emerald-500 focus:outline-none"
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Bus Breakdown (Unit)</label>
                    <input 
                        type="number" 
                        name="unit_breakdown_bus" 
                        value="{{ old('unit_breakdown_bus', $transport->unit_breakdown_bus ?? 0) }}" 
                        min="0"
                        class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-rose-400 font-mono-num font-bold text-sm focus:border-rose-500 focus:outline-none"
                    >
                </div>
            </div>
        </div>

        <!-- Hot Issue / Remarks Transport -->
        <div class="pt-4 border-t border-slate-800 space-y-1.5">
            <div class="flex items-center justify-between">
                <label class="block text-xs font-bold text-amber-300 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Hot Issue / Remarks Transport</span>
                </label>
                <span class="text-[10px] text-slate-400">Tampil di dashboard</span>
            </div>
            <textarea 
                name="notes" 
                rows="2" 
                placeholder="Contoh: LV-04 sedang masuk workshop jadwal ganti transmisi; Bus-02 kendala kompresor AC sedang perbaikan..."
                class="w-full px-4 py-2 rounded-xl bg-slate-900 border border-slate-700 text-slate-200 text-xs focus:border-cyan-400 focus:outline-none"
            >{{ old('notes', $transport->notes ?? '') }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white text-xs font-bold shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Metrik Transport</span>
            </button>
        </div>
    </form>

</div>
@endsection
