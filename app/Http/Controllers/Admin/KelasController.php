<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(Request $request): View
    {
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tanggal_mulai')->get();
        $tahunAktif = $tahunAjarans->firstWhere('is_aktif', true);

        // Default filter menggunakan tahun ajaran aktif jika filter tahun_ajaran_id belum dipilih
        $selectedTahunId = $request->filled('tahun_ajaran_id')
            ? $request->tahun_ajaran_id
            : ($tahunAktif?->id);

        $kelasList = Kelas::query()
            ->with(['tahunAjaran', 'waliKelas'])
            ->when($selectedTahunId, fn ($q) => $q->where('tahun_ajaran_id', $selectedTahunId))
            ->when($request->tingkat, fn ($q, $tingkat) => $q->where('tingkat', $tingkat))
            ->when($request->search, fn ($q, $search) => $q->where('nama', 'like', "%{$search}%"))
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.kelas.index', compact(
            'kelasList',
            'tahunAjarans',
            'selectedTahunId'
        ));
    }

    public function create(): View
    {
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tanggal_mulai')->get();
        $gurus = User::whereIn('role', ['guru', 'petugas'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.admin.kelas.create', compact('tahunAjarans', 'gurus'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'tingkat'         => ['required', 'integer', Rule::in([10, 11, 12])],
            'nama'            => [
                'required',
                'string',
                'max:30',
                Rule::unique('kelas')->where(fn ($q) => $q
                    ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                    ->whereNull('deleted_at')),
            ],
            'wali_kelas_id'   => ['nullable', 'exists:users,id'],
        ], [
            'nama.unique' => 'Nama kelas ini sudah terdaftar pada tahun ajaran yang dipilih.',
        ]);

        Kelas::create($validated);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas baru berhasil ditambahkan.');
    }

    public function edit(Kelas $kela): View
    {
        $kelas = $kela;
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tanggal_mulai')->get();
        $gurus = User::whereIn('role', ['guru', 'petugas'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('pages.admin.kelas.edit', compact('kelas', 'tahunAjarans', 'gurus'));
    }

    public function update(Request $request, Kelas $kela): RedirectResponse
    {
        $kelas = $kela;

        $validated = $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'tingkat'         => ['required', 'integer', Rule::in([10, 11, 12])],
            'nama'            => [
                'required',
                'string',
                'max:30',
                Rule::unique('kelas')
                    ->where(fn ($q) => $q
                        ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                        ->whereNull('deleted_at'))
                    ->ignore($kelas->id),
            ],
            'wali_kelas_id'   => ['nullable', 'exists:users,id'],
        ], [
            'nama.unique' => 'Nama kelas ini sudah terdaftar pada tahun ajaran yang dipilih.',
        ]);

        $kelas->update($validated);

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela): RedirectResponse
    {
        $kela->delete(); // Soft delete sesuai aturan BR-12

        return redirect()
            ->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }
}