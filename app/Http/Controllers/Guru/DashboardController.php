<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $today = Carbon::today()->toDateString();
        $hariIni = Carbon::now()->dayOfWeekIso; // 1 = Senin ... 7 = Minggu

        $kelasWali = null;
        $jadwalHariIni = collect();
        $penugasanPengganti = collect();
        $tugasKelasPerluDiisi = collect();
        $riwayatIzin = collect();

        // 1. Cek apakah guru adalah Wali Kelas pada Tahun Ajaran Aktif
        if ($userId && Schema::hasTable('kelas') && Schema::hasTable('tahun_ajarans')) {
            $tahunAktif = DB::table('tahun_ajarans')->where('is_aktif', true)->first();
            $queryWali = DB::table('kelas')->where('wali_kelas_id', $userId)->whereNull('deleted_at');
            if ($tahunAktif) {
                $queryWali->where('tahun_ajaran_id', $tahunAktif->id);
            }
            $kelasWali = $queryWali->first();
        }

        // 2. Jadwal Mengajar Hari Ini
        if ($userId && Schema::hasTable('jadwal_pelajarans') && Schema::hasTable('kelas') && Schema::hasTable('mata_pelajarans') && Schema::hasTable('jam_pelajarans')) {
            $jadwalHariIni = DB::table('jadwal_pelajarans')
                ->join('kelas', 'jadwal_pelajarans.kelas_id', '=', 'kelas.id')
                ->join('mata_pelajarans', 'jadwal_pelajarans.mata_pelajaran_id', '=', 'mata_pelajarans.id')
                ->join('jam_pelajarans', 'jadwal_pelajarans.jam_pelajaran_id', '=', 'jam_pelajarans.id')
                ->select(
                    'jadwal_pelajarans.*',
                    'kelas.nama as nama_kelas',
                    'mata_pelajarans.nama as nama_mapel',
                    'jam_pelajarans.jam_ke',
                    'jam_pelajarans.jam_mulai',
                    'jam_pelajarans.jam_selesai'
                )
                ->where('jadwal_pelajarans.guru_id', $userId)
                ->where('jadwal_pelajarans.hari', $hariIni)
                ->orderBy('jam_pelajarans.jam_ke')
                ->get();
        }

        // 3. Penugasan Guru Pengganti Hari Ini & Tugas Kelas yang Perlu Diisi
        if ($userId && Schema::hasTable('kelas_kosongs') && Schema::hasTable('kelas') && Schema::hasTable('users') && Schema::hasTable('jadwal_pelajarans') && Schema::hasTable('jam_pelajarans') && Schema::hasTable('mata_pelajarans')) {
            // Penugasan pengganti yang diterima guru ini hari ini
            $penugasanPengganti = DB::table('kelas_kosongs')
                ->join('kelas', 'kelas_kosongs.kelas_id', '=', 'kelas.id')
                ->join('users', 'kelas_kosongs.guru_asal_id', '=', 'users.id')
                ->join('jadwal_pelajarans', 'kelas_kosongs.jadwal_pelajaran_id', '=', 'jadwal_pelajarans.id')
                ->join('jam_pelajarans', 'jadwal_pelajarans.jam_pelajaran_id', '=', 'jam_pelajarans.id')
                ->join('mata_pelajarans', 'jadwal_pelajarans.mata_pelajaran_id', '=', 'mata_pelajarans.id')
                ->select(
                    'kelas_kosongs.*',
                    'kelas.nama as nama_kelas',
                    'users.name as nama_guru_asal',
                    'jam_pelajarans.jam_ke',
                    'mata_pelajarans.nama as nama_mapel'
                )
                ->whereDate('kelas_kosongs.tanggal', $today)
                ->where('kelas_kosongs.guru_pengganti_id', $userId)
                ->orderBy('jam_pelajarans.jam_ke')
                ->get();

            // Kelas milik guru ini yang ditinggalkan dan tugasnya masih kosong
            $tugasKelasPerluDiisi = DB::table('kelas_kosongs')
                ->join('kelas', 'kelas_kosongs.kelas_id', '=', 'kelas.id')
                ->join('jadwal_pelajarans', 'kelas_kosongs.jadwal_pelajaran_id', '=', 'jadwal_pelajarans.id')
                ->join('jam_pelajarans', 'jadwal_pelajarans.jam_pelajaran_id', '=', 'jam_pelajarans.id')
                ->join('mata_pelajarans', 'jadwal_pelajarans.mata_pelajaran_id', '=', 'mata_pelajarans.id')
                ->select(
                    'kelas_kosongs.*',
                    'kelas.nama as nama_kelas',
                    'jam_pelajarans.jam_ke',
                    'mata_pelajarans.nama as nama_mapel'
                )
                ->whereDate('kelas_kosongs.tanggal', '>=', $today)
                ->where('kelas_kosongs.guru_asal_id', $userId)
                ->whereNull('kelas_kosongs.tugas')
                ->orderBy('kelas_kosongs.tanggal')
                ->orderBy('jam_pelajarans.jam_ke')
                ->limit(5)
                ->get();
        }

        // 4. Riwayat Pengajuan Izin Terakhir
        if ($userId && Schema::hasTable('pengajuan_izins')) {
            $riwayatIzin = DB::table('pengajuan_izins')
                ->where('diajukan_oleh', $userId)
                ->whereNull('deleted_at')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        }

        return view('pages.guru.dashboard', compact(
            'kelasWali',
            'jadwalHariIni',
            'penugasanPengganti',
            'tugasKelasPerluDiisi',
            'riwayatIzin'
        ));
    }
}