@extends('layouts.admin.admin')

@section('title', 'Pengaturan Sekolah')
@section('page-title', 'Pengaturan Identitas & Operasional Sekolah')

@section('content')
    <form action="{{ route('admin.pengaturan-sekolah.update') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl space-y-6">
        @csrf
        @method('PUT')

        <!-- Bagian 1: Identitas & Kop Sekolah -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-sm font-semibold text-slate-800">Identitas & Kop Laporan Sekolah</h2>
                <p class="text-xs text-slate-500">Informasi ini ditampilkan pada header aplikasi dan kop cetak laporan resmi</p>
            </div>

            <div class="p-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="nama_sekolah" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Nama Sekolah <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nama_sekolah" name="nama_sekolah" value="{{ old('nama_sekolah', $setting->nama_sekolah) }}" required
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('nama_sekolah') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('nama_sekolah') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="npsn" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">NPSN</label>
                    <input type="text" id="npsn" name="npsn" value="{{ old('npsn', $setting->npsn) }}"
                           placeholder="Contoh: 20212345"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    @error('npsn') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="telepon" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Nomor Telepon</label>
                    <input type="text" id="telepon" name="telepon" value="{{ old('telepon', $setting->telepon) }}"
                           placeholder="(022) 1234567"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    @error('telepon') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Email Resmi Sekolah</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $setting->email) }}"
                           placeholder="info@sekolah.sch.id"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="website" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Website Sekolah</label>
                    <input type="text" id="website" name="website" value="{{ old('website', $setting->website) }}"
                           placeholder="https://sekolah.sch.id"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    @error('website') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="alamat" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Alamat Lengkap Sekolah</label>
                    <textarea id="alamat" name="alamat" rows="2"
                              placeholder="Jl. Pendidikan No. 1, Kecamatan, Kota/Kabupaten"
                              class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">{{ old('alamat', $setting->alamat) }}</textarea>
                    @error('alamat') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="kepala_sekolah" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Nama Kepala Sekolah</label>
                    <input type="text" id="kepala_sekolah" name="kepala_sekolah" value="{{ old('kepala_sekolah', $setting->kepala_sekolah) }}"
                           placeholder="Nama lengkap & gelar kepala sekolah"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    @error('kepala_sekolah') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="nip_kepala_sekolah" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">NIP Kepala Sekolah</label>
                    <input type="text" id="nip_kepala_sekolah" name="nip_kepala_sekolah" value="{{ old('nip_kepala_sekolah', $setting->nip_kepala_sekolah) }}"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    @error('nip_kepala_sekolah') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Logo Sekolah -->
                <div class="sm:col-span-2">
                    <label for="logo" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Logo Sekolah (PNG/JPG/WEBP, Maks. 2 MB)</label>
                    @if($setting->logo)
                        <div class="mt-2 mb-2 flex items-center gap-3">
                            <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo Sekolah"
                                 class="w-14 h-14 object-contain p-1 bg-slate-50 border border-slate-200 rounded-lg">
                            <span class="text-xs text-slate-500">Logo sekolah saat ini</span>
                        </div>
                    @endif
                    <input type="file" id="logo" name="logo" accept="image/png,image/jpeg,image/webp"
                           class="mt-1.5 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('logo') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Bagian 2: Aturan Jam Masuk & Geofencing Presensi -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-slate-800">Jam Masuk & Validasi Lokasi (Geofencing)</h2>
                    <p class="text-xs text-slate-500">Atur batas keterlambatan siswa/guru dan radius koordinat GPS area sekolah</p>
                </div>

                <button type="button" id="btn-ambil-lokasi"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Ambil Koordinat Lokasi Saya</span>
                </button>
            </div>

            <div class="p-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="jam_masuk_sekolah" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Jam Masuk Sekolah <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" id="jam_masuk_sekolah" name="jam_masuk_sekolah"
                           value="{{ old('jam_masuk_sekolah', \Carbon\Carbon::parse($setting->jam_masuk_sekolah)->format('H:i')) }}" required
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    @error('jam_masuk_sekolah') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="toleransi_terlambat_menit" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Toleransi Terlambat (Menit) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" id="toleransi_terlambat_menit" name="toleransi_terlambat_menit"
                           value="{{ old('toleransi_terlambat_menit', $setting->toleransi_terlambat_menit) }}" min="0" max="120" required
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    @error('toleransi_terlambat_menit') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2 pt-2 border-t border-slate-100">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="geofencing_aktif" value="1" @checked(old('geofencing_aktif', $setting->geofencing_aktif))
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs font-semibold text-slate-700">Aktifkan Validasi Radius GPS (Geofencing) saat Presensi</span>
                    </label>
                </div>

                <div>
                    <label for="latitude" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Latitude Sekolah</label>
                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $setting->latitude) }}"
                           placeholder="Contoh: -6.9174639"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm font-mono border rounded-lg focus:outline-none {{ $errors->has('latitude') ? 'border-rose-400' : 'border-slate-300 focus:border-indigo-500' }}">
                    @error('latitude') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="longitude" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Longitude Sekolah</label>
                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $setting->longitude) }}"
                           placeholder="Contoh: 107.6191228"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm font-mono border rounded-lg focus:outline-none {{ $errors->has('longitude') ? 'border-rose-400' : 'border-slate-300 focus:border-indigo-500' }}">
                    @error('longitude') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="radius_meter" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Radius Maksimal Area Sekolah (Meter) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" id="radius_meter" name="radius_meter"
                           value="{{ old('radius_meter', $setting->radius_meter) }}" min="10" max="5000" required
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    @error('radius_meter') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-2.5 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs transition-colors cursor-pointer">
                Simpan Pengaturan Sekolah
            </button>
        </div>
    </form>

    <script>
        document.getElementById('btn-ambil-lokasi')?.addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung fitur geolokasi.');
                return;
            }
            const btn = this;
            btn.disabled = true;
            btn.querySelector('span').textContent = 'Mengambil titik GPS...';

            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    document.getElementById('latitude').value = pos.coords.latitude.toFixed(7);
                    document.getElementById('longitude').value = pos.coords.longitude.toFixed(7);
                    btn.disabled = false;
                    btn.querySelector('span').textContent = 'Koordinat Diperbarui';
                },
                function () {
                    alert('Gagal mengambil lokasi. Pastikan izin lokasi (GPS) diaktifkan pada browser.');
                    btn.disabled = false;
                    btn.querySelector('span').textContent = 'Ambil Koordinat Lokasi Saya';
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });
    </script>
@endsection