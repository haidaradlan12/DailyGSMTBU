@extends('layouts.app')

@section('title', 'Pendaftaran Petugas | Monitoring GS MTBU')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-6">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-700 via-indigo-600 to-cyan-400 p-0.5 items-center justify-center shadow-xl shadow-blue-500/20 mb-4">
                <div class="w-full h-full bg-[#0b1120] rounded-[14px] flex items-center justify-center font-black text-white text-xl">
                    GS
                </div>
            </div>
            <h2 class="text-2xl font-black tracking-tight text-white uppercase">Registrasi Petugas</h2>
            <p class="text-xs text-slate-400 mt-1">Daftarkan akun untuk update data Monitoring GS MTBU</p>
        </div>

        <!-- Register Card -->
        <div class="glass-card rounded-2xl p-6 sm:p-8 border border-slate-800 shadow-2xl relative overflow-hidden">
            <!-- Critical Information Alert -->
            <div class="mb-5 p-3 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs flex items-start gap-2.5">
                <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div class="leading-relaxed">
                    <strong>Penting:</strong> Setelah submit pendaftaran, akun Anda berstatus <em>Pending</em> dan wajib divalidasi oleh <strong>Admin Utama</strong> sebelum Anda dapat login.
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Nama Lengkap
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus
                        placeholder="Contoh: Ahmad Rizki"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                    >
                </div>

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
                        placeholder="ahmad@gsmtbu.com"
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
                        placeholder="Minimal 8 karakter"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                        Konfirmasi Kata Sandi
                    </label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation" 
                        required 
                        placeholder="Ulangi kata sandi"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-900/90 border border-slate-700/80 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                    >
                </div>

                <button type="submit" class="w-full mt-2 py-2.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm shadow-lg shadow-blue-600/30 transition-all">
                    Daftarkan Akun
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-800 text-center text-xs text-slate-400">
                Sudah memiliki akun terverifikasi? 
                <a href="{{ route('login') }}" class="font-bold text-blue-400 hover:text-blue-300 hover:underline ml-1">
                    Masuk di sini
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
