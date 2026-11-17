@extends('layouts.admin.admin')

@section('title', 'Kelola Kelas')
@section('page-title', 'Data Kelas & Wali Kelas')

@section('content')
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <!-- Header & Tombol Tambah -->
        <div class="p-5 border-b border-slate-200 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Daftar Rombongan Belajar (Kelas)</h2>
                <p class="text-xs text-slate-500">Kelola tingkat kelas dan penugasan wali kelas per tahun ajaran</p>
            </div>
            <a href="{{ route('admin.kelas.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Kelas</span>
            </a>
        </div>

        <!-- Baris Filter & Pencarian -->
        <form method="GET" action="{{ route('admin.kelas.index') }}" class="p-4 bg-slate-50/70 border-b border-slate-200">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                <div>
                    <select name="tahun_ajaran_id"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Tahun Ajaran</option>
                        @foreach($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" @selected((string) $selectedTahunId === (string) $ta->id)>
                                {{ $ta->nama }} ({{ ucfirst($ta->semester) }}) {{ $ta->is_aktif ? '★' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="tingkat"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Tingkat</option>
                        <option value="10" @selected(request('tingkat') === '10')>Kelas 10 (X)</option>
                        <option value="11" @selected(request('tingkat') === '11')>Kelas 11 (XI)</option>
                        <option value="12" @selected(request('tingkat') === '12')>Kelas 12 (XII)</option>
                    </select>
                </div>

                <div class="sm:col-span-2 flex gap-2">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama kelas (mis. XII RPL 1)..."
                           class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">

                    <button type="submit"
                            class="px-4 py-2 text-xs font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors cursor-pointer">
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'tingkat', 'tahun_ajaran_id']))
                        <a href="{{ route('admin.kelas.index') }}"
                           class="px-3 py-2 text-xs font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-100">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Tabel Data Kelas -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5 w-24">Tingkat</th>
                        <th class="px-5 py-3.5">Nama Kelas</th>
                        <th class="px-5 py-3.5">Tahun Ajaran</th>
                        <th class="px-5 py-3.5">Wali Kelas</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($kelasList as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    Kelas {{ $item->tingkat }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800">
                                {{ $item->nama }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600">
                                {{ $item->tahunAjaran->nama ?? '-' }} ({{ ucfirst($item->tahunAjaran->semester ?? '-') }})
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-700">
                                @if($item->waliKelas)
                                    <span class="font-medium text-slate-800">{{ $item->waliKelas->name }}</span>
                                @else
                                    <span class="text-slate-400 italic">Belum ditentukan</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.kelas.edit', $item) }}"
                                       class="px-2.5 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.kelas.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Hapus kelas {{ $item->nama }}?')">
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
                            <td colspan="5" class="px-5 py-10 text-center text-xs text-slate-400">
                                Belum ada data kelas pada filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($kelasList->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/50">
                {{ $kelasList->links() }}
            </div>
        @endif
    </div>
@endsection