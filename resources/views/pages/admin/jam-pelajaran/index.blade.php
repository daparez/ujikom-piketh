@extends('layouts.admin.admin')

@section('title', 'Jam Pelajaran')
@section('page-title', 'Data Jam Pelajaran')

@section('content')
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <!-- Header & Tombol Tambah -->
        <div class="p-5 border-b border-slate-200 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Daftar Slot Jam Pelajaran & Istirahat</h2>
                <p class="text-xs text-slate-500">Slot yang ditandai istirahat tidak akan dihitung sebagai kelas kosong saat guru berhalangan</p>
            </div>
            <a href="{{ route('admin.jam-pelajaran.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Jam Pelajaran</span>
            </a>
        </div>

        <!-- Tabel Jam Pelajaran -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5 w-32">Urutan</th>
                        <th class="px-5 py-3.5">Rentang Waktu</th>
                        <th class="px-5 py-3.5">Durasi</th>
                        <th class="px-5 py-3.5">Jenis Slot</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($jamPelajarans as $jam)
                        @php
                            $mulai = \Carbon\Carbon::parse($jam->jam_mulai);
                            $selesai = \Carbon\Carbon::parse($jam->jam_selesai);
                            $durasi = $mulai->diffInMinutes($selesai);
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $jam->is_istirahat ? 'bg-amber-50/40' : '' }}">
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                    Jam ke-{{ $jam->jam_ke }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-xs sm:text-sm font-semibold text-slate-800">
                                {{ $mulai->format('H:i') }} &mdash; {{ $selesai->format('H:i') }} WIB
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-500">
                                {{ $durasi }} menit
                            </td>
                            <td class="px-5 py-3.5">
                                @if($jam->is_istirahat)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                        Jam Istirahat
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-700 border border-sky-200">
                                        Jam Pelajaran (KBM)
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.jam-pelajaran.edit', $jam) }}"
                                       class="px-2.5 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.jam-pelajaran.destroy', $jam) }}" method="POST"
                                          onsubmit="return confirm('Hapus slot Jam ke-{{ $jam->jam_ke }}?')">
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
                                Belum ada slot jam pelajaran. Silakan tambahkan urutan jam pelajaran sekolah.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection