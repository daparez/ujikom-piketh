@extends('layouts.admin.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Administrator')

@section('content')
    <div class="space-y-6">
        <!-- Banner Sambutan & Info Tahun Ajaran Aktif -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 capitalize">
                        {{ $hariIni }}, {{ $today->translatedFormat('d F Y') }}
                    </span>
                    @if($tahunAktif)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            TA {{ $tahunAktif->nama }} &bull; Semester {{ ucfirst($tahunAktif->semester) }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            Belum Ada Tahun Ajaran Aktif
                        </span>
                    @endif
                </div>
                <h2 class="text-base sm:text-lg font-bold text-slate-800 mt-2">
                    Selamat Datang, {{ auth()->user()->name }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Ringkasan operasional piket harian, kehadiran guru, kedisiplinan siswa, dan pengajuan izin.
                </p>
            </div>

            <!-- Tombol Akses Cepat -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.monitoring.piket-hari-ini') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs transition-colors">
                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                    <span>Pantau Piket Hari Ini</span>
                </a>
                <a href="{{ route('admin.laporan.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                    <span>Rekap & Laporan</span>
                </a>
            </div>
        </div>

        <!-- Baris 1: 4 Kartu KPI Operasional Hari Ini -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <!-- Kehadiran Guru -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kehadiran Guru</p>
                    <span class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <p class="text-2xl font-bold text-slate-800">
                        {{ $operasionalStats['guru_hadir'] }}
                        <span class="text-xs font-normal text-slate-400">/ {{ $operasionalStats['total_jadwal'] }} sesi</span>
                    </p>
                    <span class="text-xs font-bold text-emerald-600">{{ $operasionalStats['persen_kehadiran'] }}%</span>
                </div>
            </div>

            <!-- Kelas Kosong Belum Terisi -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kelas Kosong Aktif</p>
                    <span class="p-2 rounded-lg {{ $operasionalStats['kelas_kosong_aktif'] > 0 ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 text-slate-500' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <p class="text-2xl font-bold {{ $operasionalStats['kelas_kosong_aktif'] > 0 ? 'text-rose-600' : 'text-slate-800' }}">
                        {{ $operasionalStats['kelas_kosong_aktif'] }}
                        <span class="text-xs font-normal text-slate-400">kelas</span>
                    </p>
                    <a href="{{ route('admin.monitoring.piket-hari-ini') }}" class="text-xs font-medium text-indigo-600 hover:underline">
                        Cek detail &rarr;
                    </a>
                </div>
            </div>

            <!-- Siswa Terlambat & Izin -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Siswa Telat & Izin</p>
                    <span class="p-2 rounded-lg bg-amber-50 text-amber-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <p class="text-2xl font-bold text-slate-800">
                        {{ $operasionalStats['siswa_terlambat'] }}
                        <span class="text-xs font-normal text-slate-400">terlambat</span>
                    </p>
                    <span class="text-xs font-medium text-slate-600">{{ $operasionalStats['siswa_izin_keluar'] }} izin keluar</span>
                </div>
            </div>

            <!-- Pengajuan Izin Menunggu Verifikasi -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengajuan Izin Guru</p>
                    <span class="p-2 rounded-lg {{ $masterStats['izin_menunggu'] > 0 ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-50 text-slate-500' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </span>
                </div>
                <div class="mt-3 flex items-baseline justify-between">
                    <p class="text-2xl font-bold {{ $masterStats['izin_menunggu'] > 0 ? 'text-indigo-600' : 'text-slate-800' }}">
                        {{ $masterStats['izin_menunggu'] }}
                        <span class="text-xs font-normal text-slate-400">menunggu</span>
                    </p>
                    <a href="{{ route('admin.pengajuan-izin.index') }}" class="text-xs font-medium text-indigo-600 hover:underline">
                        Verifikasi &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- Baris 2: Petugas Piket Hari Ini & Ringkasan Data Master -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Petugas Piket Bertugas Hari Ini -->
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">Petugas Piket Bertugas Hari Ini</h3>
                        <p class="text-xs text-slate-500 capitalize">Jadwal piket hari {{ $hariIni }}</p>
                    </div>
                    <a href="{{ route('admin.jadwal-piket.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                        Atur Jadwal
                    </a>
                </div>
                <div class="p-5">
                    @if($petugasHariIni->isNotEmpty())
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            @foreach($petugasHariIni as $piket)
                                <div class="p-3.5 rounded-xl border {{ $piket->is_koordinator ? 'bg-indigo-50/50 border-indigo-200' : 'bg-slate-50/70 border-slate-200' }} flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs sm:text-sm font-semibold text-slate-800">{{ $piket->petugas->name ?? '-' }}</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $piket->keterangan ?? 'Pos Piket Umum' }}</p>
                                    </div>
                                    @if($piket->is_koordinator)
                                        <span class="shrink-0 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-indigo-600 text-white">
                                            Koordinator
                                        </span>
                                    @else
                                        <span class="shrink-0 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-white text-slate-600 border border-slate-200">
                                            Anggota
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 text-center text-xs text-slate-400">
                            Belum ada petugas piket yang dijadwalkan untuk hari {{ ucfirst($hariIni) }}.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ringkasan Data Master -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-5 flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Ringkasan Data Master</h3>
                    <p class="text-xs text-slate-500">Total entitas aktif di dalam sistem</p>

                    <div class="mt-4 space-y-3 text-xs">
                        <div class="flex items-center justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-600">Guru & Petugas Aktif</span>
                            <span class="font-bold text-slate-800">{{ $masterStats['total_guru'] }} Orang</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-600">Siswa Aktif</span>
                            <span class="font-bold text-slate-800">{{ $masterStats['total_siswa'] }} Siswa</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-100">
                            <span class="text-slate-600">Rombongan Belajar</span>
                            <span class="font-bold text-slate-800">{{ $masterStats['total_kelas'] }} Kelas</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-slate-600">Kunjungan Tamu Hari Ini</span>
                            <span class="font-bold text-slate-800">{{ $operasionalStats['tamu_hari_ini'] }} Tamu</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.pengaturan-sekolah.index') }}"
                   class="mt-4 w-full text-center py-2 text-xs font-semibold text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                    Pengaturan Sekolah
                </a>
            </div>
        </div>

        <!-- Baris 3: Pengajuan Izin Guru Terbaru & Log Aktivitas Sistem -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Pengajuan Izin Guru Terbaru -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">Pengajuan Izin Guru Terbaru</h3>
                        <p class="text-xs text-slate-500">Permohonan izin tidak mengajar yang perlu ditinjau</p>
                    </div>
                    <a href="{{ route('admin.pengajuan-izin.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                        Semua &rarr;
                    </a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($pengajuanIzinTerbaru as $izin)
                        @php
                            $badge = match($izin->status) {
                                'disetujui' => 'bg-emerald-100 text-emerald-700',
                                'ditolak'   => 'bg-rose-100 text-rose-700',
                                default     => 'bg-amber-100 text-amber-800',
                            };
                        @endphp
                        <div class="px-5 py-3.5 flex items-center justify-between gap-3 hover:bg-slate-50/70">
                            <div>
                                <p class="text-xs font-semibold text-slate-800">{{ $izin->guru->name ?? '-' }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5 capitalize">
                                    {{ str_replace('_', ' ', $izin->jenis) }} &bull; {{ $izin->tanggal_mulai->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold capitalize {{ $badge }}">
                                    {{ $izin->status }}
                                </span>
                                <a href="{{ route('admin.pengajuan-izin.show', $izin) }}"
                                   class="text-xs font-semibold text-indigo-600 hover:underline">
                                    Tinjau
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-xs text-slate-400">
                            Belum ada pengajuan izin guru.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Log Aktivitas Sistem Terbaru -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">Log Aktivitas Terbaru</h3>
                        <p class="text-xs text-slate-500">Jejak perubahan data terkini di sistem</p>
                    </div>
                    <a href="{{ route('admin.log-aktivitas.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                        Semua &rarr;
                    </a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($logTerbaru as $log)
                        <div class="px-5 py-3.5 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs text-slate-700">
                                    <span class="font-semibold text-slate-800">{{ $log->user->name ?? 'Sistem' }}</span>
                                    &mdash; {{ $log->deskripsi }}
                                </p>
                                <span class="text-[11px] text-slate-400">{{ $log->modul }} &bull; {{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <span class="shrink-0 px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-slate-100 text-slate-600">
                                {{ $log->aksi }}
                            </span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-xs text-slate-400">
                            Belum ada aktivitas yang tercatat.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection