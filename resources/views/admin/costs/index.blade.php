@extends('layouts.admin')

@section('page-title', 'Bagian 1: Manajemen Realisasi Cost')

@section('admin-content')
<div class="space-y-6 max-w-6xl">

    <!-- Header & Date Filter Bar -->
    <div class="glass-card rounded-2xl p-5 border border-slate-800 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-white tracking-tight">Pembaruan Data Cost</h3>
            <p class="text-xs text-slate-400">Kelola angka Plan dan Realisasi Aktual untuk 5 Kategori Operasional</p>
        </div>

        <form method="GET" action="{{ route('admin.costs.index') }}" class="flex items-center gap-2 bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5">
            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
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

    <!-- Bulk Update Form -->
    <form method="POST" action="{{ route('admin.costs.store') }}">
        @csrf
        <input type="hidden" name="date" value="{{ $selectedDate }}">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($categories as $key => $label)
                @php
                    $cost = $costs[$key];
                    $plan = $cost['plan_amount'];
                    $actual = $cost['actual_amount'];
                    $pct = $plan > 0 ? round(($actual / $plan) * 100, 1) : 0;
                @endphp
                <div class="glass-card rounded-2xl p-5 border border-slate-800 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            <h4 class="font-bold text-white text-sm">{{ $label }}</h4>
                        </div>
                        <span id="pct-badge-{{ $key }}" class="text-xs font-mono-num font-bold px-2 py-0.5 rounded-full {{ $pct >= 100 ? 'bg-rose-500/20 text-rose-300' : ($pct >= 85 ? 'bg-amber-500/20 text-amber-300' : 'bg-blue-500/20 text-blue-300') }}">
                            {{ $pct }}%
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Budget Plan (Rp)</label>
                        <input 
                            type="number" 
                            step="any"
                            name="costs[{{ $key }}][plan_amount]" 
                            id="plan-{{ $key }}"
                            value="{{ old("costs.$key.plan_amount", $cost['plan_amount']) }}" 
                            oninput="updatePercentage('{{ $key }}')"
                            required 
                            min="0"
                            class="w-full px-3.5 py-2 rounded-xl bg-slate-900/90 border border-slate-700 text-white font-mono-num text-sm focus:border-blue-500 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Realisasi Aktual (Rp)</label>
                        <input 
                            type="number" 
                            step="any"
                            name="costs[{{ $key }}][actual_amount]" 
                            id="actual-{{ $key }}"
                            value="{{ old("costs.$key.actual_amount", $cost['actual_amount']) }}" 
                            oninput="updatePercentage('{{ $key }}')"
                            required 
                            min="0"
                            class="w-full px-3.5 py-2 rounded-xl bg-slate-900/90 border border-slate-700 text-cyan-400 font-mono-num text-sm focus:border-blue-500 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-1">Catatan Tambahan (Opsional)</label>
                        <input 
                            type="text" 
                            name="costs[{{ $key }}][notes]" 
                            value="{{ old("costs.$key.notes", $cost['notes']) }}" 
                            placeholder="Keterangan alokasi pengeluaran..."
                            class="w-full px-3.5 py-2 rounded-xl bg-slate-900/90 border border-slate-700 text-slate-300 text-xs focus:border-blue-500 focus:outline-none"
                        >
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex items-center justify-end gap-3">
            <a href="{{ route('admin.costs.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition-colors">
                Batal / Reset
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Semua Perubahan Cost</span>
            </button>
        </div>
    </form>

</div>

<script>
    function updatePercentage(key) {
        const planInput = document.getElementById('plan-' + key);
        const actualInput = document.getElementById('actual-' + key);
        const badge = document.getElementById('pct-badge-' + key);

        const plan = parseFloat(planInput.value) || 0;
        const actual = parseFloat(actualInput.value) || 0;

        let pct = plan > 0 ? ((actual / plan) * 100).toFixed(1) : 0;
        badge.textContent = pct + '%';

        if (pct >= 100) {
            badge.className = 'text-xs font-mono-num font-bold px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-300';
        } else if (pct >= 85) {
            badge.className = 'text-xs font-mono-num font-bold px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-300';
        } else {
            badge.className = 'text-xs font-mono-num font-bold px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-300';
        }
    }
</script>
@endsection
