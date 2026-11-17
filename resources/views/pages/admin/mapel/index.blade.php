@extends('layouts.admin.admin')

@section('title', 'Mata Pelajaran')
@section('page-title', 'Data Mata Pelajaran')

@section('content')
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <!-- Header & Tombol Tambah -->
        <div class="p-5 border-b border-slate-200 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Daftar Mata Pelajaran</h2>
                <p class="text-xs text-slate-500">Kelola kode dan nama mata pelajaran yang diajarkan di sekolah</p>
            </div>
            <a href="{{ route('admin.mapel.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Mata Pelajaran</span>
            </a>
        </div>

        <!-- Baris Pencarian -->
        <form method="GET" action="{{ route('admin.mapel.index') }}" class="p-4 bg-slate-50/70 border-b border-slate-200">
            <div class="flex flex-col gap-2 sm:flex-row sm:max-w-md">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari kode atau nama mata pelajaran..."
                       class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                <button type="submit"
                        class="px-4 py-2 text-xs font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors cursor-pointer">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.mapel.index') }}"
                       class="px-3 py-2 text-xs font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-100 text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Tabel Mata Pelajaran -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5 w-16">No</th>
                        <th class="px-5 py-3.5 w-40">Kode Mapel</th>
                        <th class="px-5 py-3.5">Nama Mata Pelajaran</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($mapels as $index => $mapel)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5 text-xs text-slate-500">
                                {{ $mapels->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md font-mono text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                                    {{ $mapel->kode }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-slate-800">
                                {{ $mapel->nama }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.mapel.edit', $mapel) }}"
                                       class="px-2.5 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.mapel.destroy', $mapel) }}" method="POST"
                                          onsubmit="return confirm('Hapus mata pelajaran {{ $mapel->nama }} ({{ $mapel->kode }})?')">
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
                            <td colspan="4" class="px-5 py-10 text-center text-xs text-slate-400">
                                Data mata pelajaran tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($mapels->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/50">
                {{ $mapels->links() }}
            </div>
        @endif
    </div>
@endsection