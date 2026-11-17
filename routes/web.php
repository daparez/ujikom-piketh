<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\JamPelajaranController;
use App\Http\Controllers\Admin\JenisPelanggaranController;
use App\Http\Controllers\Admin\JadwalPelajaranController;
use App\Http\Controllers\Admin\JadwalPiketController;
use App\Http\Controllers\Admin\MonitoringPiketController;
use App\Http\Controllers\Admin\PengajuanIzinController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengaturanSekolahController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Sementara tanpa middleware role dulu sebelum Enum & EnsureRole dibuat
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('tahun-ajaran', TahunAjaranController::class)->except(['show']);
    Route::patch('tahun-ajaran/{tahun_ajaran}/aktifkan', [TahunAjaranController::class, 'aktifkan'])
    ->name('tahun-ajaran.aktifkan');

    Route::resource('kelas', KelasController::class)->except(['show']);
    Route::resource('siswa', SiswaController::class)->except(['show']);
    Route::resource('mapel', MapelController::class)->except(['show']);
    Route::resource('jam-pelajaran', JamPelajaranController::class)->except(['show']);
    Route::resource('jenis-pelanggaran', JenisPelanggaranController::class)->except(['show']);
    Route::resource('jadwal-pelajaran', JadwalPelajaranController::class)->except(['show']);
    Route::resource('jadwal-piket', JadwalPiketController::class)->except(['show']);

    Route::get('monitoring/piket-hari-ini', [MonitoringPiketController::class, 'piketHariIni'])
    ->name('monitoring.piket-hari-ini');

    Route::get('pengajuan-izin', [PengajuanIzinController::class, 'index'])->name('pengajuan-izin.index');
    Route::get('pengajuan-izin/{pengajuan_izin}', [PengajuanIzinController::class, 'show'])->name('pengajuan-izin.show');
    Route::patch('pengajuan-izin/{pengajuan_izin}/setujui', [PengajuanIzinController::class, 'setujui'])->name('pengajuan-izin.setujui');
    Route::patch('pengajuan-izin/{pengajuan_izin}/tolak', [PengajuanIzinController::class, 'tolak'])->name('pengajuan-izin.tolak');
    Route::delete('pengajuan-izin/{pengajuan_izin}', [PengajuanIzinController::class, 'destroy'])->name('pengajuan-izin.destroy');


    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');

    Route::get('pengaturan-sekolah', [PengaturanSekolahController::class, 'index'])
    ->name('pengaturan-sekolah.index');
    Route::put('pengaturan-sekolah', [PengaturanSekolahController::class, 'update'])
    ->name('pengaturan-sekolah.update');

    Route::get('log-aktivitas', [ActivityLogController::class, 'index'])
    ->name('log-aktivitas.index');
    Route::delete('log-aktivitas/bersihkan', [ActivityLogController::class, 'bersihkan'])
    ->name('log-aktivitas.bersihkan');
});

Route::prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
