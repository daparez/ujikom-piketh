<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisPelanggaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JenisPelanggaranController extends Controller
{
    public function index(Request $request): View
    {
        $jenisPelanggarans = JenisPelanggaran::query()
            ->filter($request->only(['search', 'kategori']))
            ->orderBy('kode')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.jenis-pelanggaran.index', compact('jenisPelanggarans'));
    }

    public function create(): View
    {
        // Saran kode otomatis berikutnya (misal P-001)
        $total = JenisPelanggaran::withTrashed()->count() + 1;
        $nextKode = 'P-' . str_pad((string) $total, 3, '0', STR_PAD_LEFT);

        return view('pages.admin.jenis-pelanggaran.create', compact('nextKode'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'kode' => strtoupper(trim((string) $request->kode)),
        ]);

        $validated = $request->validate([
            'kode'     => ['required', 'string', 'max:10', 'unique:jenis_pelanggarans,kode'],
            'nama'     => ['required', 'string', 'max:150'],
            'kategori' => ['required', Rule::in(['ringan', 'sedang', 'berat'])],
            'poin'     => ['required', 'integer', 'min:1', 'max:500'],
            'is_aktif' => ['nullable', 'boolean'],
        ], [
            'kode.unique' => 'Kode jenis pelanggaran ini sudah digunakan.',
        ]);

        $validated['is_aktif'] = $request->boolean('is_aktif');

        JenisPelanggaran::create($validated);

        return redirect()
            ->route('admin.jenis-pelanggaran.index')
            ->with('success', 'Jenis pelanggaran baru berhasil ditambahkan.');
    }

    public function edit(JenisPelanggaran $jenisPelanggaran): View
    {
        return view('pages.admin.jenis-pelanggaran.edit', compact('jenisPelanggaran'));
    }

    public function update(Request $request, JenisPelanggaran $jenisPelanggaran): RedirectResponse
    {
        $request->merge([
            'kode' => strtoupper(trim((string) $request->kode)),
        ]);

        $validated = $request->validate([
            'kode'     => ['required', 'string', 'max:10', Rule::unique('jenis_pelanggarans', 'kode')->ignore($jenisPelanggaran->id)],
            'nama'     => ['required', 'string', 'max:150'],
            'kategori' => ['required', Rule::in(['ringan', 'sedang', 'berat'])],
            'poin'     => ['required', 'integer', 'min:1', 'max:500'],
            'is_aktif' => ['nullable', 'boolean'],
        ], [
            'kode.unique' => 'Kode jenis pelanggaran ini sudah digunakan.',
        ]);

        $validated['is_aktif'] = $request->boolean('is_aktif');

        $jenisPelanggaran->update($validated);

        return redirect()
            ->route('admin.jenis-pelanggaran.index')
            ->with('success', 'Data jenis pelanggaran berhasil diperbarui.');
    }

    public function destroy(JenisPelanggaran $jenisPelanggaran): RedirectResponse
    {
        $jenisPelanggaran->update(['is_aktif' => false]);
        $jenisPelanggaran->delete(); // Soft delete sesuai aturan BR-12

        return redirect()
            ->route('admin.jenis-pelanggaran.index')
            ->with('success', 'Jenis pelanggaran berhasil dihapus (arsip soft-delete).');
    }
}