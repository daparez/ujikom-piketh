@extends('layouts.admin.admin')

@section('title', 'Jenis Pelanggaran')
@section('page-title', 'Data Jenis Pelanggaran & Poin')

@section('content')
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <!-- Header & Tombol Tambah -->
        <div class="p-5 border-b border-slate-200 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Daftar Tata Tertib & Bobot Poin Pelanggaran</h2>
                <p class="text-xs text-slate-500">Kelola kategori pelanggaran dan bobot poin untuk akumulasi kedisiplinan siswa</p>
            </div>
            <a href="{{ route('admin.jenis-pelanggaran.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Jenis Pelanggaran</span>
            </a>
        </div>

        <!-- Baris Filter & Pencarian -->
        <form method="GET" action="{{ route('admin.jenis-pelanggaran.index') }}" class="p-4 bg-slate-50/70 border-b border-slate-200">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <div class="sm:col-span-2">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari kode (mis. P-001) atau nama pelanggaran..."
                           class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div class="flex gap-2">
                    <select name="kategori"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Kategori</option>
                        <option value="ringan" @selected(request('kategori') === 'ringan')>Ringan</option>
                        <option value="sedang" @selected(request('kategori') === 'sedang')>Sedang</option>
                        <option value="berat" @selected(request('kategori') === 'berat')>Berat</option>
                    </select>

                    <button type="submit"
                            class="px-4 py-2 text-xs font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors cursor-pointer">
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'kategori']))
                        <a href="{{ route('admin.jenis-pelanggaran.index') }}"
                           class="px-3 py-2 text-xs font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-100">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Tabel Jenis Pelanggaran -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5 w-28">Kode</th>
                        <th class="px-5 py-3.5">Jenis Pelanggaran</th>
                        <th class="px-5 py-3.5 w-32">Kategori</th>
                        <th class="px-5 py-3.5 w-28 text-center">Bobot Poin</th>
                        <th class="px-5 py-3.5 w-28">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($jenisPelanggarans as $item)
                        @php
                            $kategoriBadge = match($item->kategori) {
                                'berat' => 'bg-rose-100 text-rose-700 border-rose-200',
                                'sedang' => 'bg-amber-100 text-amber-800 border-amber-200',
                                default => 'bg-sky-100 text-sky-700 border-sky-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md font-mono text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                                    {{ $item->kode }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-slate-800">
                                {{ $item->nama }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize border {{ $kategoriBadge }}">
                                    {{ $item->kategori }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-800">
                                    +{{ $item->poin }} Poin
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <x-badge-status :status="$item->is_aktif ? 'aktif' : 'nonaktif'" />
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.jenis-pelanggaran.edit', $item) }}"
                                       class="px-2.5 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.jenis-pelanggaran.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Hapus jenis pelanggaran {{ $item->nama }} ({{ $item->kode }})?')">
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
                                Data jenis pelanggaran tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($jenisPelanggarans->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/50">
                {{ $jenisPelanggarans->links() }}
            </div>
        @endif
    </div>
@endsection