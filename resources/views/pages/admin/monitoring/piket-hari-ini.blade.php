@extends('layouts.admin.admin')

@section('title', 'Pantau Piket Hari Ini')
@section('page-title', 'Pantau Piket Hari Ini')

@section('content')
    <div class="space-y-6">
        <!-- Baris Kontrol Tanggal & Info Petugas Bertugas -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="flex items-center gap-2.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                            Monitoring Operasional
                        </span>
                        @if($tahunAktif)
                            <span class="text-xs text-slate-500">
                                TA {{ $tahunAktif->nama }} ({{ ucfirst($tahunAktif->semester) }})
                            </span>
                        @endif
                    </div>
                    <h2 class="text-base font-bold text-slate-800 mt-1.5 capitalize">
                        {{ $hariIni }}, {{ $tanggal->translatedFormat('d F Y') }}
                    </h2>
                </div>

                <!-- Filter Tanggal Pemantauan -->
                <form method="GET" action="{{ route('admin.monitoring.piket-hari-ini') }}" class="flex flex-wrap items-center gap-2">
                    <label for="tanggal" class="text-xs font-medium text-slate-600">Pilih Tanggal:</label>
                    <input type="date"
                           id="tanggal"
                           name="tanggal"
                           value="{{ $tanggal->format('Y-m-d') }}"
                           class="px-3 py-1.5 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    <button type="submit"
                            class="px-3.5 py-1.5 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 cursor-pointer">
                        Tampilkan
                    </button>
                    @if(! $tanggal->isToday())
                        <a href="{{ route('admin.monitoring.piket-hari-ini') }}"
                           class="px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200">
                            Hari Ini
                        </a>
                    @endif
                </form>
            </div>

            <!-- Daftar Petugas Piket Bertugas -->
            <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold text-slate-600 mr-1">Petugas Piket Bertugas:</span>
                @forelse($petugasHariIni as $piket)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium {{ $piket->is_koordinator ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                        <span>{{ $piket->petugas->name ?? '-' }}</span>
                        @if($piket->is_koordinator)
                            <span class="text-[10px] font-bold uppercase px-1.5 py-0.2 bg-indigo-600 text-white rounded">Koordinator</span>
                        @endif
                    </span>
                @empty
                    <span class="text-xs text-slate-400 italic">Tidak ada jadwal petugas piket pada hari ini.</span>
                @endforelse
            </div>
        </div>

        <!-- 4 Kartu Ringkasan KPI -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs">
                <p class="text-xs font-medium text-slate-500">Kehadiran Guru Mengajar</p>
                <div class="mt-2 flex items-baseline justify-between">
                    <p class="text-2xl font-bold text-slate-800">
                        {{ $stats['guru_hadir'] }} <span class="text-xs font-normal text-slate-400">/ {{ $stats['total_jadwal'] }} jam</span>
                    </p>
                    <span class="text-xs font-semibold text-emerald-600">
                        {{ $stats['total_jadwal'] > 0 ? round(($stats['guru_hadir'] / $stats['total_jadwal']) * 100) : 0 }}%
                    </span>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs">
                <p class="text-xs font-medium text-slate-500">Status Kelas Kosong</p>
                <div class="mt-2 flex items-baseline justify-between">
                    <p class="text-2xl font-bold {{ $stats['kelas_kosong_aktif'] > 0 ? 'text-rose-600' : 'text-slate-800' }}">
                        {{ $stats['kelas_kosong_aktif'] }} <span class="text-xs font-normal text-slate-500">belum terisi</span>
                    </p>
                    <span class="text-xs font-medium text-indigo-600">
                        {{ $stats['kelas_tertangani'] }} tertangani
                    </span>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs">
                <p class="text-xs font-medium text-slate-500">Kedisiplinan & Izin Siswa</p>
                <div class="mt-2 flex items-baseline justify-between">
                    <p class="text-2xl font-bold text-amber-600">
                        {{ $stats['siswa_terlambat'] }} <span class="text-xs font-normal text-slate-500">terlambat</span>
                    </p>
                    <span class="text-xs font-medium text-slate-600">
                        {{ $stats['siswa_izin'] }} izin keluar
                    </span>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs">
                <p class="text-xs font-medium text-slate-500">Kunjungan Buku Tamu</p>
                <div class="mt-2 flex items-baseline justify-between">
                    <p class="text-2xl font-bold text-slate-800">
                        {{ $stats['tamu_hari_ini'] }} <span class="text-xs font-normal text-slate-500">tamu</span>
                    </p>
                    @if($stats['tamu_masih_di_dalam'] > 0)
                        <span class="text-xs font-semibold text-sky-600">
                            {{ $stats['tamu_masih_di_dalam'] }} masih di lokasi
                        </span>
                    @else
                        <span class="text-xs text-slate-400">Semua sudah keluar</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Monitoring Matriks Jadwal KBM & Kehadiran Guru Hari Ini -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Pemantauan KBM & Kehadiran Guru</h3>
                    <p class="text-xs text-slate-500">Status pelaksanaan setiap slot jadwal pelajaran hari ini</p>
                </div>
                <span class="text-xs font-medium text-slate-500">Total: {{ $jadwalHariIni->count() }} Sesi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3">Jam Ke-</th>
                            <th class="px-5 py-3">Kelas</th>
                            <th class="px-5 py-3">Mata Pelajaran</th>
                            <th class="px-5 py-3">Guru Pengampu</th>
                            <th class="px-5 py-3">Status KBM</th>
                            <th class="px-5 py-3">Keterangan / Pengganti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @forelse($jadwalHariIni as $jadwal)
                            @php
                                $presensi = $presensiMap->get($jadwal->id);
                                $kosong = $kelasKosongMap->get($jadwal->id);
                            @endphp
                            <tr class="hover:bg-slate-50/80 {{ $kosong && $kosong->status === 'kosong' ? 'bg-rose-50/40' : '' }}">
                                <td class="px-5 py-3 font-mono text-xs text-slate-700">
                                    <span class="font-semibold">Jam {{ $jadwal->jamPelajaran->jam_ke ?? '-' }}</span>
                                    @if($jadwal->jamPelajaran)
                                        <span class="block text-[11px] text-slate-400">
                                            {{ \Carbon\Carbon::parse($jadwal->jamPelajaran->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($jadwal->jamPelajaran->jam_selesai)->format('H:i') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 font-semibold text-slate-800">
                                    {{ $jadwal->kelas->nama ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-slate-700">
                                    {{ $jadwal->mataPelajaran->nama ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-slate-700">
                                    {{ $jadwal->guru->name ?? '-' }}
                                </td>
                                <td class="px-5 py-3">
                                    @if($kosong)
                                        @if($kosong->status === 'kosong')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 border border-rose-200">
                                                Kelas Kosong
                                            </span>
                                        @elseif($kosong->status === 'tertangani')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200">
                                                Diisi Pengganti
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                Tugas Mandiri
                                            </span>
                                        @endif
                                    @elseif($presensi)
                                        <x-badge-status :status="$presensi->status" />
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                            Belum Dipresensi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-xs text-slate-600">
                                    @if($kosong)
                                        Alasan: <span class="font-medium capitalize">{{ str_replace('_', ' ', $kosong->alasan) }}</span>
                                        @if($kosong->guruPengganti)
                                            &bull; Pengganti: <span class="font-semibold text-indigo-700">{{ $kosong->guruPengganti->name }}</span>
                                        @endif
                                    @elseif($presensi)
                                        {{ $presensi->jam_masuk ? 'Masuk ' . \Carbon\Carbon::parse($presensi->jam_masuk)->format('H:i') : '' }}
                                        {{ $presensi->keterangan ? ' - ' . $presensi->keterangan : '' }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-xs text-slate-400">
                                    Tidak ada jadwal pelajaran untuk hari {{ ucfirst($hariIni) }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Grid 2 Kolom: Aktivitas Siswa (Terlambat & Izin) + Pelanggaran -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Keterlambatan & Izin Keluar Siswa -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-800">Siswa Terlambat & Izin Keluar</h3>
                    <p class="text-xs text-slate-500">Catatan lalu lintas gerbang siswa hari ini</p>
                </div>
                <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                    @foreach($keterlambatans as $late)
                        <div class="px-5 py-3 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold text-slate-800">
                                    {{ $late->siswa->nama ?? '-' }}
                                    <span class="text-slate-400 font-normal">({{ $late->siswa->kelas->nama ?? '-' }})</span>
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Datang {{ \Carbon\Carbon::parse($late->jam_datang)->format('H:i') }} WIB (telat {{ $late->menit_terlambat }} mnt) &mdash; {{ $late->alasan }}
                                </p>
                            </div>
                            <span class="shrink-0 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                                Terlambat
                            </span>
                        </div>
                    @endforeach

                    @foreach($izinKeluars as $izin)
                        <div class="px-5 py-3 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold text-slate-800">
                                    {{ $izin->siswa->nama ?? '-' }}
                                    <span class="text-slate-400 font-normal">({{ $izin->siswa->kelas->nama ?? '-' }})</span>
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Keluar {{ \Carbon\Carbon::parse($izin->jam_keluar)->format('H:i') }} WIB &mdash; {{ $izin->alasan }}
                                </p>
                            </div>
                            <span class="shrink-0 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-sky-100 text-sky-700 capitalize">
                                {{ str_replace('_', ' ', $izin->jenis) }}
                            </span>
                        </div>
                    @endforeach

                    @if($keterlambatans->isEmpty() && $izinKeluars->isEmpty())
                        <div class="px-5 py-8 text-center text-xs text-slate-400">
                            Tidak ada catatan keterlambatan maupun izin keluar siswa.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pelanggaran Siswa Hari Ini -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-800">Pelanggaran Tata Tertib Siswa</h3>
                    <p class="text-xs text-slate-500">Pelanggaran yang tercatat pada tanggal ini</p>
                </div>
                <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                    @forelse($pelanggarans as $pel)
                        <div class="px-5 py-3 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold text-slate-800">
                                    {{ $pel->siswa->nama ?? '-' }}
                                    <span class="text-slate-400 font-normal">({{ $pel->siswa->kelas->nama ?? '-' }})</span>
                                </p>
                                <p class="text-xs text-slate-600 mt-0.5">
                                    {{ $pel->jenisPelanggaran->nama ?? '-' }}
                                </p>
                            </div>
                            <span class="shrink-0 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                                +{{ $pel->poin }} Poin
                            </span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-xs text-slate-400">
                            Nihil pelanggaran siswa pada tanggal ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Grid 2 Kolom: Buku Tamu & Kejadian / Jurnal Piket -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Buku Tamu -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-800">Buku Tamu Sekolah</h3>
                    <p class="text-xs text-slate-500">Daftar kunjungan tamu eksternal / wali murid</p>
                </div>
                <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
                    @forelse($bukuTamus as $tamu)
                        <div class="px-5 py-3 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold text-slate-800">
                                    {{ $tamu->nama_tamu }}
                                    @if($tamu->instansi)
                                        <span class="font-normal text-slate-500">&bull; {{ $tamu->instansi }}</span>
                                    @endif
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Bertemu: {{ $tamu->bertemu_dengan }} ({{ $tamu->keperluan }})
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-[11px] font-mono text-slate-600 block">
                                    {{ \Carbon\Carbon::parse($tamu->jam_masuk)->format('H:i') }} - {{ $tamu->jam_keluar ? \Carbon\Carbon::parse($tamu->jam_keluar)->format('H:i') : 'Sekarang' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-xs text-slate-400">
                            Belum ada tamu tercatat pada tanggal ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Jurnal Kejadian Piket -->
            <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200">
                    <h3 class="text-sm font-semibold text-slate-800">Jurnal Kejadian Piket</h3>
                    <p class="text-xs text-slate-500">Catatan insiden atau kejadian khusus selama jam sekolah</p>
                </div>
                <div class="divide-y divide-slate-100 max-h-72 overflow-y-auto">
                    @forelse($kejadianPikets as $kejadian)
                        <div class="px-5 py-3">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs font-semibold text-slate-800">{{ $kejadian->judul }}</p>
                                <span class="text-[11px] font-mono text-slate-400">
                                    {{ \Carbon\Carbon::parse($kejadian->waktu)->format('H:i') }} WIB
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 mt-1">{{ $kejadian->uraian }}</p>
                            @if($kejadian->tindakan)
                                <p class="text-[11px] text-indigo-700 mt-1">Tindakan: {{ $kejadian->tindakan }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-xs text-slate-400">
                            Tidak ada kejadian khusus tercatat pada tanggal ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection