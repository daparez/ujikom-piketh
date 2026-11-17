@extends('layouts.admin.admin')

@section('title', 'Edit Mata Pelajaran')
@section('page-title', 'Edit Mata Pelajaran')

@section('content')
    <div class="max-w-xl bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Edit Mapel: {{ $mapel->nama }}</h2>
                <p class="text-xs text-slate-500">Perbarui kode atau nama mata pelajaran</p>
            </div>
            <a href="{{ route('admin.mapel.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('admin.mapel.update', $mapel) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Kode Mapel -->
            <div>
                <label for="kode" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                    Kode Mata Pelajaran <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="kode" name="kode" value="{{ old('kode', $mapel->kode) }}" required maxlength="15"
                       class="mt-1.5 w-full px-3.5 py-2 text-sm font-mono uppercase border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('kode') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                @error('kode') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- Nama Mapel -->
            <div>
                <label for="nama" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                    Nama Mata Pelajaran <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $mapel->nama) }}" required maxlength="100"
                       class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('nama') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                @error('nama') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.mapel.index') }}"
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