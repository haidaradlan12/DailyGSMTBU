@extends('layouts.admin')

@section('page-title', 'Bagian 4 & 5: Manajemen MEP & Instrument & Building Const')

@section('admin-content')
<div class="space-y-6 max-w-6xl">

    <!-- Header & Action Bar -->
    <div class="glass-card rounded-2xl p-5 border border-slate-800 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-white tracking-tight">Pekerjaan MEP & Instrument & Building Const</h3>
            <p class="text-xs text-slate-400">Monitoring status pekerjaan tiket (Minor, Mayor, Project) dari portal umsticket.artyc.id</p>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- Filter Date -->
            <form method="GET" action="{{ route('admin.work_orders.index') }}" class="flex items-center gap-2 bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <input 
                    type="date" 
                    name="date" 
                    value="{{ $selectedDate }}" 
                    onchange="this.form.submit()" 
                    class="bg-transparent text-white text-xs font-semibold focus:outline-none cursor-pointer"
                >
            </form>

            <!-- Sync Button -->
            <form method="POST" action="{{ route('admin.work_orders.sync') }}">
                @csrf
                <input type="hidden" name="date" value="{{ $selectedDate }}">
                <button type="submit" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs shadow-md shadow-purple-600/30 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin-reverse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Sinkronisasi umsticket</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Status Banner umsticket -->
    <div class="p-4 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between text-xs">
        <div class="flex items-center gap-3">
            <span class="w-2.5 h-2.5 rounded-full {{ ($setting->last_sync_status ?? '') === 'success' ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400' }}"></span>
            <div>
                <span class="text-slate-400">Status Endpoint:</span>
                <span class="font-semibold text-slate-200 ml-1">{{ $setting->endpoint_url ?? 'https://umsticket.artyc.id' }}</span>
                <span class="text-slate-500 ml-2">({{ $setting->last_sync_message ?? 'Siap digunakan' }})</span>
            </div>
        </div>
        <div class="text-slate-400">
            Terakhir dicek: <span class="font-bold text-slate-300">{{ $setting?->last_synced_at?->diffForHumans() ?? 'Belum ada' }}</span>
        </div>
    </div>

    <!-- Form Edit Manual & Simpan Matrix -->
    <form method="POST" action="{{ route('admin.work_orders.update') }}">
        @csrf
        <input type="hidden" name="date" value="{{ $selectedDate }}">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- BAGIAN 4: MEP INSTRUMENT -->
            <div class="glass-card rounded-2xl p-5 border border-slate-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 rounded-xl bg-purple-500/10 text-purple-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-base">Bagian 4: MEP & Instrument</h4>
                            <p class="text-xs text-slate-400">Mechanical, Electrical, Plumbing & Instrument</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($categories as $catKey => $catLabel)
                        @php $val = $orders['mep'][$catKey]; @endphp
                        <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-800 space-y-2">
                            <span class="text-xs font-bold text-purple-300 uppercase tracking-wider block">Kategori: {{ $catLabel }}</span>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-amber-400 mb-1">Menunggu Jadwal</label>
                                    <input 
                                        type="number" 
                                        name="orders[mep][{{ $catKey }}][waiting_schedule]" 
                                        value="{{ old("orders.mep.$catKey.waiting_schedule", $val['waiting_schedule']) }}" 
                                        min="0"
                                        class="w-full px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-amber-400 font-mono-num font-bold text-sm focus:outline-none"
                                    >
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-sky-400 mb-1">OnProgress</label>
                                    <input 
                                        type="number" 
                                        name="orders[mep][{{ $catKey }}][in_progress]" 
                                        value="{{ old("orders.mep.$catKey.in_progress", $val['in_progress']) }}" 
                                        min="0"
                                        class="w-full px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-sky-400 font-mono-num font-bold text-sm focus:outline-none"
                                    >
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-emerald-400 mb-1">Selesai</label>
                                    <input 
                                        type="number" 
                                        name="orders[mep][{{ $catKey }}][completed]" 
                                        value="{{ old("orders.mep.$catKey.completed", $val['completed']) }}" 
                                        min="0"
                                        class="w-full px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-emerald-400 font-mono-num font-bold text-sm focus:outline-none"
                                    >
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Hot Issue MEP & Instrument -->
                    <div class="bg-purple-950/20 p-3.5 rounded-xl border border-purple-500/30 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-purple-300 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>Hot Issue / Remarks MEP & Instrument</span>
                            </label>
                            <span class="text-[10px] text-slate-400">Tampil di dashboard</span>
                        </div>
                        <textarea 
                            name="notes[mep]" 
                            rows="2" 
                            placeholder="Contoh: Perbaikan panel distribusi daya utama mess sedang menunggu penggantian breaker 250A..."
                            class="w-full px-3 py-2 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-xs focus:border-purple-400 focus:outline-none"
                        >{{ old('notes.mep', $mepNote?->hot_issue ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 5: SIPIL -->
            <div class="glass-card rounded-2xl p-5 border border-slate-800 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-base">Bagian 5: Building Const</h4>
                            <p class="text-xs text-slate-400">Pekerjaan Bangunan & Fasilitas Sipil Lapangan</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($categories as $catKey => $catLabel)
                        @php $val = $orders['sipil'][$catKey]; @endphp
                        <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-800 space-y-2">
                            <span class="text-xs font-bold text-emerald-300 uppercase tracking-wider block">Kategori: {{ $catLabel }}</span>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-amber-400 mb-1">Menunggu Jadwal</label>
                                    <input 
                                        type="number" 
                                        name="orders[sipil][{{ $catKey }}][waiting_schedule]" 
                                        value="{{ old("orders.sipil.$catKey.waiting_schedule", $val['waiting_schedule']) }}" 
                                        min="0"
                                        class="w-full px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-amber-400 font-mono-num font-bold text-sm focus:outline-none"
                                    >
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-sky-400 mb-1">OnProgress</label>
                                    <input 
                                        type="number" 
                                        name="orders[sipil][{{ $catKey }}][in_progress]" 
                                        value="{{ old("orders.sipil.$catKey.in_progress", $val['in_progress']) }}" 
                                        min="0"
                                        class="w-full px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-sky-400 font-mono-num font-bold text-sm focus:outline-none"
                                    >
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-emerald-400 mb-1">Selesai</label>
                                    <input 
                                        type="number" 
                                        name="orders[sipil][{{ $catKey }}][completed]" 
                                        value="{{ old("orders.sipil.$catKey.completed", $val['completed']) }}" 
                                        min="0"
                                        class="w-full px-3 py-1.5 rounded-lg bg-slate-800 border border-slate-700 text-emerald-400 font-mono-num font-bold text-sm focus:outline-none"
                                    >
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Hot Issue Building Const -->
                    <div class="bg-emerald-950/20 p-3.5 rounded-xl border border-emerald-500/30 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-emerald-300 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span>Hot Issue / Remarks Building Const</span>
                            </label>
                            <span class="text-[10px] text-slate-400">Tampil di dashboard</span>
                        </div>
                        <textarea 
                            name="notes[sipil]" 
                            rows="2" 
                            placeholder="Contoh: Renovasi atap shelter bus terhambat cuaca hujan lebat, material spandek sudah siap..."
                            class="w-full px-3 py-2 rounded-lg bg-slate-800 border border-slate-700 text-slate-200 text-xs focus:border-emerald-400 focus:outline-none"
                        >{{ old('notes.sipil', $sipilNote?->hot_issue ?? '') }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex items-center justify-end gap-3 pt-4">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white text-xs font-bold shadow-lg shadow-purple-600/30 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Matriks Pekerjaan MEP & Instrument & Building Const</span>
            </button>
        </div>
    </form>

    <!-- Pengaturan Integrasi umsticket.artyc.id -->
    <div class="glass-card rounded-2xl p-5 border border-slate-800 space-y-4">
        <h4 class="text-sm font-bold text-white uppercase tracking-wider">Konfigurasi Sambungan umsticket.artyc.id</h4>
        <form method="POST" action="{{ route('admin.work_orders.settings') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            @csrf
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-300 mb-1">URL Endpoint API</label>
                <input 
                    type="url" 
                    name="endpoint_url" 
                    value="{{ old('endpoint_url', $setting->endpoint_url ?? 'https://umsticket.artyc.id') }}" 
                    required 
                    class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:border-purple-500 focus:outline-none"
                >
            </div>
            <div>
                <button type="submit" class="w-full py-2 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold border border-slate-700 transition-colors">
                    Simpan Konfigurasi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
