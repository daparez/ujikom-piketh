@extends('layouts.admin.admin')

@section('title', 'Edit Kelas')
@section('page-title', 'Edit Data Kelas')

@section('content')
    <div class="max-w-2xl bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Edit Kelas: {{ $kelas->nama }}</h2>
                <p class="text-xs text-slate-500">Perbarui nama kelas, tingkat, atau wali kelas</p>
            </div>
            <a href="{{ route('admin.kelas.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('admin.kelas.update', $kelas) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- Tahun Ajaran -->
                <div class="sm:col-span-2">
                    <label for="tahun_ajaran_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Tahun Ajaran <span class="text-rose-500">*</span>
                    </label>
                    <select id="tahun_ajaran_id" name="tahun_ajaran_id" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        @foreach($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" @selected(old('tahun_ajaran_id', $kelas->tahun_ajaran_id) == $ta->id)>
                                {{ $ta->nama }} - Semester {{ ucfirst($ta->semester) }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('tahun_ajaran_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Tingkat -->
                <div>
                    <label for="tingkat" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Tingkat Kelas <span class="text-rose-500">*</span>
                    </label>
                    <select id="tingkat" name="tingkat" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="10" @selected(old('tingkat', $kelas->tingkat) == 10)>10 (Kelas X)</option>
                        <option value="11" @selected(old('tingkat', $kelas->tingkat) == 11)>11 (Kelas XI)</option>
                        <option value="12" @selected(old('tingkat', $kelas->tingkat) == 12)>12 (Kelas XII)</option>
                    </select>
                    @error('tingkat') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Nama Kelas -->
                <div>
                    <label for="nama" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Nama Kelas <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $kelas->nama) }}" required
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('nama') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('nama') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Wali Kelas -->
                <div class="sm:col-span-2">
                    <label for="wali_kelas_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Wali Kelas (Opsional)
                    </label>
                    <select id="wali_kelas_id" name="wali_kelas_id"
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">-- Pilih Guru Wali Kelas --</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" @selected(old('wali_kelas_id', $kelas->wali_kelas_id) == $guru->id)>
                                {{ $guru->name }} {{ $guru->nip ? "(NIP. {$guru->nip})" : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('wali_kelas_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.kelas.index') }}"
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