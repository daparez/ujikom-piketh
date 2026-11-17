<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuTamu;
use App\Models\IzinKeluarSiswa;
use App\Models\KejadianPiket;
use App\Models\Kelas;
use App\Models\KelasKosong;
use App\Models\KeterlambatanSiswa;
use App\Models\PelanggaranSiswa;
use App\Models\PresensiGuru;
use App\Models\SekolahSetting;
use App\Models\TahunAjaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $data = $this->buildRekapData($request);
        return view('pages.admin.laporan.index', $data);
    }

    public function cetak(Request $request): View
    {
        $data = $this->buildRekapData($request);
        $data['jenisLaporan'] = $request->get('jenis', 'semua');
        $data['sekolah'] = SekolahSetting::first();

        return view('pages.admin.laporan.cetak', $data);
    }

    private function buildRekapData(Request $request): array
    {
        $tanggalMulai = $request->filled('tanggal_mulai')
            ? Carbon::parse($request->tanggal_mulai)->startOfDay()
            : Carbon::now()->startOfMonth();

        $tanggalSelesai = $request->filled('tanggal_selesai')
            ? Carbon::parse($request->tanggal_selesai)->endOfDay()
            : Carbon::today()->endOfDay();

        $kelasId = $request->kelas_id;
        $tahunAktif = TahunAjaran::where('is_aktif', true)->first();

        $kelasList = Kelas::query()
            ->when($tahunAktif, fn ($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        // 1. Rekap Kehadiran Guru
        $presensiQuery = PresensiGuru::whereBetween('tanggal', [$tanggalMulai->toDateString(), $tanggalSelesai->toDateString()])
            ->when($kelasId, fn ($q) => $q->whereHas('jadwalPelajaran', fn ($j) => $j->where('kelas_id', $kelasId)))
            ->get();

        $gurus = User::whereIn('role', ['guru', 'petugas'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $rekapGuru = $gurus->map(function ($guru) use ($presensiQuery) {
            $records = $presensiQuery->where('guru_id', $guru->id);
            $total = $records->count();
            $hadir = $records->where('status', 'hadir')->count();
            $terlambat = $records->where('status', 'terlambat')->count();
            $izin = $records->where('status', 'izin')->count();
            $sakit = $records->where('status', 'sakit')->count();
            $alpha = $records->where('status', 'tanpa_keterangan')->count();

            $persentase = $total > 0 ? round((($hadir + $terlambat) / $total) * 100, 1) : 0;

            return [
                'guru'       => $guru,
                'total'      => $total,
                'hadir'      => $hadir,
                'terlambat'  => $terlambat,
                'izin'       => $izin,
                'sakit'      => $sakit,
                'alpha'      => $alpha,
                'persentase' => $persentase,
            ];
        })->filter(fn ($row) => $row['total'] > 0)->values();

        // 2. Rekap Kelas Kosong
        $kelasKosongs = KelasKosong::with([
            'jadwalPelajaran.kelas',
            'jadwalPelajaran.mataPelajaran',
            'jadwalPelajaran.guru',
            'jadwalPelajaran.jamPelajaran',
            'guruPengganti',
        ])
            ->whereBetween('tanggal', [$tanggalMulai->toDateString(), $tanggalSelesai->toDateString()])
            ->when($kelasId, fn ($q) => $q->whereHas('jadwalPelajaran', fn ($j) => $j->where('kelas_id', $kelasId)))
            ->orderByDesc('tanggal')
            ->get();

        // 3. Rekap Keterlambatan & Izin Keluar Siswa
        $keterlambatans = KeterlambatanSiswa::with(['siswa.kelas'])
            ->whereBetween('tanggal', [$tanggalMulai->toDateString(), $tanggalSelesai->toDateString()])
            ->when($kelasId, fn ($q) => $q->whereHas('siswa', fn ($s) => $s->where('kelas_id', $kelasId)))
            ->orderByDesc('tanggal')
            ->get();

        $izinKeluars = IzinKeluarSiswa::with(['siswa.kelas'])
            ->whereBetween('tanggal', [$tanggalMulai->toDateString(), $tanggalSelesai->toDateString()])
            ->when($kelasId, fn ($q) => $q->whereHas('siswa', fn ($s) => $s->where('kelas_id', $kelasId)))
            ->orderByDesc('tanggal')
            ->get();

        // 4. Rekap Pelanggaran & Akumulasi Poin Siswa
        $pelanggarans = PelanggaranSiswa::with(['siswa.kelas', 'jenisPelanggaran'])
            ->whereBetween('tanggal', [$tanggalMulai->toDateString(), $tanggalSelesai->toDateString()])
            ->when($kelasId, fn ($q) => $q->whereHas('siswa', fn ($s) => $s->where('kelas_id', $kelasId)))
            ->orderByDesc('tanggal')
            ->get();

        $rekapPoinSiswa = $pelanggarans
            ->groupBy('siswa_id')
            ->map(function ($items) {
                $siswa = $items->first()->siswa;
                return [
                    'siswa'             => $siswa,
                    'jumlah_kasus'      => $items->count(),
                    'total_poin'        => $items->sum('poin'),
                    'pelanggaran_akhir' => $items->first()->jenisPelanggaran->nama ?? '-',
                ];
            })
            ->sortByDesc('total_poin')
            ->values();

        // 5. Ringkasan Buku Tamu & Kejadian Piket
        $totalTamu = BukuTamu::whereBetween('tanggal', [$tanggalMulai->toDateString(), $tanggalSelesai->toDateString()])->count();
        $totalKejadian = KejadianPiket::whereBetween('tanggal', [$tanggalMulai->toDateString(), $tanggalSelesai->toDateString()])->count();

        $ringkasan = [
            'total_sesi_guru'     => $presensiQuery->count(),
            'guru_hadir'          => $presensiQuery->whereIn('status', ['hadir', 'terlambat'])->count(),
            'total_kelas_kosong'  => $kelasKosongs->count(),
            'kosong_tertangani'   => $kelasKosongs->whereIn('status', ['tertangani', 'tugas_mandiri'])->count(),
            'total_terlambat'     => $keterlambatans->count(),
            'total_izin_keluar'   => $izinKeluars->count(),
            'total_pelanggaran'   => $pelanggarans->count(),
            'total_tamu'          => $totalTamu,
            'total_kejadian'      => $totalKejadian,
        ];

        return compact(
            'tanggalMulai',
            'tanggalSelesai',
            'kelasId',
            'kelasList',
            'tahunAktif',
            'rekapGuru',
            'kelasKosongs',
            'keterlambatans',
            'izinKeluars',
            'pelanggarans',
            'rekapPoinSiswa',
            'ringkasan'
        );
    }
}