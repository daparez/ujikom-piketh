<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SekolahSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengaturanSekolahController extends Controller
{
    public function index(): View
    {
        $setting = SekolahSetting::firstOrCreate(
            ['id' => 1],
            [
                'nama_sekolah'              => 'SMK Negeri / Swasta',
                'jam_masuk_sekolah'         => '07:00:00',
                'toleransi_terlambat_menit' => 10,
                'geofencing_aktif'          => false,
                'radius_meter'              => 100,
            ]
        );

        return view('pages.admin.pengaturan-sekolah.index', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = SekolahSetting::firstOrCreate(['id' => 1], ['nama_sekolah' => 'Sekolah']);

        $validated = $request->validate([
            'nama_sekolah'              => ['required', 'string', 'max:150'],
            'npsn'                      => ['nullable', 'string', 'max:20'],
            'alamat'                    => ['nullable', 'string', 'max:500'],
            'telepon'                   => ['nullable', 'string', 'max:25'],
            'email'                     => ['nullable', 'email', 'max:100'],
            'website'                   => ['nullable', 'string', 'max:100'],
            'kepala_sekolah'            => ['nullable', 'string', 'max:100'],
            'nip_kepala_sekolah'        => ['nullable', 'string', 'max:30'],
            'logo'                      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'jam_masuk_sekolah'         => ['required', 'date_format:H:i'],
            'toleransi_terlambat_menit' => ['required', 'integer', 'min:0', 'max:120'],
            'geofencing_aktif'          => ['nullable', 'boolean'],
            'latitude'                  => ['nullable', 'numeric', 'between:-90,90', 'required_if:geofencing_aktif,1'],
            'longitude'                 => ['nullable', 'numeric', 'between:-180,180', 'required_if:geofencing_aktif,1'],
            'radius_meter'              => ['required', 'integer', 'min:10', 'max:5000'],
        ], [
            'latitude.required_if'  => 'Koordinat Latitude wajib diisi jika fitur Geofencing diaktifkan.',
            'longitude.required_if' => 'Koordinat Longitude wajib diisi jika fitur Geofencing diaktifkan.',
        ]);

        $validated['geofencing_aktif'] = $request->boolean('geofencing_aktif');

        if ($request->hasFile('logo')) {
            if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                Storage::disk('public')->delete($setting->logo);
            }
            $validated['logo'] = $request->file('logo')->store('sekolah', 'public');
        }

        $setting->update($validated);

        return redirect()
            ->route('admin.pengaturan-sekolah.index')
            ->with('success', 'Pengaturan sekolah berhasil disimpan.');
    }
}