@extends('layouts.admin.admin')

@section('title', 'Edit Jenis Pelanggaran')
@section('page-title', 'Edit Jenis Pelanggaran')

@section('content')
    <div class="max-w-2xl bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Edit Pelanggaran: {{ $jenisPelanggaran->kode }}</h2>
                <p class="text-xs text-slate-500">Perbarui nama pelanggaran, kategori, bobot poin, atau status</p>
            </div>
            <a href="{{ route('admin.jenis-pelanggaran.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('admin.jenis-pelanggaran.update', $jenisPelanggaran) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- Kode Pelanggaran -->
                <div>
                    <label for="kode" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Kode Pelanggaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="kode" name="kode" value="{{ old('kode', $jenisPelanggaran->kode) }}" required maxlength="10"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm font-mono uppercase border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('kode') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('kode') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Bobot Poin -->
                <div>
                    <label for="poin" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Bobot Poin <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" id="poin" name="poin" value="{{ old('poin', $jenisPelanggaran->poin) }}" min="1" max="500" required
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('poin') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('poin') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Nama Pelanggaran -->
                <div class="sm:col-span-2">
                    <label for="nama" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Nama / Uraian Pelanggaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $jenisPelanggaran->nama) }}" required maxlength="150"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('nama') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('nama') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Kategori -->
                <div class="sm:col-span-2">
                    <label for="kategori" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Kategori Pelanggaran <span class="text-rose-500">*</span>
                    </label>
                    <select id="kategori" name="kategori" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="ringan" @selected(old('kategori', $jenisPelanggaran->kategori) === 'ringan')>Ringan</option>
                        <option value="sedang" @selected(old('kategori', $jenisPelanggaran->kategori) === 'sedang')>Sedang</option>
                        <option value="berat" @selected(old('kategori', $jenisPelanggaran->kategori) === 'berat')>Berat</option>
                    </select>
                    @error('kategori') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Status Aktif -->
                <div class="sm:col-span-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_aktif" value="1" @checked(old('is_aktif', $jenisPelanggaran->is_aktif))
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs font-medium text-slate-700">Aktif (dapat dipilih saat mencatat pelanggaran siswa)</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.jenis-pelanggaran.index') }}"
                   class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection