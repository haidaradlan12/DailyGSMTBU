@extends('layouts.app')

@section('title', 'Login Petugas | Monitoring GS MTBU')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-700 via-indigo-600 to-cyan-400 p-0.5 items-center justify-center shadow-xl shadow-blue-500/20 mb-4">
                <div class="w-full h-full bg-[#0b1120] rounded-[14px] flex items-center justify-center font-black text-white text-xl">
                    GS
                </div>
            </div>
            <h2 class="text-2xl font-black tracking-tight text-white uppercase">Monitoring GS MTBU</h2>
            <p class="text-xs text-slate-400 mt-1">Masuk ke Panel Manajemen Pembaruan Data</p>
        </div>

        <!-- Login Card -->
        <div class="glass-card rounded-2xl p-6 sm:p-8 border border-slate-800 shadow-2xl relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email" 
                        autofocus
                        placeholder="nama@gsmtbu.com"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Kata Sandi
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required 
                        placeholder="••••••••"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                    >
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-400 hover:text-slate-200">
                        <input type="checkbox" name="remember" class="rounded bg-slate-900 border-slate-700 text-blue-600 focus:ring-0">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm shadow-lg shadow-blue-600/30 transition-all">
                    Masuk ke Sistem
                </button>
            </form>

            <!-- Admin Notice -->
            <div class="mt-6 pt-5 border-t border-slate-800 text-xs text-slate-400 space-y-2">
                <div class="p-3 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-300 text-[11px] leading-relaxed">
                    <strong>Pemberitahuan Sistem:</strong> Semua registrasi akun baru wajib divalidasi dan disetujui oleh <strong>Admin Utama</strong> sebelum dapat digunakan untuk login.
                </div>

                <div class="flex items-center justify-between pt-2">
                    <span class="text-slate-400">Belum punya akun?</span>
                    <a href="{{ route('register') }}" class="font-bold text-blue-400 hover:text-blue-300 hover:underline">
                        Daftar Akun Baru
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali ke Dashboard Utama</span>
            </a>
        </div>
    </div>
</div>
@endsection
