@extends('layouts.admin')

@section('page-title', 'Otoritas Super Admin: Validasi & Approval Pengguna')

@section('admin-content')
<div class="space-y-8 max-w-6xl">

    <!-- TABEL 1: Pendaftaran Menunggu Validasi (Pending Approval) -->
    <div class="glass-card rounded-2xl p-6 border border-slate-800 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white tracking-tight">Pendaftaran Menunggu Validasi</h3>
                    <p class="text-xs text-slate-400">Pengguna di bawah ini belum bisa login sebelum disetujui oleh Admin Utama</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $pendingUsers->isNotEmpty() ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-slate-800 text-slate-400' }}">
                {{ $pendingUsers->count() }} Menunggu
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="text-[11px] font-bold text-slate-400 uppercase bg-slate-900/80 border-b border-slate-800">
                    <tr>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Alamat Email</th>
                        <th class="py-3 px-4">Waktu Daftar</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Tindakan Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($pendingUsers as $user)
                        <tr class="hover:bg-slate-900/40 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-white">
                                {{ $user->name }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-300 font-mono-num">
                                {{ $user->email }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-400">
                                {{ $user->created_at->diffForHumans() }}
                                <span class="text-[10px] block text-slate-500">{{ $user->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-300 border border-amber-500/30">
                                    Pending Approval
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Approve Button -->
                                    <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md transition-all flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span>Setujui</span>
                                        </button>
                                    </form>

                                    <!-- Reject Button -->
                                    <form method="POST" action="{{ route('admin.users.reject', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menolak dan menghapus pendaftaran ini?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 font-semibold text-xs border border-rose-500/20 transition-all">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-500">
                                Tidak ada pendaftaran akun baru yang menunggu validasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABEL 2: Pengguna Aktif & Pengaturan Role -->
    <div class="glass-card rounded-2xl p-6 border border-slate-800 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="p-2.5 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-white tracking-tight">Daftar Pengguna Terverifikasi</h3>
                    <p class="text-xs text-slate-400">Pengguna yang memiliki hak akses masuk ke panel administrasi</p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300">
                {{ $approvedUsers->count() }} Pengguna
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="text-[11px] font-bold text-slate-400 uppercase bg-slate-900/80 border-b border-slate-800">
                    <tr>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4">Role Akses</th>
                        <th class="py-3 px-4">Tanggal Disetujui</th>
                        <th class="py-3 px-4 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @foreach($approvedUsers as $user)
                        <tr class="hover:bg-slate-900/40 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-white">
                                <div class="flex items-center gap-2">
                                    <span>{{ $user->name }}</span>
                                    @if($user->id === Auth::id())
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-500/20 text-blue-300 font-bold">(Anda)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-300 font-mono-num">
                                {{ $user->email }}
                            </td>
                            <td class="py-3.5 px-4">
                                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="inline-flex items-center gap-2">
                                    @csrf
                                    <select 
                                        name="role" 
                                        onchange="this.form.submit()" 
                                        class="px-2.5 py-1 rounded-lg bg-slate-900 border border-slate-700 text-xs font-bold focus:outline-none {{ $user->role === 'super_admin' ? 'text-amber-400' : 'text-slate-300' }}"
                                        {{ $user->id === Auth::id() ? 'disabled' : '' }}
                                    >
                                        <option value="operator" {{ $user->role === 'operator' ? 'selected' : '' }}>Operator</option>
                                        <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Admin Utama</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-slate-400">
                                {{ $user->approved_at ? $user->approved_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if($user->id !== Auth::id())
                                    <form method="POST" action="{{ route('admin.users.reject', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin mencabut akses dan menghapus pengguna ini?')">
                                        @csrf
                                        <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs font-semibold hover:underline">
                                            Hapus Pengguna
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-slate-500 italic">Akun Utama</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
