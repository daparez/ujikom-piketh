@extends('layouts.admin.admin')

@section('title', 'Tambah Jadwal Pelajaran')
@section('page-title', 'Tambah Jadwal Pelajaran')

@section('content')
    <div class="max-w-3xl bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Formulir Jadwal Pelajaran</h2>
                <p class="text-xs text-slate-500">Sistem otomatis memeriksa bentrok jadwal kelas maupun guru</p>
            </div>
            <a href="{{ route('admin.jadwal-pelajaran.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('admin.jadwal-pelajaran.store') }}" method="POST" class="p-6 space-y-5">
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

                <!-- Hari -->
                <div>
                    <label for="hari" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Hari <span class="text-rose-500">*</span>
                    </label>
                    <select id="hari" name="hari" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        @foreach($hariList as $key => $label)
                            <option value="{{ $key }}" @selected(old('hari') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('hari') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Jam Pelajaran -->
                <div>
                    <label for="jam_pelajaran_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Slot Jam Pelajaran <span class="text-rose-500">*</span>
                    </label>
                    <select id="jam_pelajaran_id" name="jam_pelajaran_id" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border rounded-lg focus:outline-none {{ $errors->has('jam_pelajaran_id') ? 'border-rose-400' : 'border-slate-300 focus:border-indigo-500' }}">
                        <option value="">-- Pilih Jam Ke --</option>
                        @foreach($jamPelajarans as $jam)
                            <option value="{{ $jam->id }}" @selected(old('jam_pelajaran_id') == $jam->id)>
                                Jam ke-{{ $jam->jam_ke }} ({{ \Carbon\Carbon::parse($jam->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jam->jam_selesai)->format('H:i') }})
                            </option>
                        @endforeach
                    </select>
                    @error('jam_pelajaran_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
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
                                {{ $kelas->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kelas_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Mata Pelajaran -->
                <div>
                    <label for="mata_pelajaran_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Mata Pelajaran <span class="text-rose-500">*</span>
                    </label>
                    <select id="mata_pelajaran_id" name="mata_pelajaran_id" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($mapels as $mapel)
                            <option value="{{ $mapel->id }}" @selected(old('mata_pelajaran_id') == $mapel->id)>
                                [{{ $mapel->kode }}] {{ $mapel->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('mata_pelajaran_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Guru Pengajar -->
                <div>
                    <label for="guru_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Guru Pengajar <span class="text-rose-500">*</span>
                    </label>
                    <select id="guru_id" name="guru_id" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border rounded-lg focus:outline-none {{ $errors->has('guru_id') ? 'border-rose-400' : 'border-slate-300 focus:border-indigo-500' }}">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" @selected(old('guru_id') == $guru->id)>
                                {{ $guru->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('guru_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Ruangan -->
                <div>
                    <label for="ruangan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Ruangan / Lab (Opsional)
                    </label>
                    <input type="text" id="ruangan" name="ruangan" value="{{ old('ruangan') }}" maxlength="30"
                           placeholder="Contoh: R.12 / Lab RPL 1"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    @error('ruangan') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.jadwal-pelajaran.index') }}"
                   class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs cursor-pointer">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
@endsection