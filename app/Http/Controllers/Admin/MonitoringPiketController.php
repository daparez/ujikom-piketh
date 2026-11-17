<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuTamu;
use App\Models\IzinKeluarSiswa;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPiket;
use App\Models\KejadianPiket;
use App\Models\KelasKosong;
use App\Models\KeterlambatanSiswa;
use App\Models\PelanggaranSiswa;
use App\Models\PresensiGuru;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitoringPiketController extends Controller
{
    public function piketHariIni(Request $request): View
    {
        $tanggal = $request->filled('tanggal')
            ? Carbon::parse($request->tanggal)
            : Carbon::today();

        $tanggalStr = $tanggal->toDateString();

        $mapHari = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            7 => 'minggu',
        ];
        $hariIni = $mapHari[$tanggal->dayOfWeekIso];

        $tahunAktif = TahunAjaran::where('is_aktif', true)->first();

        // 1. Petugas Piket Bertugas Hari Ini
        $petugasHariIni = JadwalPiket::with('petugas')
            ->when($tahunAktif, fn ($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->where('hari', $hariIni)
            ->orderByDesc('is_koordinator')
            ->get();

        // 2. Jadwal Pelajaran Hari Ini + Status Presensi & Kelas Kosong
        $jadwalHariIni = JadwalPelajaran::with(['kelas', 'mataPelajaran', 'guru', 'jamPelajaran'])
            ->when($tahunAktif, fn ($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->where('hari', $hariIni)
            ->orderBy('jam_pelajaran_id')
            ->orderBy('kelas_id')
            ->get();

        $presensiMap = PresensiGuru::whereDate('tanggal', $tanggalStr)
            ->get()
            ->keyBy('jadwal_pelajaran_id');

        $kelasKosongList = KelasKosong::with(['jadwalPelajaran.kelas', 'jadwalPelajaran.mataPelajaran', 'jadwalPelajaran.guru', 'jadwalPelajaran.jamPelajaran', 'guruPengganti'])
            ->whereDate('tanggal', $tanggalStr)
            ->get();

        $kelasKosongMap = $kelasKosongList->keyBy('jadwal_pelajaran_id');

        // 3. Log Operasional Siswa, Tamu, & Kejadian Piket pada Tanggal Tersebut
        $keterlambatans = KeterlambatanSiswa::with(['siswa.kelas', 'petugas'])
            ->whereDate('tanggal', $tanggalStr)
            ->latest('jam_datang')
            ->get();

        $izinKeluars = IzinKeluarSiswa::with(['siswa.kelas', 'petugas'])
            ->whereDate('tanggal', $tanggalStr)
            ->latest('jam_keluar')
            ->get();

        $pelanggarans = PelanggaranSiswa::with(['siswa.kelas', 'jenisPelanggaran', 'pencatat'])
            ->whereDate('tanggal', $tanggalStr)
            ->latest()
            ->get();

        $bukuTamus = BukuTamu::with('petugas')
            ->whereDate('tanggal', $tanggalStr)
            ->latest('jam_masuk')
            ->get();

        $kejadianPikets = KejadianPiket::with('petugas')
            ->whereDate('tanggal', $tanggalStr)
            ->latest('waktu')
            ->get();

        // 4. Statistik Ringkas KPI
        $stats = [
            'total_jadwal'        => $jadwalHariIni->count(),
            'guru_hadir'          => $presensiMap->whereIn('status', ['hadir', 'terlambat'])->count(),
            'kelas_kosong_aktif'  => $kelasKosongList->where('status', 'kosong')->count(),
            'kelas_tertangani'    => $kelasKosongList->whereIn('status', ['tertangani', 'tugas_mandiri'])->count(),
            'siswa_terlambat'     => $keterlambatans->count(),
            'siswa_izin'          => $izinKeluars->count(),
            'tamu_hari_ini'       => $bukuTamus->count(),
            'tamu_masih_di_dalam' => $bukuTamus->whereNull('jam_keluar')->count(),
        ];

        return view('pages.admin.monitoring.piket-hari-ini', compact(
            'tanggal',
            'hariIni',
            'tahunAktif',
            'petugasHariIni',
            'jadwalHariIni',
            'presensiMap',
            'kelasKosongMap',
            'kelasKosongList',
            'keterlambatans',
            'izinKeluars',
            'pelanggarans',
            'bukuTamus',
            'kejadianPikets',
            'stats'
        ));
    }
}