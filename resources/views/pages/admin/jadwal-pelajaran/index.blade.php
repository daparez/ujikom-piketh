@extends('layouts.admin.admin')

@section('title', 'Jadwal Pelajaran')
@section('page-title', 'Data Jadwal Pelajaran')

@section('content')
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <!-- Header & Tombol Tambah -->
        <div class="p-5 border-b border-slate-200 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Daftar Jadwal Pelajaran (KBM)</h2>
                <p class="text-xs text-slate-500">Jadwal ini menjadi acuan presensi kehadiran guru dan deteksi kelas kosong otomatis</p>
            </div>
            <a href="{{ route('admin.jadwal-pelajaran.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Jadwal</span>
            </a>
        </div>

        <!-- Baris Filter -->
        <form method="GET" action="{{ route('admin.jadwal-pelajaran.index') }}" class="p-4 bg-slate-50/70 border-b border-slate-200">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-5">
                <div>
                    <select name="tahun_ajaran_id"
                            class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        @foreach($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" @selected((string) $selectedTahunId === (string) $ta->id)>
                                {{ $ta->nama }} ({{ ucfirst($ta->semester) }}) {{ $ta->is_aktif ? '★' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="hari"
                            class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Hari</option>
                        @foreach($hariList as $key => $label)
                            <option value="{{ $key }}" @selected(request('hari') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="kelas_id"
                            class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" @selected((string) request('kelas_id') === (string) $kelas->id)>
                                {{ $kelas->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <select name="guru_id"
                            class="w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Guru</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" @selected((string) request('guru_id') === (string) $guru->id)>
                                {{ $guru->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="w-full px-4 py-2 text-xs font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors cursor-pointer">
                        Filter
                    </button>

                    @if(request()->hasAny(['hari', 'kelas_id', 'guru_id', 'tahun_ajaran_id']))
                        <a href="{{ route('admin.jadwal-pelajaran.index') }}"
                           class="px-3 py-2 text-xs font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-100">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Tabel Jadwal Pelajaran -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5">Hari & Jam</th>
                        <th class="px-5 py-3.5">Kelas</th>
                        <th class="px-5 py-3.5">Mata Pelajaran</th>
                        <th class="px-5 py-3.5">Guru Pengajar</th>
                        <th class="px-5 py-3.5">Ruangan</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($jadwals as $jadwal)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="font-semibold text-slate-800 capitalize text-xs sm:text-sm">
                                    {{ $jadwal->hari }}
                                </span>
                                <span class="block text-xs text-slate-500 font-mono mt-0.5">
                                    Jam ke-{{ $jadwal->jamPelajaran->jam_ke ?? '-' }}
                                    @if($jadwal->jamPelajaran)
                                        ({{ \Carbon\Carbon::parse($jadwal->jamPelajaran->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jamPelajaran->jam_selesai)->format('H:i') }})
                                    @endif
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $jadwal->kelas->nama ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-slate-800 text-xs sm:text-sm">{{ $jadwal->mataPelajaran->nama ?? '-' }}</p>
                                <span class="text-[11px] font-mono text-slate-400">{{ $jadwal->mataPelajaran->kode ?? '' }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-xs sm:text-sm text-slate-700">
                                {{ $jadwal->guru->name ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600">
                                {{ $jadwal->ruangan ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.jadwal-pelajaran.edit', $jadwal) }}"
                                       class="px-2.5 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.jadwal-pelajaran.destroy', $jadwal) }}" method="POST"
                                          onsubmit="return confirm('Hapus jadwal {{ $jadwal->mataPelajaran->nama }} kelas {{ $jadwal->kelas->nama }}?')">
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
                                Belum ada jadwal pelajaran pada filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($jadwals->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/50">
                {{ $jadwals->links() }}
            </div>
        @endif
    </div>
@endsection