@extends('layouts.admin.admin')

@section('title', 'Tahun Ajaran')
@section('page-title', 'Data Tahun Ajaran')

@section('content')
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <!-- Header & Tombol Tambah -->
        <div class="p-5 border-b border-slate-200 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Daftar Tahun Ajaran & Semester</h2>
                <p class="text-xs text-slate-500">Hanya satu tahun ajaran dan semester yang dapat berstatus aktif pada satu waktu</p>
            </div>
            <a href="{{ route('admin.tahun-ajaran.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Tahun Ajaran</span>
            </a>
        </div>

        <!-- Baris Pencarian -->
        <form method="GET" action="{{ route('admin.tahun-ajaran.index') }}" class="p-4 bg-slate-50/70 border-b border-slate-200">
            <div class="flex flex-col gap-2 sm:flex-row sm:max-w-md">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari tahun ajaran (mis. 2026/2027)..."
                       class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                <button type="submit"
                        class="px-4 py-2 text-xs font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors cursor-pointer">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.tahun-ajaran.index') }}"
                       class="px-3 py-2 text-xs font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-100 text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Tabel Tahun Ajaran -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5">Tahun Ajaran</th>
                        <th class="px-5 py-3.5">Semester</th>
                        <th class="px-5 py-3.5">Rentang Tanggal</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($tahunAjarans as $ta)
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $ta->is_aktif ? 'bg-indigo-50/30' : '' }}">
                            <td class="px-5 py-3.5 font-semibold text-slate-800">
                                {{ $ta->nama }}
                            </td>
                            <td class="px-5 py-3.5 capitalize text-slate-700">
                                {{ $ta->semester }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600">
                                {{ $ta->tanggal_mulai->translatedFormat('d M Y') }} &mdash; {{ $ta->tanggal_selesai->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <x-badge-status :status="$ta->is_aktif ? 'aktif' : 'nonaktif'" />
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if(! $ta->is_aktif)
                                        <form action="{{ route('admin.tahun-ajaran.aktifkan', $ta) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="px-2.5 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors cursor-pointer">
                                                Aktifkan
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('admin.tahun-ajaran.edit', $ta) }}"
                                       class="px-2.5 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </a>

                                    @if(! $ta->is_aktif)
                                        <form action="{{ route('admin.tahun-ajaran.destroy', $ta) }}" method="POST"
                                              onsubmit="return confirm('Hapus tahun ajaran {{ $ta->nama }} ({{ ucfirst($ta->semester) }})?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-2.5 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 rounded-lg hover:bg-rose-100 transition-colors cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-xs text-slate-400">
                                Belum ada data tahun ajaran. Silakan tambahkan tahun ajaran baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($tahunAjarans->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/50">
                {{ $tahunAjarans->links() }}
            </div>
        @endif
    </div>
@endsection