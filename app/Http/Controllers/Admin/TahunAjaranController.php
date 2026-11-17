<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TahunAjaranController extends Controller
{
    public function index(Request $request): View
    {
        $tahunAjarans = TahunAjaran::query()
            ->when($request->search, fn ($q, $search) => $q->where('nama', 'like', "%{$search}%"))
            ->orderByDesc('is_aktif')
            ->orderByDesc('tanggal_mulai')
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.tahun-ajaran.index', compact('tahunAjarans'));
    }

    public function create(): View
    {
        return view('pages.admin.tahun-ajaran.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:20',
                Rule::unique('tahun_ajarans')->where(fn ($q) => $q->where('semester', $request->semester)),
            ],
            'semester'        => ['required', Rule::in(['ganjil', 'genap'])],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
            'is_aktif'        => ['nullable', 'boolean'],
        ], [
            'nama.unique'           => 'Kombinasi Tahun Ajaran dan Semester ini sudah ada.',
            'tanggal_selesai.after' => 'Tanggal selesai harus lebih besar dari tanggal mulai.',
        ]);

        $validated['is_aktif'] = $request->boolean('is_aktif');

        DB::transaction(function () use ($validated) {
            // Sesuai Bab 5.2.2: Hanya satu tahun ajaran yang boleh aktif
            if ($validated['is_aktif']) {
                TahunAjaran::where('is_aktif', true)->update(['is_aktif' => false]);
            }

            TahunAjaran::create($validated);
        });

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran baru berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahunAjaran): View
    {
        return view('pages.admin.tahun-ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:20',
                Rule::unique('tahun_ajarans')
                    ->where(fn ($q) => $q->where('semester', $request->semester))
                    ->ignore($tahunAjaran->id),
            ],
            'semester'        => ['required', Rule::in(['ganjil', 'genap'])],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
            'is_aktif'        => ['nullable', 'boolean'],
        ], [
            'nama.unique'           => 'Kombinasi Tahun Ajaran dan Semester ini sudah ada.',
            'tanggal_selesai.after' => 'Tanggal selesai harus lebih besar dari tanggal mulai.',
        ]);

        $validated['is_aktif'] = $request->boolean('is_aktif');

        // Cegah menonaktifkan satu-satunya tahun ajaran yang sedang aktif
        if ($tahunAjaran->is_aktif && ! $validated['is_aktif']) {
            return back()->with('error', 'Tidak dapat menonaktifkan tahun ajaran yang sedang aktif. Aktifkan tahun ajaran lain terlebih dahulu.');
        }

        DB::transaction(function () use ($validated, $tahunAjaran) {
            if ($validated['is_aktif']) {
                TahunAjaran::where('id', '!=', $tahunAjaran->id)
                    ->where('is_aktif', true)
                    ->update(['is_aktif' => false]);
            }

            $tahunAjaran->update($validated);
        });

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Data tahun ajaran berhasil diperbarui.');
    }

    public function aktifkan(TahunAjaran $tahunAjaran): RedirectResponse
    {
        DB::transaction(function () use ($tahunAjaran) {
            TahunAjaran::where('is_aktif', true)->update(['is_aktif' => false]);
            $tahunAjaran->update(['is_aktif' => true]);
        });

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', "Tahun ajaran {$tahunAjaran->nama} (" . ucfirst($tahunAjaran->semester) . ') berhasil diaktifkan.');
    }

    public function destroy(TahunAjaran $tahunAjaran): RedirectResponse
    {
        if ($tahunAjaran->is_aktif) {
            return back()->with('error', 'Tahun ajaran yang sedang aktif tidak dapat dihapus.');
        }

        $tahunAjaran->delete();

        return redirect()
            ->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}