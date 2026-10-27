@extends('layouts.admin')

@section('page-title', 'Bagian 2: Manajemen Dorminoty & Catering (Hot Issue & Persentase)')

@section('admin-content')
<div class="space-y-6 max-w-6xl">

    <!-- Header & Date Filter Bar -->
    <div class="glass-card rounded-2xl p-5 border border-slate-800 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-white tracking-tight">Manajemen Dorminoty & Catering</h3>
            <p class="text-xs text-slate-400">Pengawasan Isu Kritis (Hot Issues) dan Pembaruan Progres Persentase (%)</p>
        </div>

        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.dorcat.index') }}" class="flex items-center gap-2 bg-slate-900 border border-slate-700 rounded-xl px-3 py-1.5">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-xs text-slate-400 font-medium">Filter Tanggal:</span>
                <input 
                    type="date" 
                    name="date" 
                    value="{{ $selectedDate }}" 
                    onchange="this.form.submit()" 
                    class="bg-transparent text-white text-xs font-semibold focus:outline-none cursor-pointer"
                >
            </form>

            <button onclick="document.getElementById('modal-add-issue').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-md transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Hot Issue</span>
            </button>
        </div>
    </div>

    <!-- Issues List -->
    <div class="space-y-4">
        @forelse($issues as $issue)
            <div class="glass-card rounded-2xl p-5 border border-slate-800 space-y-4">
                <form method="POST" action="{{ route('admin.dorcat.update', $issue) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pb-3 border-b border-slate-800">
                        <div class="flex-1 w-full sm:w-auto">
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase mb-1">Judul Isu</label>
                            <input 
                                type="text" 
                                name="issue_title" 
                                value="{{ old('issue_title', $issue->issue_title) }}" 
                                required
                                class="w-full px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-sm font-semibold focus:outline-none focus:border-amber-500"
                            >
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <!-- Status -->
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-400 uppercase mb-1">Status</label>
                                <select name="status" class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs font-medium focus:outline-none">
                                    <option value="open" {{ $issue->status === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $issue->status === 'in_progress' ? 'selected' : '' }}>On Progress</option>
                                    <option value="resolved" {{ $issue->status === 'resolved' ? 'selected' : '' }}>Resolved (Selesai)</option>
                                </select>
                            </div>
                            <!-- Priority -->
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-400 uppercase mb-1">Prioritas</label>
                                <select name="priority" class="px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs font-medium focus:outline-none">
                                    <option value="normal" {{ $issue->priority === 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="high" {{ $issue->priority === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ $issue->priority === 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2 space-y-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-400 uppercase mb-1">Deskripsi & Rincian Solusi</label>
                                <textarea name="description" rows="2" class="w-full px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-slate-200 text-xs focus:outline-none focus:border-amber-500" placeholder="Deskripsi kendala dan rencana perbaikan...">{{ old('description', $issue->description) }}</textarea>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-400 uppercase mb-1">Nama PIC (Penanggung Jawab)</label>
                                <input 
                                    type="text" 
                                    name="pic_name" 
                                    value="{{ old('pic_name', $issue->pic_name) }}" 
                                    placeholder="Contoh: Budi Santoso"
                                    class="w-full px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none focus:border-amber-500"
                                >
                            </div>

                            <!-- Percentage Slider -->
                            <div>
                                <div class="flex items-center justify-between text-xs mb-1">
                                    <label class="text-[11px] font-semibold text-slate-400 uppercase">Pengisian Progres (%)</label>
                                    <span id="label-pct-{{ $issue->id }}" class="font-bold text-amber-400 font-mono-num">{{ $issue->percentage }}%</span>
                                </div>
                                <input 
                                    type="range" 
                                    name="percentage" 
                                    min="0" 
                                    max="100" 
                                    step="5"
                                    value="{{ $issue->percentage }}" 
                                    oninput="document.getElementById('label-pct-{{ $issue->id }}').textContent = this.value + '%'"
                                    class="w-full accent-amber-500 cursor-pointer"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-800">
                        <span class="text-[11px] text-slate-400">
                            Terakhir diupdate: {{ $issue->updated_at?->diffForHumans() ?? '-' }}
                        </span>
                        <div class="flex items-center gap-2">
                            <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition-colors">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Delete Action -->
                <div class="flex justify-end pt-1">
                    <form method="POST" action="{{ route('admin.dorcat.destroy', $issue) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus isu ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs font-semibold hover:underline">
                            Hapus Isu Ini
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="glass-card rounded-2xl p-12 text-center text-slate-400 space-y-3">
                <svg class="w-12 h-12 text-slate-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="text-sm font-semibold text-slate-300">Belum ada Hot Issue yang dicatat pada tanggal {{ $selectedDate }}</p>
                <button onclick="document.getElementById('modal-add-issue').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs transition-all">
                    + Tambah Isu Sekarang
                </button>
            </div>
        @endforelse
    </div>

    <!-- Modal Tambah Issue Baru -->
    <div id="modal-add-issue" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
        <div class="glass-card bg-[#0f172a] rounded-2xl p-6 border border-slate-700 w-full max-w-lg shadow-2xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                <h3 class="font-bold text-white text-base">Tambah Hot Issue Dorminoty & Catering Baru</h3>
                <button onclick="document.getElementById('modal-add-issue').classList.add('hidden')" class="text-slate-400 hover:text-white text-lg">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.dorcat.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="date" value="{{ $selectedDate }}">

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Judul Isu / Pekerjaan</label>
                    <input type="text" name="issue_title" required placeholder="Contoh: Overhaul Chiller AC Gedung Utama" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-sm focus:border-amber-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Deskripsi Singkat Kendala</label>
                    <textarea name="description" rows="2" placeholder="Rincian kendala yang terjadi..." class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-slate-200 text-xs focus:border-amber-500 focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none">
                            <option value="in_progress">On Progress</option>
                            <option value="open">Open</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Prioritas</label>
                        <select name="priority" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none">
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                            <option value="normal">Normal</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Nama PIC</label>
                        <input type="text" name="pic_name" placeholder="Nama Penanggung Jawab" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-white text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Persentase Awal (%)</label>
                        <input type="number" name="percentage" min="0" max="100" value="0" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-700 text-amber-400 font-mono-num font-bold text-xs focus:outline-none">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modal-add-issue').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-semibold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold shadow-md">Simpan Isu</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
