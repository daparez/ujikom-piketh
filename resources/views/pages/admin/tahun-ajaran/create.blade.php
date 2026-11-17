@extends('layouts.admin.admin')

@section('title', 'Tambah Tahun Ajaran')
@section('page-title', 'Tambah Tahun Ajaran Baru')

@section('content')
    <div class="max-w-2xl bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Formulir Tahun Ajaran</h2>
                <p class="text-xs text-slate-500">Tentukan periode tahun ajaran dan rentang tanggal semester</p>
            </div>
            <a href="{{ route('admin.tahun-ajaran.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- Nama Tahun Ajaran -->
                <div>
                    <label for="nama" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Tahun Ajaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', '2026/2027') }}" required
                           placeholder="Contoh: 2026/2027"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('nama') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('nama') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Semester -->
                <div>
                    <label for="semester" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Semester <span class="text-rose-500">*</span>
                    </label>
                    <select id="semester" name="semester" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="ganjil" @selected(old('semester') === 'ganjil')>Ganjil</option>
                        <option value="genap" @selected(old('semester') === 'genap')>Genap</option>
                    </select>
                    @error('semester') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Tanggal Mulai -->
                <div>
                    <label for="tanggal_mulai" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Tanggal Mulai <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('tanggal_mulai') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('tanggal_mulai') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label for="tanggal_selesai" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Tanggal Selesai <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('tanggal_selesai') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('tanggal_selesai') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Jadikan Aktif -->
                <div class="sm:col-span-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_aktif" value="1" @checked(old('is_aktif', true))
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs font-medium text-slate-700">Jadikan sebagai Tahun Ajaran Aktif (tahun ajaran aktif sebelumnya akan otomatis dinonaktifkan)</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.tahun-ajaran.index') }}"
                   class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs cursor-pointer">
                    Simpan Tahun Ajaran
                </button>
            </div>
        </form>
    </div>
@endsection