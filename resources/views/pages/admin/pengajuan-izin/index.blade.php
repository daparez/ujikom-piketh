@extends('layouts.admin.admin')

@section('title', 'Kelola Pengajuan Izin')
@section('page-title', 'Kelola Pengajuan Izin Guru')

@section('content')
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <!-- Header -->
        <div class="p-5 border-b border-slate-200 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Daftar Pengajuan Izin Tidak Mengajar</h2>
                <p class="text-xs text-slate-500">Verifikasi pengajuan izin, sakit, atau dinas luar dari guru pengajar</p>
            </div>

            @if($jumlahMenunggu > 0)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    {{ $jumlahMenunggu }} Pengajuan Menunggu Verifikasi
                </span>
            @endif
        </div>

        <!-- Baris Filter -->
        <form method="GET" action="{{ route('admin.pengajuan-izin.index') }}" class="p-4 bg-slate-50/70 border-b border-slate-200">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                <div class="sm:col-span-2">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama guru pengaju..."
                           class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div>
                    <select name="jenis"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Jenis Izin</option>
                        <option value="izin" @selected(request('jenis') === 'izin')>Izin Pribadi</option>
                        <option value="sakit" @selected(request('jenis') === 'sakit')>Sakit</option>
                        <option value="dinas_luar" @selected(request('jenis') === 'dinas_luar')>Dinas Luar</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <select name="status"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="menunggu" @selected(request('status') === 'menunggu')>Menunggu</option>
                        <option value="disetujui" @selected(request('status') === 'disetujui')>Disetujui</option>
                        <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
                    </select>

                    <button type="submit"
                            class="px-4 py-2 text-xs font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors cursor-pointer">
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'jenis', 'status']))
                        <a href="{{ route('admin.pengajuan-izin.index') }}"
                           class="px-3 py-2 text-xs font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-100">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Tabel Pengajuan Izin -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5">Guru Pengaju</th>
                        <th class="px-5 py-3.5">Tanggal Izin</th>
                        <th class="px-5 py-3.5">Jenis</th>
                        <th class="px-5 py-3.5">Alasan</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($pengajuans as $item)
                        @php
                            $statusBadge = match($item->status) {
                                'disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'ditolak'   => 'bg-rose-100 text-rose-700 border-rose-200',
                                default     => 'bg-amber-100 text-amber-800 border-amber-200',
                            };
                            $jenisBadge = match($item->jenis) {
                                'sakit'      => 'bg-rose-50 text-rose-700 border-rose-200',
                                'dinas_luar' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                default      => 'bg-sky-50 text-sky-700 border-sky-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $item->status === 'menunggu' ? 'bg-amber-50/20' : '' }}">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-slate-800 text-xs sm:text-sm">{{ $item->guru->name ?? '-' }}</p>
                                <span class="text-[11px] text-slate-400">Diajukan {{ $item->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-700">
                                @if($item->tanggal_mulai->equalTo($item->tanggal_selesai))
                                    <span class="font-medium">{{ $item->tanggal_mulai->translatedFormat('d M Y') }}</span>
                                @else
                                    <span class="font-medium">
                                        {{ $item->tanggal_mulai->translatedFormat('d M') }} &mdash; {{ $item->tanggal_selesai->translatedFormat('d M Y') }}
                                    </span>
                                    <span class="block text-[11px] text-slate-400">
                                        ({{ $item->tanggal_mulai->diffInDays($item->tanggal_selesai) + 1 }} hari)
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize border {{ $jenisBadge }}">
                                    {{ str_replace('_', ' ', $item->jenis) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600 max-w-xs truncate">
                                {{ $item->alasan }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold capitalize border {{ $statusBadge }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.pengajuan-izin.show', $item) }}"
                                       class="px-3 py-1.5 text-xs font-semibold {{ $item->status === 'menunggu' ? 'text-white bg-indigo-600 hover:bg-indigo-700' : 'text-indigo-700 bg-indigo-50 hover:bg-indigo-100' }} rounded-lg transition-colors">
                                        {{ $item->status === 'menunggu' ? 'Tinjau' : 'Detail' }}
                                    </a>

                                    <form action="{{ route('admin.pengajuan-izin.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Hapus riwayat pengajuan izin ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2.5 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 rounded-lg hover:bg-rose-100 transition-colors cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-xs text-slate-400">
                                Tidak ada data pengajuan izin guru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pengajuans->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/50">
                {{ $pengajuans->links() }}
            </div>
        @endif
    </div>
@endsection