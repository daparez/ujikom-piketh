@extends('layouts.admin.admin')

@section('title', 'Laporan & Rekapitulasi')
@section('page-title', 'Laporan & Rekapitulasi Piket')

@section('content')
    <div x-data="{ tab: 'guru' }" class="space-y-6">
        <!-- Panel Filter Periode & Kelas + Tombol Cetak -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs p-5">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5 items-end">
                <div>
                    <label for="tanggal_mulai" class="block text-[11px] font-semibold text-slate-600 uppercase tracking-wider">Dari Tanggal</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                           value="{{ $tanggalMulai->format('Y-m-d') }}"
                           class="mt-1.5 w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label for="tanggal_selesai" class="block text-[11px] font-semibold text-slate-600 uppercase tracking-wider">Sampai Tanggal</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                           value="{{ $tanggalSelesai->format('Y-m-d') }}"
                           class="mt-1.5 w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label for="kelas_id" class="block text-[11px] font-semibold text-slate-600 uppercase tracking-wider">Filter Kelas</label>
                    <select id="kelas_id" name="kelas_id"
                            class="mt-1.5 w-full px-3 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" @selected((string) $kelasId === (string) $kelas->id)>
                                {{ $kelas->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="w-full px-4 py-2 text-xs font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors cursor-pointer">
                        Tampilkan Rekap
                    </button>
                </div>

                <div>
                    <a href="{{ route('admin.laporan.cetak', array_merge(request()->query(), ['jenis' => 'semua'])) }}"
                       target="_blank"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Cetak Laporan</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- 4 Kartu Ringkasan Periode -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs">
                <p class="text-xs font-medium text-slate-500">Presensi Mengajar Guru</p>
                <p class="mt-2 text-2xl font-bold text-slate-800">
                    {{ $ringkasan['guru_hadir'] }} <span class="text-xs font-normal text-slate-400">/ {{ $ringkasan['total_sesi_guru'] }} sesi hadir</span>
                </p>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs">
                <p class="text-xs font-medium text-slate-500">Insiden Kelas Kosong</p>
                <p class="mt-2 text-2xl font-bold text-slate-800">
                    {{ $ringkasan['total_kelas_kosong'] }} <span class="text-xs font-normal text-emerald-600">({{ $ringkasan['kosong_tertangani'] }} tertangani)</span>
                </p>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs">
                <p class="text-xs font-medium text-slate-500">Keterlambatan & Izin Siswa</p>
                <p class="mt-2 text-2xl font-bold text-amber-600">
                    {{ $ringkasan['total_terlambat'] }} <span class="text-xs font-normal text-slate-500">telat &bull; {{ $ringkasan['total_izin_keluar'] }} izin</span>
                </p>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-xs">
                <p class="text-xs font-medium text-slate-500">Kasus Pelanggaran Siswa</p>
                <p class="mt-2 text-2xl font-bold text-rose-600">
                    {{ $ringkasan['total_pelanggaran'] }} <span class="text-xs font-normal text-slate-500">pelanggaran</span>
                </p>
            </div>
        </div>

        <!-- Navigasi Tab Rekapitulasi -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-4 pt-3 border-b border-slate-200 flex flex-wrap gap-2 bg-slate-50/70">
                <button type="button" @click="tab = 'guru'"
                        :class="tab === 'guru' ? 'border-indigo-600 text-indigo-700 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2.5 text-xs font-semibold border-b-2 rounded-t-lg transition-colors cursor-pointer">
                    1. Rekap Kehadiran Guru ({{ $rekapGuru->count() }})
                </button>

                <button type="button" @click="tab = 'kosong'"
                        :class="tab === 'kosong' ? 'border-indigo-600 text-indigo-700 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2.5 text-xs font-semibold border-b-2 rounded-t-lg transition-colors cursor-pointer">
                    2. Rekap Kelas Kosong ({{ $kelasKosongs->count() }})
                </button>

                <button type="button" @click="tab = 'siswa'"
                        :class="tab === 'siswa' ? 'border-indigo-600 text-indigo-700 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2.5 text-xs font-semibold border-b-2 rounded-t-lg transition-colors cursor-pointer">
                    3. Keterlambatan & Izin Siswa ({{ $keterlambatans->count() + $izinKeluars->count() }})
                </button>

                <button type="button" @click="tab = 'poin'"
                        :class="tab === 'poin' ? 'border-indigo-600 text-indigo-700 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="px-4 py-2.5 text-xs font-semibold border-b-2 rounded-t-lg transition-colors cursor-pointer">
                    4. Rekap Poin Pelanggaran ({{ $rekapPoinSiswa->count() }})
                </button>
            </div>

            <!-- TAB 1: REKAP KEHADIRAN GURU -->
            <div x-show="tab === 'guru'" class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5">Nama Guru</th>
                            <th class="px-4 py-3.5 text-center">Total Sesi</th>
                            <th class="px-4 py-3.5 text-center text-emerald-700">Hadir</th>
                            <th class="px-4 py-3.5 text-center text-amber-700">Terlambat</th>
                            <th class="px-4 py-3.5 text-center text-sky-700">Izin</th>
                            <th class="px-4 py-3.5 text-center text-indigo-700">Sakit</th>
                            <th class="px-4 py-3.5 text-center text-rose-700">Alpha</th>
                            <th class="px-5 py-3.5 text-right">Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @forelse($rekapGuru as $row)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-slate-800">{{ $row['guru']->name }}</p>
                                    <span class="text-[11px] text-slate-400">{{ $row['guru']->nip ? 'NIP. ' . $row['guru']->nip : '' }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-center font-semibold text-slate-700">{{ $row['total'] }}</td>
                                <td class="px-4 py-3.5 text-center font-semibold text-emerald-700">{{ $row['hadir'] }}</td>
                                <td class="px-4 py-3.5 text-center font-semibold text-amber-700">{{ $row['terlambat'] }}</td>
                                <td class="px-4 py-3.5 text-center font-semibold text-sky-700">{{ $row['izin'] }}</td>
                                <td class="px-4 py-3.5 text-center font-semibold text-indigo-700">{{ $row['sakit'] }}</td>
                                <td class="px-4 py-3.5 text-center font-semibold text-rose-700">{{ $row['alpha'] }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $row['persentase'] >= 80 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $row['persentase'] }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-10 text-center text-xs text-slate-400">
                                    Belum ada data presensi guru pada periode tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- TAB 2: REKAP KELAS KOSONG -->
            <div x-show="tab === 'kosong'" style="display: none;" class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Kelas & Jam</th>
                            <th class="px-5 py-3.5">Mata Pelajaran & Guru</th>
                            <th class="px-5 py-3.5">Alasan</th>
                            <th class="px-5 py-3.5">Penanganan / Pengganti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @forelse($kelasKosongs as $kk)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-5 py-3.5 font-medium text-slate-700">
                                    {{ $kk->tanggal->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-semibold text-slate-800">{{ $kk->jadwalPelajaran->kelas->nama ?? '-' }}</span>
                                    <span class="block text-[11px] font-mono text-slate-400">Jam ke-{{ $kk->jadwalPelajaran->jamPelajaran->jam_ke ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="font-medium text-slate-800">{{ $kk->jadwalPelajaran->mataPelajaran->nama ?? '-' }}</p>
                                    <span class="text-xs text-slate-500">{{ $kk->jadwalPelajaran->guru->name ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-3.5 capitalize text-xs text-slate-600">
                                    {{ str_replace('_', ' ', $kk->alasan) }}
                                </td>
                                <td class="px-5 py-3.5 text-xs">
                                    @if($kk->status === 'tertangani')
                                        <span class="font-semibold text-emerald-700">Diisi: {{ $kk->guruPengganti->name ?? '-' }}</span>
                                    @elseif($kk->status === 'tugas_mandiri')
                                        <span class="font-semibold text-amber-700">Tugas Mandiri</span>
                                    @else
                                        <span class="font-semibold text-rose-600">Tidak Terisi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-xs text-slate-400">
                                    Tidak ada catatan kelas kosong pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- TAB 3: REKAP KETERLAMBATAN & IZIN SISWA -->
            <div x-show="tab === 'siswa'" style="display: none;" class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Nama Siswa</th>
                            <th class="px-5 py-3.5">Kelas</th>
                            <th class="px-5 py-3.5">Jenis Catatan</th>
                            <th class="px-5 py-3.5">Waktu & Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @foreach($keterlambatans as $late)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-5 py-3.5 text-slate-700">{{ $late->tanggal->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $late->siswa->nama ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-xs text-slate-600">{{ $late->siswa->kelas->nama ?? '-' }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                        Terlambat ({{ $late->menit_terlambat }} mnt)
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-600">
                                    Datang {{ \Carbon\Carbon::parse($late->jam_datang)->format('H:i') }} &mdash; {{ $late->alasan }}
                                </td>
                            </tr>
                        @endforeach

                        @foreach($izinKeluars as $izin)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-5 py-3.5 text-slate-700">{{ $izin->tanggal->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $izin->siswa->nama ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-xs text-slate-600">{{ $izin->siswa->kelas->nama ?? '-' }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-700 capitalize">
                                        {{ str_replace('_', ' ', $izin->jenis) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-600">
                                    Keluar {{ \Carbon\Carbon::parse($izin->jam_keluar)->format('H:i') }} &mdash; {{ $izin->alasan }}
                                </td>
                            </tr>
                        @endforeach

                        @if($keterlambatans->isEmpty() && $izinKeluars->isEmpty())
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-xs text-slate-400">
                                    Tidak ada data keterlambatan maupun izin keluar siswa pada periode ini.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- TAB 4: REKAP AKUMULASI POIN PELANGGARAN SISWA -->
            <div x-show="tab === 'poin'" style="display: none;" class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-3.5 w-14">Rank</th>
                            <th class="px-5 py-3.5">NIS / Nama Siswa</th>
                            <th class="px-5 py-3.5">Kelas</th>
                            <th class="px-5 py-3.5 text-center">Jumlah Kasus</th>
                            <th class="px-5 py-3.5">Pelanggaran Terakhir</th>
                            <th class="px-5 py-3.5 text-right">Akumulasi Poin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @forelse($rekapPoinSiswa as $idx => $row)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-5 py-3.5 font-bold text-slate-500">#{{ $idx + 1 }}</td>
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-slate-800">{{ $row['siswa']->nama ?? '-' }}</p>
                                    <span class="font-mono text-[11px] text-slate-400">NIS. {{ $row['siswa']->nis ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-xs font-medium text-slate-700">
                                    {{ $row['siswa']->kelas->nama ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-center font-semibold text-slate-700">
                                    {{ $row['jumlah_kasus'] }}x
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-600">
                                    {{ $row['pelanggaran_akhir'] }}
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                                        {{ $row['total_poin'] }} Poin
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-xs text-slate-400">
                                    Nihil catatan pelanggaran siswa pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection