@extends('layouts.admin.admin')

@section('title', 'Jadwal Piket')
@section('page-title', 'Data Jadwal Petugas Piket')

@section('content')
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <!-- Header & Tombol Tambah -->
        <div class="p-5 border-b border-slate-200 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Daftar Jadwal Piket Harian</h2>
                <p class="text-xs text-slate-500">Atur pembagian guru/petugas piket harian dan koordinator piket sekolah</p>
            </div>
            <a href="{{ route('admin.jadwal-piket.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Petugas Piket</span>
            </a>
        </div>

        <!-- Baris Filter -->
        <form method="GET" action="{{ route('admin.jadwal-piket.index') }}" class="p-4 bg-slate-50/70 border-b border-slate-200">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                <div>
                    <select name="tahun_ajaran_id"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        @foreach($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" @selected((string) $selectedTahunId === (string) $ta->id)>
                                {{ $ta->nama }} ({{ ucfirst($ta->semester) }}) {{ $ta->is_aktif ? '★' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="hari"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Hari</option>
                        @foreach($hariList as $key => $label)
                            <option value="{{ $key }}" @selected(request('hari') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="petugas_id"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Petugas / Guru</option>
                        @foreach($petugasList as $petugas)
                            <option value="{{ $petugas->id }}" @selected((string) request('petugas_id') === (string) $petugas->id)>
                                {{ $petugas->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="w-full px-4 py-2 text-xs font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors cursor-pointer">
                        Filter
                    </button>

                    @if(request()->hasAny(['hari', 'petugas_id', 'tahun_ajaran_id']))
                        <a href="{{ route('admin.jadwal-piket.index') }}"
                           class="px-3 py-2 text-xs font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-100">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Tabel Jadwal Piket -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5 w-32">Hari</th>
                        <th class="px-5 py-3.5">Nama Petugas / Guru</th>
                        <th class="px-5 py-3.5 w-44">Peran Piket</th>
                        <th class="px-5 py-3.5">Keterangan / Area Tugas</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($jadwalPikets as $piket)
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $piket->is_koordinator ? 'bg-emerald-50/30' : '' }}">
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold capitalize bg-slate-100 text-slate-800 border border-slate-200">
                                    {{ $piket->hari }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-slate-800 text-xs sm:text-sm">{{ $piket->petugas->name ?? '-' }}</p>
                                <span class="text-[11px] text-slate-400">
                                    {{ $piket->petugas->nip ? 'NIP. ' . $piket->petugas->nip : ($piket->petugas->email ?? '') }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($piket->is_koordinator)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Koordinator Piket
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-700 border border-sky-200">
                                        Anggota Piket
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600">
                                {{ $piket->keterangan ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.jadwal-piket.edit', $piket) }}"
                                       class="px-2.5 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.jadwal-piket.destroy', $piket) }}" method="POST"
                                          onsubmit="return confirm('Hapus jadwal piket {{ $piket->petugas->name }} pada hari {{ ucfirst($piket->hari) }}?')">
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
                                Belum ada jadwal piket pada filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($jadwalPikets->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/50">
                {{ $jadwalPikets->links() }}
            </div>
        @endif
    </div>
@endsection