@extends('layouts.admin')

@section('page-title', 'Ringkasan Administrasi')

@section('admin-content')
<div class="space-y-6 max-w-7xl">

    <!-- Welcome & Quick Status Banner -->
    <div class="glass-card rounded-2xl p-6 border border-slate-800 relative overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="space-y-1">
            <h3 class="text-xl font-black text-white tracking-tight">Halo, {{ Auth::user()->name }}!</h3>
            <p class="text-sm text-slate-400">
                Panel Manajemen Data Monitoring General Services Muara Tiga Besar Utara (MTBU).
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard') }}" target="_blank" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-md shadow-blue-600/30 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>Lihat Display Utama</span>
            </a>
        </div>
    </div>

    <!-- Super Admin Pending Approval Alert -->
    @if(Auth::user()->isSuperAdmin() && $pendingUsers->isNotEmpty())
        <div class="p-5 rounded-2xl bg-amber-500/10 border border-amber-500/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-300">Ada {{ $pendingUsers->count() }} Pendaftaran Pengguna Menunggu Validasi!</h4>
                    <p class="text-xs text-amber-400/80">Pengguna baru tidak dapat login sampai Anda menyetujui akun mereka.</p>
                </div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-md transition-all shrink-0">
                Buka Menu Approval
            </a>
        </div>
    @endif

    <!-- Modul Quick Status Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Bagian 1: Cost -->
        <a href="{{ route('admin.costs.index') }}" class="glass-card rounded-2xl p-5 border border-slate-800 hover:border-blue-500/50 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Bagian 1</span>
                <span class="p-2 rounded-xl bg-blue-500/10 text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <h4 class="text-base font-bold text-white group-hover:text-blue-400 transition-colors">Tabel Realisasi Cost</h4>
            <p class="text-xs text-slate-400 mt-1">5 Kategori: Tiket, NPE, Office, Mess, Building</p>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                <span class="text-slate-400">Data Hari Ini:</span>
                <span class="font-bold {{ $costsCount >= 5 ? 'text-emerald-400' : 'text-amber-400' }}">
                    {{ $costsCount }} / 5 Kategori
                </span>
            </div>
        </a>

        <!-- Bagian 2: Dorminoty & Catering -->
        <a href="{{ route('admin.dorcat.index') }}" class="glass-card rounded-2xl p-5 border border-slate-800 hover:border-amber-500/50 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Bagian 2</span>
                <span class="p-2 rounded-xl bg-amber-500/10 text-amber-400 group-hover:bg-amber-600 group-hover:text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
            </div>
            <h4 class="text-base font-bold text-white group-hover:text-amber-400 transition-colors">Dorminoty & Catering</h4>
            <p class="text-xs text-slate-400 mt-1">Tracking Isu & Pengisian Persentase (%)</p>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                <span class="text-slate-400">Isu Terdaftar:</span>
                <span class="font-bold text-amber-400 font-mono-num">{{ $dorcatCount }} Isu</span>
            </div>
        </a>

        <!-- Bagian 3: Transport -->
        <a href="{{ route('admin.transport.index') }}" class="glass-card rounded-2xl p-5 border border-slate-800 hover:border-cyan-500/50 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Bagian 3</span>
                <span class="p-2 rounded-xl bg-cyan-500/10 text-cyan-400 group-hover:bg-cyan-600 group-hover:text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </span>
            </div>
            <h4 class="text-base font-bold text-white group-hover:text-cyan-400 transition-colors">Transport (Manual)</h4>
            <p class="text-xs text-slate-400 mt-1">PA LV, ATR LV, dan PA Bus Karyawan</p>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                <span class="text-slate-400">Status Hari Ini:</span>
                <span class="font-bold {{ $transport ? 'text-emerald-400' : 'text-slate-400' }}">
                    {{ $transport ? 'PA LV: ' . $transport->pa_lv . '%' : 'Belum Diinput' }}
                </span>
            </div>
        </a>

        <!-- Bagian 4 & 5: MEP & Instrument & Building Const -->
        <a href="{{ route('admin.work_orders.index') }}" class="glass-card rounded-2xl p-5 border border-slate-800 hover:border-purple-500/50 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Bagian 4 & 5</span>
                <span class="p-2 rounded-xl bg-purple-500/10 text-purple-400 group-hover:bg-purple-600 group-hover:text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </span>
            </div>
            <h4 class="text-base font-bold text-white group-hover:text-purple-400 transition-colors">MEP & Instrument & Building Const</h4>
            <p class="text-xs text-slate-400 mt-1">Sinkronisasi umsticket.artyc.id</p>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                <span class="text-slate-400">Sumber Portal:</span>
                <span class="font-bold text-purple-400 truncate max-w-[120px]">umsticket.artyc.id</span>
            </div>
        </a>
    </div>

</div>
@endsection
