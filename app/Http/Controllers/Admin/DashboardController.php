<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BukuTamu;
use App\Models\IzinKeluarSiswa;
use App\Models\JadwalPelajaran;
use App\Models\JadwalPiket;
use App\Models\Kelas;
use App\Models\KelasKosong;
use App\Models\KeterlambatanSiswa;
use App\Models\PengajuanIzin;
use App\Models\PresensiGuru;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $todayStr = $today->toDateString();

        $mapHari = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            7 => 'minggu',
        ];
        $hariIni = $mapHari[$today->dayOfWeekIso];

        $tahunAktif = TahunAjaran::where('is_aktif', true)->first();

        // 1. Statistik Data Master
        $masterStats = [
            'total_guru'    => User::whereIn('role', ['guru', 'petugas'])->where('is_active', true)->count(),
            'total_siswa'   => Siswa::where('status', 'aktif')->count(),
            'total_kelas'   => Kelas::when($tahunAktif, fn ($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))->count(),
            'izin_menunggu' => PengajuanIzin::where('status', 'menunggu')->count(),
        ];

        // 2. Statistik Operasional Hari Ini
        $totalJadwalHariIni = JadwalPelajaran::when($tahunAktif, fn ($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->where('hari', $hariIni)
            ->count();

        $guruHadirHariIni = PresensiGuru::whereDate('tanggal', $todayStr)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();

        $operasionalStats = [
            'total_jadwal'       => $totalJadwalHariIni,
            'guru_hadir'         => $guruHadirHariIni,
            'persen_kehadiran'   => $totalJadwalHariIni > 0 ? round(($guruHadirHariIni / $totalJadwalHariIni) * 100) : 0,
            'kelas_kosong_aktif' => KelasKosong::whereDate('tanggal', $todayStr)->where('status', 'kosong')->count(),
            'siswa_terlambat'    => KeterlambatanSiswa::whereDate('tanggal', $todayStr)->count(),
            'siswa_izin_keluar'  => IzinKeluarSiswa::whereDate('tanggal', $todayStr)->count(),
            'tamu_hari_ini'      => BukuTamu::whereDate('tanggal', $todayStr)->count(),
        ];

        // 3. Petugas Piket Bertugas Hari Ini
        $petugasHariIni = JadwalPiket::with('petugas')
            ->when($tahunAktif, fn ($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->where('hari', $hariIni)
            ->orderByDesc('is_koordinator')
            ->get();

        // 4. Daftar Kelas Kosong Belum Tertangani Hari Ini
        $kelasKosongAktif = KelasKosong::with([
            'jadwalPelajaran.kelas',
            'jadwalPelajaran.mataPelajaran',
            'jadwalPelajaran.guru',
            'jadwalPelajaran.jamPelajaran',
        ])
            ->whereDate('tanggal', $todayStr)
            ->where('status', 'kosong')
            ->latest()
            ->take(5)
            ->get();

        // 5. Daftar Pengajuan Izin Guru Terbaru (Prioritas Menunggu)
        $pengajuanIzinTerbaru = PengajuanIzin::with('guru')
            ->orderByRaw("FIELD(status, 'menunggu', 'disetujui', 'ditolak')")
            ->latest()
            ->take(5)
            ->get();

        // 6. Log Aktivitas Sistem Terbaru
        $logTerbaru = ActivityLog::with('user')
            ->latest()
            ->take(6)
            ->get();

        return view('pages.admin.dashboard', compact(
            'today',
            'hariIni',
            'tahunAktif',
            'masterStats',
            'operasionalStats',
            'petugasHariIni',
            'kelasKosongAktif',
            'pengajuanIzinTerbaru',
            'logTerbaru'
        ));
    }
}