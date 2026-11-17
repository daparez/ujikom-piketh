<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MapelController extends Controller
{
    public function index(Request $request): View
    {
        $mapels = MataPelajaran::query()
            ->search($request->search)
            ->orderBy('kode')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.mapel.index', compact('mapels'));
    }

    public function create(): View
    {
        return view('pages.admin.mapel.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'kode' => strtoupper(trim((string) $request->kode)),
        ]);

        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:15', 'unique:mata_pelajarans,kode'],
            'nama' => ['required', 'string', 'max:100'],
        ], [
            'kode.unique' => 'Kode mata pelajaran ini sudah digunakan.',
        ]);

        MataPelajaran::create($validated);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran baru berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mapel): View
    {
        return view('pages.admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, MataPelajaran $mapel): RedirectResponse
    {
        $request->merge([
            'kode' => strtoupper(trim((string) $request->kode)),
        ]);

        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:15', Rule::unique('mata_pelajarans', 'kode')->ignore($mapel->id)],
            'nama' => ['required', 'string', 'max:100'],
        ], [
            'kode.unique' => 'Kode mata pelajaran ini sudah digunakan.',
        ]);

        $mapel->update($validated);

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Data mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mapel): RedirectResponse
    {
        // Aturan BR-12: Cegah hapus permanen jika sudah dipakai pada transaksi jadwal pelajaran
        if (Schema::hasTable('jadwal_pelajarans')) {
            $dipakai = DB::table('jadwal_pelajarans')
                ->where('mata_pelajaran_id', $mapel->id)
                ->exists();

            if ($dipakai) {
                return back()->with('error', 'Mata pelajaran tidak dapat dihapus karena sudah digunakan pada Jadwal Pelajaran.');
            }
        }

        $mapel->delete();

        return redirect()
            ->route('admin.mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}