<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiswaController extends Controller
{
    public function index(Request $request): View
    {
        $tahunAktif = TahunAjaran::where('is_aktif', true)->first();

        $kelasList = Kelas::query()
            ->with('tahunAjaran')
            ->when($tahunAktif, fn ($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        $siswas = Siswa::query()
            ->with(['kelas.tahunAjaran'])
            ->filter($request->only(['search', 'kelas_id', 'status']))
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.siswa.index', compact('siswas', 'kelasList'));
    }

    public function create(): View
    {
        $tahunAktif = TahunAjaran::where('is_aktif', true)->first();

        $kelasList = Kelas::query()
            ->with('tahunAjaran')
            ->when($tahunAktif, fn ($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view('pages.admin.siswa.create', compact('kelasList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kelas_id'      => ['required', 'exists:kelas,id'],
            'nis'           => ['required', 'string', 'max:20', 'unique:siswas,nis'],
            'nisn'          => ['nullable', 'string', 'max:20', 'unique:siswas,nisn'],
            'nama'          => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'no_hp_ortu'    => ['nullable', 'string', 'max:20'],
            'status'        => ['required', Rule::in(['aktif', 'lulus', 'pindah', 'keluar'])],
        ], [
            'nis.unique'  => 'NIS ini sudah terdaftar pada siswa lain.',
            'nisn.unique' => 'NISN ini sudah terdaftar pada siswa lain.',
        ]);

        Siswa::create($validated);

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Data siswa baru berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa): View
    {
        $kelasList = Kelas::query()
            ->with('tahunAjaran')
            ->orderByDesc('tahun_ajaran_id')
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view('pages.admin.siswa.edit', compact('siswa', 'kelasList'));
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $validated = $request->validate([
            'kelas_id'      => ['required', 'exists:kelas,id'],
            'nis'           => ['required', 'string', 'max:20', Rule::unique('siswas', 'nis')->ignore($siswa->id)],
            'nisn'          => ['nullable', 'string', 'max:20', Rule::unique('siswas', 'nisn')->ignore($siswa->id)],
            'nama'          => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'no_hp_ortu'    => ['nullable', 'string', 'max:20'],
            'status'        => ['required', Rule::in(['aktif', 'lulus', 'pindah', 'keluar'])],
        ], [
            'nis.unique'  => 'NIS ini sudah terdaftar pada siswa lain.',
            'nisn.unique' => 'NISN ini sudah terdaftar pada siswa lain.',
        ]);

        $siswa->update($validated);

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $siswa->delete(); // Soft delete sesuai aturan BR-12

        return redirect()
            ->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus (arsip soft-delete).');
    }
}