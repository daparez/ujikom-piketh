@extends('layouts.admin.admin')

@section('title', 'Edit Jam Pelajaran')
@section('page-title', 'Edit Slot Jam Pelajaran')

@section('content')
    <div class="max-w-xl bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Edit Jam ke-{{ $jamPelajaran->jam_ke }}</h2>
                <p class="text-xs text-slate-500">Perbarui urutan, waktu mulai/selesai, atau keterangan istirahat</p>
            </div>
            <a href="{{ route('admin.jam-pelajaran.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('admin.jam-pelajaran.update', $jamPelajaran) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Jam Ke- -->
            <div>
                <label for="jam_ke" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                    Urutan Jam Ke- <span class="text-rose-500">*</span>
                </label>
                <input type="number" id="jam_ke" name="jam_ke" value="{{ old('jam_ke', $jamPelajaran->jam_ke) }}" min="1" max="20" required
                       class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('jam_ke') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                @error('jam_ke') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- Jam Mulai -->
                <div>
                    <label for="jam_mulai" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Jam Mulai <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" id="jam_mulai" name="jam_mulai"
                           value="{{ old('jam_mulai', \Carbon\Carbon::parse($jamPelajaran->jam_mulai)->format('H:i')) }}" required
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('jam_mulai') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('jam_mulai') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Jam Selesai -->
                <div>
                    <label for="jam_selesai" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Jam Selesai <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" id="jam_selesai" name="jam_selesai"
                           value="{{ old('jam_selesai', \Carbon\Carbon::parse($jamPelajaran->jam_selesai)->format('H:i')) }}" required
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('jam_selesai') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('jam_selesai') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Tandai Istirahat -->
            <div>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_istirahat" value="1" @checked(old('is_istirahat', $jamPelajaran->is_istirahat))
                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-xs font-medium text-slate-700">Tandai sebagai Jam Istirahat (tidak dihitung sebagai kelas kosong)</span>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.jam-pelajaran.index') }}"
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