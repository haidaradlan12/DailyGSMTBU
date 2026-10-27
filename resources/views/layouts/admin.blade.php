@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col md:flex-row bg-[#090d16]">
    <!-- Sidebar Navigation -->
    <aside class="w-full md:w-64 bg-[#0f172a] border-r border-slate-800 flex flex-col shrink-0">
        <!-- Brand / Header -->
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 via-indigo-600 to-sky-400 flex items-center justify-center font-extrabold text-white shadow-lg shadow-blue-500/20">
                    GS
                </div>
                <div>
                    <h1 class="font-bold text-base tracking-wide text-white">GS MTBU</h1>
                    <p class="text-xs text-slate-400 font-medium">Panel Manajemen Data</p>
                </div>
            </div>
        </div>

        <!-- User Information Card -->
        <div class="p-4 mx-3 my-3 rounded-xl bg-slate-800/50 border border-slate-700/50 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-blue-600/30 border border-blue-500/30 flex items-center justify-center text-blue-400 font-semibold text-sm">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-semibold text-slate-200 truncate">{{ Auth::user()->name }}</p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="inline-block w-2 h-2 rounded-full {{ Auth::user()->isSuperAdmin() ? 'bg-amber-400' : 'bg-emerald-400' }}"></span>
                    <span class="text-[11px] font-medium uppercase tracking-wider {{ Auth::user()->isSuperAdmin() ? 'text-amber-400' : 'text-emerald-400' }}">
                        {{ Auth::user()->isSuperAdmin() ? 'Admin Utama' : 'Operator' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 space-y-1.5 py-2">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-5 h-5 text-current shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Ringkasan Admin</span>
            </a>

            <div class="pt-3 pb-1 px-3 text-[11px] font-semibold tracking-wider text-slate-400 uppercase">
                Modul Monitoring
            </div>

            <a href="{{ route('admin.costs.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.costs.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-5 h-5 text-current shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Bagian 1: Realisasi Cost</span>
            </a>

            <a href="{{ route('admin.dorcat.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.dorcat.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-5 h-5 text-current shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Bagian 2: Dorminoty & Catering</span>
            </a>

            <a href="{{ route('admin.transport.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.transport.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-5 h-5 text-current shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                <span>Bagian 3: Transport</span>
            </a>

            <a href="{{ route('admin.work_orders.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.work_orders.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                <svg class="w-5 h-5 text-current shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Bagian 4 & 5: MEP & Instrument & Building Const</span>
            </a>

            @if(Auth::user()->isSuperAdmin())
                <div class="pt-4 pb-1 px-3 text-[11px] font-semibold tracking-wider text-slate-400 uppercase flex items-center justify-between">
                    <span>Otoritas Super Admin</span>
                    @php $pendingCount = \App\Models\User::where('is_approved', false)->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="bg-rose-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold animate-pulse">{{ $pendingCount }}</span>
                    @endif
                </div>

                <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('admin.users.*') ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : 'text-slate-300 hover:bg-slate-800/80 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-current shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Validasi Pendaftaran</span>
                    </div>
                    @if($pendingCount > 0)
                        <span class="bg-rose-500/80 text-white text-xs px-2 py-0.5 rounded-full font-bold">{{ $pendingCount }}</span>
                    @endif
                </a>
            @endif
        </nav>

        <!-- Bottom Actions -->
        <div class="p-3 border-t border-slate-800 space-y-2">
            <a href="{{ route('dashboard') }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2 px-3 rounded-lg bg-slate-800 hover:bg-slate-700 text-sky-400 text-xs font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Lihat Dashboard Utama</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 w-full py-2 px-3 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 text-xs font-semibold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Navbar -->
        <header class="h-16 bg-[#0f172a]/80 backdrop-blur-md border-b border-slate-800 px-6 flex items-center justify-between sticky top-0 z-40">
            <div>
                <h2 class="text-base font-bold text-white">@yield('page-title', 'Manajemen Dashboard')</h2>
                <p class="text-xs text-slate-400">Monitoring General Services MTBU</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem Aktif
                </span>
            </div>
        </header>

        <!-- Page Body -->
        <main class="flex-1 p-6 md:p-8">
            @yield('admin-content')
        </main>
    </div>
</div>
@endsection
