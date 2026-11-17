<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Piket Sekolah - {{ $tanggalMulai->format('d/m/Y') }} s.d. {{ $tanggalSelesai->format('d/m/Y') }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; font-size: 11pt; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased">
    <!-- Toolbar Cetak -->
    <div class="no-print bg-slate-900 text-white px-6 py-3 flex items-center justify-between">
        <span class="text-xs font-medium">Pratinjau Cetak Laporan Rekapitulasi Piket Sekolah</span>
        <div class="flex items-center gap-2">
            <button onclick="window.print()"
                    class="px-4 py-1.5 text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 rounded-lg cursor-pointer">
                Cetak / Simpan PDF
            </button>
            <button onclick="window.close()"
                    class="px-3 py-1.5 text-xs font-medium bg-slate-700 hover:bg-slate-600 rounded-lg cursor-pointer">
                Tutup
            </button>
        </div>
    </div>

    <!-- Lembar Kertas A4 -->
    <div class="max-w-4xl mx-auto my-6 bg-white p-8 sm:p-10 shadow-sm print:shadow-none print:my-0 print:p-0">
        <!-- Kop Surat Sekolah -->
        <div class="border-b-2 border-slate-900 pb-4 text-center">
            <h1 class="text-lg font-bold uppercase tracking-wide">
                {{ $sekolah->nama_sekolah ?? 'SISTEM INFORMASI MANAJEMEN PIKET SEKOLAH' }}
            </h1>
            @if(!empty($sekolah->npsn))
                <p class="text-xs text-slate-600">NPSN: {{ $sekolah->npsn }}</p>
            @endif
            <p class="text-xs text-slate-600 mt-0.5">
                {{ $sekolah->alamat ?? 'Alamat Sekolah Belum Diatur' }}
                {{ !empty($sekolah->telepon) ? ' | Telp: ' . $sekolah->telepon : '' }}
            </p>
        </div>

        <!-- Judul Laporan -->
        <div class="my-6 text-center">
            <h2 class="text-sm font-bold uppercase underline">LAPORAN REKAPITULASI OPERASIONAL PIKET SEKOLAH</h2>
            <p class="text-xs text-slate-600 mt-1">
                Periode: {{ $tanggalMulai->translatedFormat('d F Y') }} s.d. {{ $tanggalSelesai->translatedFormat('d F Y') }}
                @if($tahunAktif)
                    &bull; Tahun Ajaran {{ $tahunAktif->nama }} ({{ ucfirst($tahunAktif->semester) }})
                @endif
            </p>
        </div>

        <!-- Ringkasan Eksekutif -->
        <div class="mb-6">
            <h3 class="text-xs font-bold uppercase mb-2">I. Ringkasan Aktivitas Piket</h3>
            <table class="w-full border border-slate-400 text-xs">
                <tbody>
                    <tr class="border-b border-slate-300">
                        <td class="p-2 font-medium bg-slate-50 w-1/3">Total Sesi Mengajar Dipantau</td>
                        <td class="p-2 w-1/6 font-bold">{{ $ringkasan['total_sesi_guru'] }} sesi</td>
                        <td class="p-2 font-medium bg-slate-50 w-1/3">Total Siswa Terlambat</td>
                        <td class="p-2 w-1/6 font-bold">{{ $ringkasan['total_terlambat'] }} siswa</td>
                    </tr>
                    <tr class="border-b border-slate-300">
                        <td class="p-2 font-medium bg-slate-50">Total Insiden Kelas Kosong</td>
                        <td class="p-2 font-bold">{{ $ringkasan['total_kelas_kosong'] }} sesi</td>
                        <td class="p-2 font-medium bg-slate-50">Total Siswa Izin Keluar</td>
                        <td class="p-2 font-bold">{{ $ringkasan['total_izin_keluar'] }} siswa</td>
                    </tr>
                    <tr>
                        <td class="p-2 font-medium bg-slate-50">Total Pelanggaran Siswa</td>
                        <td class="p-2 font-bold">{{ $ringkasan['total_pelanggaran'] }} kasus</td>
                        <td class="p-2 font-medium bg-slate-50">Kunjungan Tamu / Kejadian</td>
                        <td class="p-2 font-bold">{{ $ringkasan['total_tamu'] }} tamu / {{ $ringkasan['total_kejadian'] }} kejadian</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tabel Rekap Kehadiran Guru -->
        <div class="mb-6">
            <h3 class="text-xs font-bold uppercase mb-2">II. Rekapitulasi Kehadiran Guru Mengajar</h3>
            <table class="w-full border border-slate-400 text-xs text-left">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-400">
                        <th class="p-2 border-r border-slate-300 w-10 text-center">No</th>
                        <th class="p-2 border-r border-slate-300">Nama Guru</th>
                        <th class="p-2 border-r border-slate-300 text-center">Sesi</th>
                        <th class="p-2 border-r border-slate-300 text-center">Hadir</th>
                        <th class="p-2 border-r border-slate-300 text-center">Telat</th>
                        <th class="p-2 border-r border-slate-300 text-center">Izin</th>
                        <th class="p-2 border-r border-slate-300 text-center">Sakit</th>
                        <th class="p-2 border-r border-slate-300 text-center">Alpha</th>
                        <th class="p-2 text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapGuru as $idx => $row)
                        <tr class="border-b border-slate-300">
                            <td class="p-2 border-r border-slate-300 text-center">{{ $idx + 1 }}</td>
                            <td class="p-2 border-r border-slate-300">{{ $row['guru']->name }}</td>
                            <td class="p-2 border-r border-slate-300 text-center">{{ $row['total'] }}</td>
                            <td class="p-2 border-r border-slate-300 text-center">{{ $row['hadir'] }}</td>
                            <td class="p-2 border-r border-slate-300 text-center">{{ $row['terlambat'] }}</td>
                            <td class="p-2 border-r border-slate-300 text-center">{{ $row['izin'] }}</td>
                            <td class="p-2 border-r border-slate-300 text-center">{{ $row['sakit'] }}</td>
                            <td class="p-2 border-r border-slate-300 text-center">{{ $row['alpha'] }}</td>
                            <td class="p-2 text-center font-bold">{{ $row['persentase'] }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-4 text-center text-slate-500">Tidak ada data presensi guru pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Tabel Rekap Akumulasi Poin Pelanggaran Siswa -->
        <div class="mb-8">
            <h3 class="text-xs font-bold uppercase mb-2">III. Rekapitulasi Akumulasi Poin Pelanggaran Siswa</h3>
            <table class="w-full border border-slate-400 text-xs text-left">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-400">
                        <th class="p-2 border-r border-slate-300 w-10 text-center">No</th>
                        <th class="p-2 border-r border-slate-300">NIS</th>
                        <th class="p-2 border-r border-slate-300">Nama Siswa</th>
                        <th class="p-2 border-r border-slate-300">Kelas</th>
                        <th class="p-2 border-r border-slate-300 text-center">Kasus</th>
                        <th class="p-2 text-center">Total Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapPoinSiswa as $idx => $row)
                        <tr class="border-b border-slate-300">
                            <td class="p-2 border-r border-slate-300 text-center">{{ $idx + 1 }}</td>
                            <td class="p-2 border-r border-slate-300 font-mono">{{ $row['siswa']->nis ?? '-' }}</td>
                            <td class="p-2 border-r border-slate-300">{{ $row['siswa']->nama ?? '-' }}</td>
                            <td class="p-2 border-r border-slate-300">{{ $row['siswa']->kelas->nama ?? '-' }}</td>
                            <td class="p-2 border-r border-slate-300 text-center">{{ $row['jumlah_kasus'] }}x</td>
                            <td class="p-2 text-center font-bold">{{ $row['total_poin'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-slate-500">Nihil pelanggaran siswa pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Blok Tanda Tangan -->
        <div class="mt-10 grid grid-cols-2 gap-8 text-xs">
            <div class="text-center">
                <p>Mengetahui,</p>
                <p class="font-semibold">Kepala Sekolah</p>
                <div class="h-16"></div>
                <p class="font-bold underline">{{ $sekolah->kepala_sekolah ?? '........................................' }}</p>
                @if(!empty($sekolah->nip_kepala_sekolah))
                    <p>NIP. {{ $sekolah->nip_kepala_sekolah }}</p>
                @endif
            </div>

            <div class="text-center">
                <p>{{ now()->translatedFormat('d F Y') }}</p>
                <p class="font-semibold">Koordinator Piket / Administrator</p>
                <div class="h-16"></div>
                <p class="font-bold underline">{{ auth()->user()->name }}</p>
                @if(auth()->user()->nip)
                    <p>NIP. {{ auth()->user()->nip }}</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>