<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JadwalPelajaranController extends Controller
{
    public function index(Request $request): View
    {
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tanggal_mulai')->get();
        $tahunAktif = $tahunAjarans->firstWhere('is_aktif', true);

        $selectedTahunId = $request->filled('tahun_ajaran_id')
            ? $request->tahun_ajaran_id
            : ($tahunAktif?->id);

        $kelasList = Kelas::query()
            ->when($selectedTahunId, fn ($q) => $q->where('tahun_ajaran_id', $selectedTahunId))
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        $gurus = User::whereIn('role', ['guru', 'petugas'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $jadwals = JadwalPelajaran::query()
            ->with(['tahunAjaran', 'kelas', 'mataPelajaran', 'guru', 'jamPelajaran'])
            ->filter([
                'tahun_ajaran_id' => $selectedTahunId,
                'kelas_id'        => $request->kelas_id,
                'guru_id'         => $request->guru_id,
                'hari'            => $request->hari,
            ])
            ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu')")
            ->orderBy('kelas_id')
            ->orderBy('jam_pelajaran_id')
            ->paginate(20)
            ->withQueryString();

        $hariList = JadwalPelajaran::HARI_LIST;

        return view('pages.admin.jadwal-pelajaran.index', compact(
            'jadwals',
            'tahunAjarans',
            'selectedTahunId',
            'kelasList',
            'gurus',
            'hariList'
        ));
    }

    public function create(): View
    {
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tanggal_mulai')->get();
        $tahunAktif = $tahunAjarans->firstWhere('is_aktif', true);

        $kelasList = Kelas::query()
            ->with('tahunAjaran')
            ->when($tahunAktif, fn ($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        $mapels = MataPelajaran::orderBy('nama')->get();

        $gurus = User::whereIn('role', ['guru', 'petugas'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Hanya ambil slot jam KBM (bukan jam istirahat)
        $jamPelajarans = JamPelajaran::where('is_istirahat', false)
            ->orderBy('jam_ke')
            ->get();

        $hariList = JadwalPelajaran::HARI_LIST;

        return view('pages.admin.jadwal-pelajaran.create', compact(
            'tahunAjarans',
            'kelasList',
            'mapels',
            'gurus',
            'jamPelajarans',
            'hariList'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_ajaran_id'   => ['required', 'exists:tahun_ajarans,id'],
            'kelas_id'          => ['required', 'exists:kelas,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajarans,id'],
            'guru_id'           => ['required', 'exists:users,id'],
            'jam_pelajaran_id'  => [
                'required',
                Rule::exists('jam_pelajarans', 'id')->where('is_istirahat', false),
            ],
            'hari'              => ['required', Rule::in(array_keys(JadwalPelajaran::HARI_LIST))],
            'ruangan'           => ['nullable', 'string', 'max:30'],
        ], [
            'jam_pelajaran_id.exists' => 'Slot jam pelajaran tidak valid atau merupakan jam istirahat.',
        ]);

        // Cek bentrok jadwal kelas pada hari & jam yang sama
        $bentrokKelas = JadwalPelajaran::with(['mataPelajaran', 'guru'])
            ->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
            ->where('kelas_id', $validated['kelas_id'])
            ->where('hari', $validated['hari'])
            ->where('jam_pelajaran_id', $validated['jam_pelajaran_id'])
            ->first();

        if ($bentrokKelas) {
            return back()->withInput()->withErrors([
                'jam_pelajaran_id' => "Bentrok Kelas: Kelas ini sudah memiliki jadwal {$bentrokKelas->mataPelajaran->nama} ({$bentrokKelas->guru->name}) pada hari dan jam tersebut.",
            ]);
        }

        // Cek bentrok jadwal guru mengajar di kelas lain pada hari & jam yang sama
        $bentrokGuru = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
            ->where('guru_id', $validated['guru_id'])
            ->where('hari', $validated['hari'])
            ->where('jam_pelajaran_id', $validated['jam_pelajaran_id'])
            ->first();

        if ($bentrokGuru) {
            return back()->withInput()->withErrors([
                'guru_id' => "Bentrok Guru: Guru ini sudah terjadwal mengajar {$bentrokGuru->mataPelajaran->nama} di kelas {$bentrokGuru->kelas->nama} pada hari dan jam tersebut.",
            ]);
        }

        JadwalPelajaran::create($validated);

        return redirect()
            ->route('admin.jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function edit(JadwalPelajaran $jadwalPelajaran): View
    {
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tanggal_mulai')->get();

        $kelasList = Kelas::query()
            ->with('tahunAjaran')
            ->where('tahun_ajaran_id', $jadwalPelajaran->tahun_ajaran_id)
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        $mapels = MataPelajaran::orderBy('nama')->get();

        $gurus = User::whereIn('role', ['guru', 'petugas'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $jamPelajarans = JamPelajaran::where('is_istirahat', false)
            ->orderBy('jam_ke')
            ->get();

        $hariList = JadwalPelajaran::HARI_LIST;

        return view('pages.admin.jadwal-pelajaran.edit', compact(
            'jadwalPelajaran',
            'tahunAjarans',
            'kelasList',
            'mapels',
            'gurus',
            'jamPelajarans',
            'hariList'
        ));
    }

    public function update(Request $request, JadwalPelajaran $jadwalPelajaran): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_ajaran_id'   => ['required', 'exists:tahun_ajarans,id'],
            'kelas_id'          => ['required', 'exists:kelas,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajarans,id'],
            'guru_id'           => ['required', 'exists:users,id'],
            'jam_pelajaran_id'  => [
                'required',
                Rule::exists('jam_pelajarans', 'id')->where('is_istirahat', false),
            ],
            'hari'              => ['required', Rule::in(array_keys(JadwalPelajaran::HARI_LIST))],
            'ruangan'           => ['nullable', 'string', 'max:30'],
        ]);

        // Cek bentrok kelas (kecuali record ini sendiri)
        $bentrokKelas = JadwalPelajaran::with(['mataPelajaran', 'guru'])
            ->where('id', '!=', $jadwalPelajaran->id)
            ->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
            ->where('kelas_id', $validated['kelas_id'])
            ->where('hari', $validated['hari'])
            ->where('jam_pelajaran_id', $validated['jam_pelajaran_id'])
            ->first();

        if ($bentrokKelas) {
            return back()->withInput()->withErrors([
                'jam_pelajaran_id' => "Bentrok Kelas: Kelas ini sudah memiliki jadwal {$bentrokKelas->mataPelajaran->nama} ({$bentrokKelas->guru->name}) pada hari dan jam tersebut.",
            ]);
        }

        // Cek bentrok guru (kecuali record ini sendiri)
        $bentrokGuru = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('id', '!=', $jadwalPelajaran->id)
            ->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
            ->where('guru_id', $validated['guru_id'])
            ->where('hari', $validated['hari'])
            ->where('jam_pelajaran_id', $validated['jam_pelajaran_id'])
            ->first();

        if ($bentrokGuru) {
            return back()->withInput()->withErrors([
                'guru_id' => "Bentrok Guru: Guru ini sudah terjadwal mengajar {$bentrokGuru->mataPelajaran->nama} di kelas {$bentrokGuru->kelas->nama} pada hari dan jam tersebut.",
            ]);
        }

        $jadwalPelajaran->update($validated);

        return redirect()
            ->route('admin.jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy(JadwalPelajaran $jadwalPelajaran): RedirectResponse
    {
        $jadwalPelajaran->delete();

        return redirect()
            ->route('admin.jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }
}