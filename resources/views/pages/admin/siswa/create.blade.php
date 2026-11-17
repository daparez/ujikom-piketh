@extends('layouts.admin.admin')

@section('title', 'Tambah Siswa')
@section('page-title', 'Tambah Data Siswa')

@section('content')
    <div class="max-w-3xl bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Formulir Biodata Siswa</h2>
                <p class="text-xs text-slate-500">Masukkan identitas lengkap siswa dan pilih kelasnya</p>
            </div>
            <a href="{{ route('admin.siswa.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('admin.siswa.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- Nama Siswa -->
                <div class="sm:col-span-2">
                    <label for="nama" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Nama Lengkap Siswa <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                           placeholder="Masukkan nama lengkap sesuai ijazah/rapor"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('nama') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('nama') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- NIS -->
                <div>
                    <label for="nis" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        NIS (Nomor Induk Siswa) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nis" name="nis" value="{{ old('nis') }}" required
                           placeholder="Contoh: 242510001"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('nis') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('nis') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- NISN -->
                <div>
                    <label for="nisn" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        NISN (Opsional)
                    </label>
                    <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}"
                           placeholder="10 digit NISN"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('nisn') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('nisn') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Kelas -->
                <div>
                    <label for="kelas_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Kelas <span class="text-rose-500">*</span>
                    </label>
                    <select id="kelas_id" name="kelas_id" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" @selected(old('kelas_id') == $kelas->id)>
                                {{ $kelas->nama }} ({{ $kelas->tahunAjaran->nama ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="jenis_kelamin" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Jenis Kelamin <span class="text-rose-500">*</span>
                    </label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki (L)</option>
                        <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan (P)</option>
                    </select>
                    @error('jenis_kelamin') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- No HP Orang Tua -->
                <div>
                    <label for="no_hp_ortu" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        No. HP Orang Tua / Wali
                    </label>
                    <input type="text" id="no_hp_ortu" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}"
                           placeholder="08xxxxxxxxxx"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    @error('no_hp_ortu') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Status Siswa -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Status Siswa <span class="text-rose-500">*</span>
                    </label>
                    <select id="status" name="status" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="aktif" @selected(old('status', 'aktif') === 'aktif')>Aktif</option>
                        <option value="lulus" @selected(old('status') === 'lulus')>Lulus</option>
                        <option value="pindah" @selected(old('status') === 'pindah')>Pindah</option>
                        <option value="keluar" @selected(old('status') === 'keluar')>Keluar</option>
                    </select>
                    @error('status') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.siswa.index') }}"
                   class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs cursor-pointer">
                    Simpan Siswa
                </button>
            </div>
        </form>
    </div>
@endsection