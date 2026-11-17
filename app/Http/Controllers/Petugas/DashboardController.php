<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $today = Carbon::today()->toDateString();

        $piketHariIni = null;
        $stats = [
            'total_guru_jadwal'  => 0,
            'guru_tercatat'      => 0,
            'persen_progres'     => 0,
            'guru_tidak_hadir'   => 0,
            'siswa_tidak_hadir'  => 0,
            'izin_menunggu'      => 0,
            'kelas_kosong_belum' => 0,
        ];
        $kelasKosongList = collect();
        $izinMenungguList = collect();

        // 1. Lembar Piket Hari Ini
        if (Schema::hasTable('piket_harians') && Schema::hasTable('users')) {
            $piket = DB::table('piket_harians')
                ->leftJoin('users', 'piket_harians.petugas_id', '=', 'users.id')
                ->select('piket_harians.*', 'users.name as petugas_nama')
                ->whereDate('piket_harians.tanggal', $today)
                ->first();

            if ($piket) {
                $piket->petugas = (object) ['name' => $piket->petugas_nama ?? '-'];
                $piketHariIni = $piket;
            }
        }

        // 2. Progres & Statistik Kehadiran Guru
        if (Schema::hasTable('kehadiran_gurus')) {
            $totalGuru = DB::table('kehadiran_gurus')->whereDate('tanggal', $today)->count();
            $guruTercatat = DB::table('kehadiran_gurus')
                ->whereDate('tanggal', $today)
                ->whereNotNull('status')
                ->count();

            $stats['total_guru_jadwal'] = $totalGuru;
            $stats['guru_tercatat'] = $guruTercatat;
            $stats['persen_progres'] = $totalGuru > 0 ? round(($guruTercatat / $totalGuru) * 100) : 0;

            $stats['guru_tidak_hadir'] = DB::table('kehadiran_gurus')
                ->whereDate('tanggal', $today)
                ->whereIn('status', ['izin', 'sakit', 'dinas', 'alpa'])
                ->count();
        }

        // 3. Siswa Tidak Hadir Hari Ini
        if (Schema::hasTable('kehadiran_siswas')) {
            $stats['siswa_tidak_hadir'] = DB::table('kehadiran_siswas')
                ->whereDate('tanggal', $today)
                ->whereIn('status', ['izin', 'sakit', 'alpa', 'dispensasi'])
                ->count();
        }

        // 4. Pengajuan Izin Menunggu Validasi
        if (Schema::hasTable('pengajuan_izins')) {
            $stats['izin_menunggu'] = DB::table('pengajuan_izins')
                ->where('status', 'menunggu')
                ->count();

            if (Schema::hasTable('users') && Schema::hasTable('siswas')) {
                $izinMenungguList = DB::table('pengajuan_izins')
                    ->leftJoin('users', 'pengajuan_izins.guru_id', '=', 'users.id')
                    ->leftJoin('siswas', 'pengajuan_izins.siswa_id', '=', 'siswas.id')
                    ->select(
                        'pengajuan_izins.*',
                        DB::raw('COALESCE(users.name, siswas.nama) as nama_pemohon')
                    )
                    ->where('pengajuan_izins.status', 'menunggu')
                    ->orderBy('pengajuan_izins.created_at')
                    ->limit(5)
                    ->get();
            }
        }

        // 5. Kelas Kosong Belum Ditangani Hari Ini
        if (Schema::hasTable('kelas_kosongs')) {
            $stats['kelas_kosong_belum'] = DB::table('kelas_kosongs')
                ->whereDate('tanggal', $today)
                ->where('status', 'belum_ditangani')
                ->count();

            if (Schema::hasTable('kelas') && Schema::hasTable('users') && Schema::hasTable('jadwal_pelajarans') && Schema::hasTable('jam_pelajarans') && Schema::hasTable('mata_pelajarans')) {
                $kelasKosongList = DB::table('kelas_kosongs')
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
                    ->where('kelas_kosongs.status', 'belum_ditangani')
                    ->orderBy('jam_pelajarans.jam_ke')
                    ->limit(5)
                    ->get();
            }
        }

        return view('pages.petugas.dashboard', compact(
            'piketHariIni',
            'stats',
            'kelasKosongList',
            'izinMenungguList'
        ));
    }
}