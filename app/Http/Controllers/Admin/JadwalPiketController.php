<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPiket;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JadwalPiketController extends Controller
{
    public function index(Request $request): View
    {
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tanggal_mulai')->get();
        $tahunAktif = $tahunAjarans->firstWhere('is_aktif', true);

        $selectedTahunId = $request->filled('tahun_ajaran_id')
            ? $request->tahun_ajaran_id
            : ($tahunAktif?->id);

        $petugasList = User::whereIn('role', ['petugas', 'guru'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $jadwalPikets = JadwalPiket::query()
            ->with(['tahunAjaran', 'petugas'])
            ->filter([
                'tahun_ajaran_id' => $selectedTahunId,
                'hari'            => $request->hari,
                'petugas_id'      => $request->petugas_id,
            ])
            ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu')")
            ->orderByDesc('is_koordinator')
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        $hariList = JadwalPiket::HARI_LIST;

        return view('pages.admin.jadwal-piket.index', compact(
            'jadwalPikets',
            'tahunAjarans',
            'selectedTahunId',
            'petugasList',
            'hariList'
        ));
    }

    public function create(): View
    {
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tanggal_mulai')->get();
        $petugasList = User::whereIn('role', ['petugas', 'guru'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $hariList = JadwalPiket::HARI_LIST;

        return view('pages.admin.jadwal-piket.create', compact(
            'tahunAjarans',
            'petugasList',
            'hariList'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'hari'            => ['required', Rule::in(array_keys(JadwalPiket::HARI_LIST))],
            'petugas_id'      => [
                'required',
                'exists:users,id',
                Rule::unique('jadwal_pikets')->where(fn ($q) => $q
                    ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                    ->where('hari', $request->hari)),
            ],
            'is_koordinator'  => ['nullable', 'boolean'],
            'keterangan'      => ['nullable', 'string', 'max:150'],
        ], [
            'petugas_id.unique' => 'Guru/Petugas ini sudah terjadwal piket pada hari dan tahun ajaran tersebut.',
        ]);

        $validated['is_koordinator'] = $request->boolean('is_koordinator');

        DB::transaction(function () use ($validated) {
            if ($validated['is_koordinator']) {
                JadwalPiket::where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
                    ->where('hari', $validated['hari'])
                    ->where('is_koordinator', true)
                    ->update(['is_koordinator' => false]);
            }

            JadwalPiket::create($validated);
        });

        return redirect()
            ->route('admin.jadwal-piket.index')
            ->with('success', 'Jadwal petugas piket berhasil ditambahkan.');
    }

    public function edit(JadwalPiket $jadwalPiket): View
    {
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tanggal_mulai')->get();
        $petugasList = User::whereIn('role', ['petugas', 'guru'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $hariList = JadwalPiket::HARI_LIST;

        return view('pages.admin.jadwal-piket.edit', compact(
            'jadwalPiket',
            'tahunAjarans',
            'petugasList',
            'hariList'
        ));
    }

    public function update(Request $request, JadwalPiket $jadwalPiket): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => ['required', 'exists:tahun_ajarans,id'],
            'hari'            => ['required', Rule::in(array_keys(JadwalPiket::HARI_LIST))],
            'petugas_id'      => [
                'required',
                'exists:users,id',
                Rule::unique('jadwal_pikets')
                    ->where(fn ($q) => $q
                        ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
                        ->where('hari', $request->hari))
                    ->ignore($jadwalPiket->id),
            ],
            'is_koordinator'  => ['nullable', 'boolean'],
            'keterangan'      => ['nullable', 'string', 'max:150'],
        ], [
            'petugas_id.unique' => 'Guru/Petugas ini sudah terjadwal piket pada hari dan tahun ajaran tersebut.',
        ]);

        $validated['is_koordinator'] = $request->boolean('is_koordinator');

        DB::transaction(function () use ($validated, $jadwalPiket) {
            if ($validated['is_koordinator']) {
                JadwalPiket::where('id', '!=', $jadwalPiket->id)
                    ->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
                    ->where('hari', $validated['hari'])
                    ->where('is_koordinator', true)
                    ->update(['is_koordinator' => false]);
            }

            $jadwalPiket->update($validated);
        });

        return redirect()
            ->route('admin.jadwal-piket.index')
            ->with('success', 'Jadwal petugas piket berhasil diperbarui.');
    }

    public function destroy(JadwalPiket $jadwalPiket): RedirectResponse
    {
        $jadwalPiket->delete();

        return redirect()
            ->route('admin.jadwal-piket.index')
            ->with('success', 'Jadwal petugas piket berhasil dihapus.');
    }
}