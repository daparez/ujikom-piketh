@extends('layouts.guru.guru')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Guru')

@section('content')
    <!-- Banner Sambutan & Aksi Cepat -->
    <div class="p-5 bg-white border border-slate-200 rounded-xl shadow-xs">
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <h2 class="text-base font-bold text-slate-800">
                    Selamat Datang, {{ auth()->user()->name ?? 'Bapak/Ibu Guru' }}
                </h2>
                <p class="mt-1 text-xs text-slate-500">
                    @if(!empty($kelasWali))
                        Anda terdaftar sebagai Wali Kelas <span class="font-semibold text-sky-700">{{ $kelasWali->nama }}</span>.
                    @else
                        Pantau jadwal mengajar, penugasan kelas pengganti, dan status perizinan Anda di sini.
                    @endif
                </p>
            </div>

            <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap">
                <a href="{{ Route::has('pengajuan-izin.create') ? route('pengajuan-izin.create') : '#' }}"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-semibold text-white bg-sky-600 rounded-lg hover:bg-sky-700 shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Ajukan Izin</span>
                </a>

                <a href="{{ Route::has('pelanggaran.create') ? route('pelanggaran.create') : '#' }}"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-700 bg-slate-100 border border-slate-200 rounded-lg hover:bg-slate-200 transition-colors">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>Catat Pelanggaran</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Penugasan Pengganti Hari Ini (Jika ditugaskan mengisi kelas kosong guru lain) -->
    @if(($penugasanPengganti ?? collect())->isNotEmpty())
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl shadow-xs">
            <div class="flex items-start gap-3">
                <div class="p-2 rounded-lg bg-amber-100 text-amber-700 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-amber-900">Anda Mendapat Penugasan Guru Pengganti Hari Ini</h3>
                    <div class="mt-2 space-y-2">
                        @foreach($penugasanPengganti as $tugas)
                            <div class="p-3 bg-white border border-amber-200 rounded-lg text-xs">
                                <p class="font-semibold text-slate-800">
                                    Kelas {{ $tugas->nama_kelas }} &bull; Jam ke-{{ $tugas->jam_ke }} ({{ $tugas->nama_mapel }})
                                </p>
                                <p class="text-slate-500 mt-0.5">Menggantikan: {{ $tugas->nama_guru_asal }}</p>
                                @if(!empty($tugas->tugas))
                                    <p class="mt-1.5 p-2 bg-slate-50 rounded text-slate-700 border border-slate-100">
                                        <span class="font-semibold">Instruksi/Tugas:</span> {{ $tugas->tugas }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Grid Utama: Jadwal Mengajar Hari Ini & Tugas Kelas Ditinggalkan -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
        <!-- Jadwal Mengajar Hari Ini (2 Kolom) -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs lg:col-span-2 flex flex-col">
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Jadwal Mengajar Hari Ini</h3>
                    <p class="text-xs text-slate-500">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <a href="{{ Route::has('guru.jadwal-saya') ? route('guru.jadwal-saya') : '#' }}"
                   class="text-xs font-medium text-sky-600 hover:text-sky-700">
                    Jadwal Lengkap &rarr;
                </a>
            </div>

            <div class="p-5 flex-1">
                @if(($jadwalHariIni ?? collect())->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($jadwalHariIni as $jadwal)
                            <div class="p-3.5 border border-slate-200 rounded-xl flex items-center justify-between gap-4 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-700 flex flex-col items-center justify-center shrink-0 border border-sky-100">
                                        <span class="text-[10px] uppercase font-semibold">Jam</span>
                                        <span class="text-sm font-bold leading-none">{{ $jadwal->jam_ke }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">{{ $jadwal->nama_mapel }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            Kelas <span class="font-semibold text-slate-700">{{ $jadwal->nama_kelas }}</span>
                                            &bull; {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-lg">
                                    Terjadwal
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center">
                        <p class="text-xs font-medium text-slate-700">Tidak ada jadwal mengajar hari ini</p>
                        <p class="text-xs text-slate-400 mt-0.5">Anda tidak memiliki jam tatap muka pada hari ini.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Tugas Kelas Perlu Diisi & Status Izin Terakhir -->
        <div class="space-y-6">
            
            <!-- Tugas Kelas yang Perlu Diisi (Saat guru berhalangan) -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">Tugas Kelas Perlu Diisi</h3>
                        <p class="text-xs text-slate-500">Kelas Anda yang ditinggalkan</p>
                    </div>
                    <a href="{{ Route::has('guru.tugas-kelas.index') ? route('guru.tugas-kelas.index') : '#' }}"
                       class="text-xs font-medium text-sky-600 hover:text-sky-700">
                        Kelola &rarr;
                    </a>
                </div>
                <div class="p-5 space-y-3">
                    @forelse($tugasKelasPerluDiisi ?? [] as $kosong)
                        <div class="p-3 border border-amber-200 bg-amber-50/40 rounded-xl">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800">{{ $kosong->nama_kelas }} &bull; Jam ke-{{ $kosong->jam_ke }}</span>
                                <span class="text-[11px] text-slate-500">{{ \Carbon\Carbon::parse($kosong->tanggal)->format('d M') }}</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-0.5">{{ $kosong->nama_mapel }}</p>
                            <a href="{{ Route::has('guru.tugas-kelas.index') ? route('guru.tugas-kelas.index') : '#' }}"
                               class="mt-2 inline-block text-xs font-semibold text-sky-600 hover:underline">
                                + Isi Materi / Tugas Mandiri
                            </a>
                        </div>
                    @empty
                        <p class="py-4 text-center text-xs text-slate-400">
                            Tidak ada kelas kosong yang perlu diisi tugas.
                        </p>
                    @endforelse
                </div>
            </div>

            <!-- Status Pengajuan Izin Terakhir -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">Pengajuan Izin Terakhir</h3>
                        <p class="text-xs text-slate-500">Riwayat pengajuan Anda</p>
                    </div>
                    <a href="{{ Route::has('pengajuan-izin.index') ? route('pengajuan-izin.index') : '#' }}"
                       class="text-xs font-medium text-sky-600 hover:text-sky-700">
                        Riwayat &rarr;
                    </a>
                </div>
                <div class="p-5 divide-y divide-slate-100">
                    @forelse($riwayatIzin ?? [] as $izin)
                        <div class="py-2.5 first:pt-0 last:pb-0 flex items-center justify-between gap-2">
                            <div>
                                <p class="text-xs font-semibold text-slate-800 capitalize">{{ $izin->jenis }}</p>
                                <p class="text-[11px] text-slate-500">
                                    {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d M Y') }}
                                </p>
                            </div>
                            <x-badge-status :status="$izin->status" />
                        </div>
                    @empty
                        <p class="py-4 text-center text-xs text-slate-400">
                            Belum ada riwayat pengajuan izin.
                        </p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection