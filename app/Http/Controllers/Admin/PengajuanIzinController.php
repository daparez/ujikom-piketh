<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\KelasKosong;
use App\Models\PengajuanIzin;
use App\Models\PresensiGuru;
use App\Models\TahunAjaran;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengajuanIzinController extends Controller
{
    public function index(Request $request): View
    {
        $pengajuans = PengajuanIzin::query()
            ->with(['guru', 'pemroses'])
            ->filter($request->only(['search', 'status', 'jenis']))
            ->orderByRaw("FIELD(status, 'menunggu', 'disetujui', 'ditolak')")
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $jumlahMenunggu = PengajuanIzin::where('status', 'menunggu')->count();

        return view('pages.admin.pengajuan-izin.index', compact('pengajuans', 'jumlahMenunggu'));
    }

    public function show(PengajuanIzin $pengajuanIzin): View
    {
        $pengajuanIzin->load(['guru', 'pemroses']);

        // Hitung jadwal mengajar guru yang terdampak pada rentang tanggal izin
        $jadwalTerdampak = $this->getJadwalTerdampak($pengajuanIzin);

        return view('pages.admin.pengajuan-izin.show', compact('pengajuanIzin', 'jadwalTerdampak'));
    }

    public function setujui(Request $request, PengajuanIzin $pengajuanIzin): RedirectResponse
    {
        if ($pengajuanIzin->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan izin ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($pengajuanIzin, $validated) {
            $pengajuanIzin->update([
                'status'        => 'disetujui',
                'catatan_admin' => $validated['catatan_admin'] ?? null,
                'diproses_oleh' => Auth::id(),
                'diproses_pada' => now(),
            ]);

            // Otomatis catat presensi guru & buat tiket kelas kosong untuk jadwal yang terdampak
            $jadwalTerdampak = $this->getJadwalTerdampak($pengajuanIzin);
            $statusPresensi = $pengajuanIzin->jenis === 'sakit' ? 'sakit' : 'izin';

            foreach ($jadwalTerdampak as $item) {
                $tanggalStr = $item['tanggal']->toDateString();
                $jadwal = $item['jadwal'];

                PresensiGuru::updateOrCreate(
                    [
                        'jadwal_pelajaran_id' => $jadwal->id,
                        'tanggal'             => $tanggalStr,
                    ],
                    [
                        'guru_id'      => $pengajuanIzin->guru_id,
                        'status'       => $statusPresensi,
                        'keterangan'   => 'Izin disetujui: ' . $pengajuanIzin->alasan,
                        'dicatat_oleh' => Auth::id(),
                    ]
                );

                KelasKosong::firstOrCreate(
                    [
                        'jadwal_pelajaran_id' => $jadwal->id,
                        'tanggal'             => $tanggalStr,
                    ],
                    [
                        'alasan'        => $pengajuanIzin->jenis,
                        'status'        => 'kosong',
                        'catatan_tugas' => $pengajuanIzin->pesan_tugas,
                        'petugas_id'    => Auth::id(),
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.pengajuan-izin.index')
            ->with('success', 'Pengajuan izin disetujui dan jadwal kelas kosong otomatis diteruskan ke petugas piket.');
    }

    public function tolak(Request $request, PengajuanIzin $pengajuanIzin): RedirectResponse
    {
        if ($pengajuanIzin->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan izin ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'catatan_admin' => ['required', 'string', 'max:500'],
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi agar guru mengetahui penyebabnya.',
        ]);

        $pengajuanIzin->update([
            'status'        => 'ditolak',
            'catatan_admin' => $validated['catatan_admin'],
            'diproses_oleh' => Auth::id(),
            'diproses_pada' => now(),
        ]);

        return redirect()
            ->route('admin.pengajuan-izin.index')
            ->with('success', 'Pengajuan izin guru telah ditolak.');
    }

    public function destroy(PengajuanIzin $pengajuanIzin): RedirectResponse
    {
        if ($pengajuanIzin->lampiran && Storage::disk('public')->exists($pengajuanIzin->lampiran)) {
            Storage::disk('public')->delete($pengajuanIzin->lampiran);
        }

        $pengajuanIzin->delete();

        return redirect()
            ->route('admin.pengajuan-izin.index')
            ->with('success', 'Data pengajuan izin berhasil dihapus.');
    }

    private function getJadwalTerdampak(PengajuanIzin $pengajuan): array
    {
        $mapHari = [
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            7 => 'minggu',
        ];

        $tahunAktif = TahunAjaran::where('is_aktif', true)->first();
        $periode = CarbonPeriod::create($pengajuan->tanggal_mulai, $pengajuan->tanggal_selesai);
        $hasil = [];

        foreach ($periode as $tanggal) {
            $hari = $mapHari[$tanggal->dayOfWeekIso];
            if ($hari === 'minggu') {
                continue;
            }

            $jadwals = JadwalPelajaran::with(['kelas', 'mataPelajaran', 'jamPelajaran'])
                ->when($tahunAktif, fn ($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
                ->where('guru_id', $pengajuan->guru_id)
                ->where('hari', $hari)
                ->orderBy('jam_pelajaran_id')
                ->get();

            foreach ($jadwals as $jadwal) {
                $hasil[] = [
                    'tanggal' => $tanggal->copy(),
                    'hari'    => $hari,
                    'jadwal'  => $jadwal,
                ];
            }
        }

        return $hasil;
    }
}