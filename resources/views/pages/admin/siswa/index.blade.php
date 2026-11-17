@extends('layouts.admin.admin')

@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa')

@section('content')
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <!-- Header & Tombol Tambah -->
        <div class="p-5 border-b border-slate-200 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Daftar Peserta Didik</h2>
                <p class="text-xs text-slate-500">Kelola biodata siswa, penempatan kelas, dan status keaktifan</p>
            </div>
            <a href="{{ route('admin.siswa.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Siswa</span>
            </a>
        </div>

        <!-- Baris Filter & Pencarian -->
        <form method="GET" action="{{ route('admin.siswa.index') }}" class="p-4 bg-slate-50/70 border-b border-slate-200">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                <div class="sm:col-span-2">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama siswa, NIS, atau NISN..."
                           class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div>
                    <select name="kelas_id"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" @selected((string) request('kelas_id') === (string) $kelas->id)>
                                {{ $kelas->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <select name="status"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                        <option value="lulus" @selected(request('status') === 'lulus')>Lulus</option>
                        <option value="pindah" @selected(request('status') === 'pindah')>Pindah</option>
                        <option value="keluar" @selected(request('status') === 'keluar')>Keluar</option>
                    </select>

                    <button type="submit"
                            class="px-4 py-2 text-xs font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors cursor-pointer">
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'kelas_id', 'status']))
                        <a href="{{ route('admin.siswa.index') }}"
                           class="px-3 py-2 text-xs font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-100">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Tabel Data Siswa -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5">NIS / NISN</th>
                        <th class="px-5 py-3.5">Nama Siswa</th>
                        <th class="px-5 py-3.5">L/P</th>
                        <th class="px-5 py-3.5">Kelas</th>
                        <th class="px-5 py-3.5">No. HP Ortu</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($siswas as $siswa)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-xs text-slate-700">
                                <span class="font-semibold">{{ $siswa->nis }}</span>
                                @if($siswa->nisn)
                                    <span class="block text-[11px] text-slate-400">NISN: {{ $siswa->nisn }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800 text-xs sm:text-sm">
                                {{ $siswa->nama }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600">
                                {{ $siswa->jenis_kelamin }}
                            </td>
                            <td class="px-5 py-3.5 text-xs">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $siswa->kelas->nama ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600">
                                {{ $siswa->no_hp_ortu ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <x-badge-status :status="$siswa->status" />
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.siswa.edit', $siswa) }}"
                                       class="px-2.5 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.siswa.destroy', $siswa) }}" method="POST"
                                          onsubmit="return confirm('Hapus data siswa {{ $siswa->nama }}?')">
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
                            <td colspan="7" class="px-5 py-10 text-center text-xs text-slate-400">
                                Data siswa tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($siswas->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/50">
                {{ $siswas->links() }}
            </div>
        @endif
    </div>
@endsection