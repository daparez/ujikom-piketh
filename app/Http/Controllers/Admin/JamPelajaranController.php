<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JamPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JamPelajaranController extends Controller
{
    public function index(): View
    {
        $jamPelajarans = JamPelajaran::query()
            ->orderBy('jam_ke')
            ->get();

        return view('pages.admin.jam-pelajaran.index', compact('jamPelajarans'));
    }

    public function create(): View
    {
        // Saran nomor jam_ke berikutnya secara otomatis
        $nextJamKe = (JamPelajaran::max('jam_ke') ?? 0) + 1;

        return view('pages.admin.jam-pelajaran.create', compact('nextJamKe'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jam_ke'       => ['required', 'integer', 'min:1', 'max:20', 'unique:jam_pelajarans,jam_ke'],
            'jam_mulai'    => ['required', 'date_format:H:i'],
            'jam_selesai'  => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'is_istirahat' => ['nullable', 'boolean'],
        ], [
            'jam_ke.unique'     => 'Urutan jam ke- ini sudah terdaftar.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        $validated['is_istirahat'] = $request->boolean('is_istirahat');

        JamPelajaran::create($validated);

        return redirect()
            ->route('admin.jam-pelajaran.index')
            ->with('success', 'Slot jam pelajaran berhasil ditambahkan.');
    }

    public function edit(JamPelajaran $jamPelajaran): View
    {
        return view('pages.admin.jam-pelajaran.edit', compact('jamPelajaran'));
    }

    public function update(Request $request, JamPelajaran $jamPelajaran): RedirectResponse
    {
        $validated = $request->validate([
            'jam_ke'       => ['required', 'integer', 'min:1', 'max:20', Rule::unique('jam_pelajarans', 'jam_ke')->ignore($jamPelajaran->id)],
            'jam_mulai'    => ['required', 'date_format:H:i'],
            'jam_selesai'  => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'is_istirahat' => ['nullable', 'boolean'],
        ], [
            'jam_ke.unique'     => 'Urutan jam ke- ini sudah terdaftar.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        $validated['is_istirahat'] = $request->boolean('is_istirahat');

        $jamPelajaran->update($validated);

        return redirect()
            ->route('admin.jam-pelajaran.index')
            ->with('success', 'Data jam pelajaran berhasil diperbarui.');
    }

    public function destroy(JamPelajaran $jamPelajaran): RedirectResponse
    {
        // Aturan BR-12: Cegah hapus jika sudah dipakai di Jadwal Pelajaran
        if (Schema::hasTable('jadwal_pelajarans')) {
            $dipakai = DB::table('jadwal_pelajarans')
                ->where('jam_pelajaran_id', $jamPelajaran->id)
                ->exists();

            if ($dipakai) {
                return back()->with('error', 'Slot jam pelajaran tidak dapat dihapus karena sudah digunakan pada Jadwal Pelajaran.');
            }
        }

        $jamPelajaran->delete();

        return redirect()
            ->route('admin.jam-pelajaran.index')
            ->with('success', 'Slot jam pelajaran berhasil dihapus.');
    }
}