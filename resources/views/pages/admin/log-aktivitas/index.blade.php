@extends('layouts.admin.admin')

@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas Sistem')

@section('content')
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <!-- Header & Tombol Bersihkan Log Lama -->
        <div class="p-5 border-b border-slate-200 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Riwayat Audit & Aktivitas Pengguna</h2>
                <p class="text-xs text-slate-500">Rekam jejak perubahan data master, verifikasi izin, dan operasional piket</p>
            </div>

            <form action="{{ route('admin.log-aktivitas.bersihkan') }}" method="POST"
                  onsubmit="return confirm('Hapus semua riwayat log aktivitas yang berusia lebih dari 30 hari?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-rose-700 bg-rose-50 border border-rose-200 rounded-lg hover:bg-rose-100 transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Bersihkan Log &gt; 30 Hari</span>
                </button>
            </form>
        </div>

        <!-- Baris Filter -->
        <form method="GET" action="{{ route('admin.log-aktivitas.index') }}" class="p-4 bg-slate-50/70 border-b border-slate-200">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-5">
                <div class="sm:col-span-2">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama pengguna atau keterangan aktivitas..."
                           class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div>
                    <select name="modul"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Modul</option>
                        @foreach($daftarModul as $modul)
                            <option value="{{ $modul }}" @selected(request('modul') === $modul)>{{ $modul }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="aksi"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Aksi</option>
                        <option value="create" @selected(request('aksi') === 'create')>Tambah (Create)</option>
                        <option value="update" @selected(request('aksi') === 'update')>Ubah (Update)</option>
                        <option value="delete" @selected(request('aksi') === 'delete')>Hapus (Delete)</option>
                        <option value="approve" @selected(request('aksi') === 'approve')>Setujui (Approve)</option>
                        <option value="reject" @selected(request('aksi') === 'reject')>Tolak (Reject)</option>
                        <option value="login" @selected(request('aksi') === 'login')>Login</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <input type="date"
                           name="tanggal"
                           value="{{ request('tanggal') }}"
                           class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">

                    <button type="submit"
                            class="px-4 py-2 text-xs font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors cursor-pointer">
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'modul', 'aksi', 'tanggal']))
                        <a href="{{ route('admin.log-aktivitas.index') }}"
                           class="px-3 py-2 text-xs font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-100">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Tabel Log Aktivitas -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5 w-44">Waktu</th>
                        <th class="px-5 py-3.5">Pengguna</th>
                        <th class="px-5 py-3.5 w-36">Modul</th>
                        <th class="px-5 py-3.5 w-28">Aksi</th>
                        <th class="px-5 py-3.5">Deskripsi Aktivitas</th>
                        <th class="px-5 py-3.5 w-32 text-right">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                    @forelse($logs as $log)
                        @php
                            $aksiBadge = match($log->aksi) {
                                'create', 'approve' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'update'            => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                'delete', 'reject'  => 'bg-rose-100 text-rose-700 border-rose-200',
                                default             => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5 text-xs text-slate-600">
                                <span class="font-medium text-slate-800 block">{{ $log->created_at->translatedFormat('d M Y') }}</span>
                                <span class="font-mono text-[11px] text-slate-400">{{ $log->created_at->format('H:i:s') }} WIB</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-slate-800 text-xs sm:text-sm">{{ $log->user->name ?? 'Sistem / Tamu' }}</p>
                                @if($log->user)
                                    <span class="text-[11px] text-slate-400 capitalize">{{ $log->user->role->value ?? $log->user->role }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs font-medium text-slate-700">
                                {{ $log->modul }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold uppercase border {{ $aksiBadge }}">
                                    {{ $log->aksi }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-700">
                                {{ $log->deskripsi }}
                            </td>
                            <td class="px-5 py-3.5 text-right font-mono text-xs text-slate-400">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-xs text-slate-400">
                                Belum ada catatan log aktivitas sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
@endsection