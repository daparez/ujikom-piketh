@extends('layouts.petugas.petugas')

@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard Piket Hari Ini')

@section('content')
    <!-- Banner Status Lembar Piket & Tombol Pintas Aksi Utama (Mobile-First) -->
    <div class="p-5 bg-white border border-slate-200 rounded-xl shadow-xs">
        <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
            <div>
                <div class="flex items-center gap-2.5">
                    <h2 class="text-base font-bold text-slate-800">
                        Lembar Piket: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </h2>
                    <x-badge-status :status="$piketHariIni->status ?? 'draft'" />
                </div>
                <p class="mt-1 text-xs text-slate-500">
                    Petugas Bertugas: <span class="font-semibold text-slate-700">{{ $piketHariIni->petugas->name ?? (auth()->user()->name ?? 'Belum Ditentukan') }}</span>
                </p>
            </div>

            <!-- Tombol Pintas Aksi Utama -->
            <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap">
                <a href="{{ Route::has('piket.hari-ini') ? route('piket.hari-ini') : '#' }}"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span>Isi Lembar Piket</span>
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

        <!-- Progres Pengisian Kehadiran Guru -->
        <div class="mt-5 pt-4 border-t border-slate-100">
            <div class="flex items-center justify-between text-xs mb-1.5">
                <span class="font-medium text-slate-600">Progres Pencatatan Kehadiran Guru Mengajar</span>
                <span class="font-bold text-slate-800">{{ $stats['guru_tercatat'] ?? 0 }} / {{ $stats['total_guru_jadwal'] ?? 0 }} Guru ({{ $stats['persen_progres'] ?? 0 }}%)</span>
            </div>
            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full transition-all duration-300" style="width: {{ $stats['persen_progres'] ?? 0 }}%"></div>
            </div>
        </div>
    </div>

    <!-- 4 Kartu Ringkasan Operasional Hari Ini -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-5 bg-white border border-slate-200 rounded-xl shadow-xs">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Guru Tidak Hadir</p>
            <p class="mt-2 text-3xl font-bold text-slate-800">{{ $stats['guru_tidak_hadir'] ?? 0 }}</p>
            <p class="mt-2 text-xs text-slate-500">Izin, sakit, dinas, atau alpa</p>
        </div>

        <div class="p-5 bg-white border border-slate-200 rounded-xl shadow-xs">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Siswa Tidak Hadir</p>
            <p class="mt-2 text-3xl font-bold text-slate-800">{{ $stats['siswa_tidak_hadir'] ?? 0 }}</p>
            <p class="mt-2 text-xs text-slate-500">Tercatat hari ini</p>
        </div>

        <div class="p-5 bg-white border border-slate-200 rounded-xl shadow-xs">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Izin Menunggu</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">{{ $stats['izin_menunggu'] ?? 0 }}</p>
            <p class="mt-2 text-xs text-slate-500">Perlu segera divalidasi</p>
        </div>

        <div class="p-5 bg-white border border-slate-200 rounded-xl shadow-xs">
            <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Kelas Kosong</p>
            <p class="mt-2 text-3xl font-bold text-rose-600">{{ $stats['kelas_kosong_belum'] ?? 0 }}</p>
            <p class="mt-2 text-xs text-slate-500">Belum ada guru pengganti</p>
        </div>
    </div>

    <!-- 2 Kolom: Kelas Kosong Perlu Penugasan & Izin Menunggu Validasi -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        
        <!-- Daftar Kelas Kosong yang Perlu Penugasan -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs flex flex-col">
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Kelas Kosong Perlu Penugasan</h3>
                    <p class="text-xs text-slate-500">Jam pelajaran hari ini yang gurunya berhalangan</p>
                </div>
                <a href="{{ Route::has('piket.hari-ini') ? route('piket.hari-ini') . '#kelas-kosong' : '#' }}"
                   class="text-xs font-medium text-emerald-600 hover:text-emerald-700">
                    Kelola Semua &rarr;
                </a>
            </div>

            <div class="p-5 flex-1 space-y-3">
                @forelse($kelasKosongList ?? [] as $item)
                    <div class="p-3.5 border border-rose-200 bg-rose-50/40 rounded-xl flex items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 text-xs font-bold bg-rose-100 text-rose-700 rounded">
                                    {{ $item->nama_kelas ?? 'Kelas' }}
                                </span>
                                <span class="text-xs font-semibold text-slate-700">
                                    Jam ke-{{ $item->jam_ke ?? '-' }} ({{ $item->nama_mapel ?? 'Mapel' }})
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                Guru Asal: <span class="font-medium text-slate-700">{{ $item->nama_guru_asal ?? '-' }}</span>
                            </p>
                        </div>
                        <a href="{{ Route::has('piket.hari-ini') ? route('piket.hari-ini') : '#' }}"
                           class="shrink-0 px-3 py-1.5 text-xs font-semibold text-white bg-rose-600 rounded-lg hover:bg-rose-700 transition-colors">
                            Tugaskan
                        </a>
                    </div>
                @empty
                    <div class="py-10 text-center">
                        <div class="w-10 h-10 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-xs font-medium text-slate-700">Semua kelas terkendali!</p>
                        <p class="text-xs text-slate-400 mt-0.5">Tidak ada kelas kosong yang belum ditangani hari ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Daftar Izin Menunggu Validasi -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs flex flex-col">
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Izin Menunggu Validasi</h3>
                    <p class="text-xs text-slate-500">Pengajuan izin/sakit/dispensasi masuk</p>
                </div>
                <a href="{{ Route::has('piket.validasi-izin.index') ? route('piket.validasi-izin.index') : '#' }}"
                   class="text-xs font-medium text-emerald-600 hover:text-emerald-700">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="p-5 flex-1 space-y-3">
                @forelse($izinMenungguList ?? [] as $izin)
                    <div class="p-3.5 border border-slate-200 rounded-xl flex items-center justify-between gap-3 hover:bg-slate-50 transition-colors">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-800">{{ $izin->nama_pemohon ?? 'Pemohon' }}</span>
                                <x-badge-status :status="$izin->jenis ?? 'izin'" />
                            </div>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d M Y') }}
                                &bull; <span class="capitalize">{{ $izin->pemohon_tipe }}</span>
                            </p>
                        </div>
                        <a href="{{ Route::has('piket.validasi-izin.index') ? route('piket.validasi-izin.index') : '#' }}"
                           class="shrink-0 px-3 py-1.5 text-xs font-semibold text-amber-800 bg-amber-100 rounded-lg hover:bg-amber-200 transition-colors">
                            Periksa
                        </a>
                    </div>
                @empty
                    <div class="py-10 text-center">
                        <p class="text-xs font-medium text-slate-700">Tidak ada antrean izin</p>
                        <p class="text-xs text-slate-400 mt-0.5">Semua pengajuan izin sudah divalidasi.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection