@extends('layouts.admin.admin')

@section('title', 'Tambah Jadwal Piket')
@section('page-title', 'Tambah Petugas Piket')

@section('content')
    <div class="max-w-2xl bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Formulir Jadwal Piket</h2>
                <p class="text-xs text-slate-500">Tugaskan guru atau petugas piket untuk hari tertentu</p>
            </div>
            <a href="{{ route('admin.jadwal-piket.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('admin.jadwal-piket.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- Tahun Ajaran -->
                <div class="sm:col-span-2">
                    <label for="tahun_ajaran_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Tahun Ajaran <span class="text-rose-500">*</span>
                    </label>
                    <select id="tahun_ajaran_id" name="tahun_ajaran_id" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        @foreach($tahunAjarans as $ta)
                            <option value="{{ $ta->id }}" @selected(old('tahun_ajaran_id', $tahunAjarans->firstWhere('is_aktif', true)?->id) == $ta->id)>
                                {{ $ta->nama }} - Semester {{ ucfirst($ta->semester) }} {{ $ta->is_aktif ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('tahun_ajaran_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Hari Piket -->
                <div>
                    <label for="hari" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Hari Piket <span class="text-rose-500">*</span>
                    </label>
                    <select id="hari" name="hari" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        @foreach($hariList as $key => $label)
                            <option value="{{ $key }}" @selected(old('hari') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('hari') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Petugas / Guru -->
                <div>
                    <label for="petugas_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Guru / Petugas Piket <span class="text-rose-500">*</span>
                    </label>
                    <select id="petugas_id" name="petugas_id" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border rounded-lg focus:outline-none {{ $errors->has('petugas_id') ? 'border-rose-400' : 'border-slate-300 focus:border-indigo-500' }}">
                        <option value="">-- Pilih Guru / Petugas --</option>
                        @foreach($petugasList as $petugas)
                            <option value="{{ $petugas->id }}" @selected(old('petugas_id') == $petugas->id)>
                                {{ $petugas->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('petugas_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Keterangan -->
                <div class="sm:col-span-2">
                    <label for="keterangan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Keterangan / Pos Tugas (Opsional)
                    </label>
                    <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan') }}" maxlength="150"
                           placeholder="Contoh: Gerbang Utama & Meja Piket Lantai 1"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    @error('keterangan') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Koordinator Piket -->
                <div class="sm:col-span-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_koordinator" value="1" @checked(old('is_koordinator'))
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs font-medium text-slate-700">Jadikan sebagai Koordinator Piket pada hari tersebut</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.jadwal-piket.index') }}"
                   class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs cursor-pointer">
                    Simpan Jadwal Piket
                </button>
            </div>
        </form>
    </div>
@endsection