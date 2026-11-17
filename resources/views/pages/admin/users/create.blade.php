@extends('layouts.admin.admin')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna Baru')

@section('content')
    <div class="max-w-3xl bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Formulir Akun Pengguna</h2>
                <p class="text-xs text-slate-500">Isi identitas lengkap guru, petugas piket, atau administrator</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <!-- Nama Lengkap -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Nama Lengkap & Gelar <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Drs. Ahmad Fauzi, M.Pd."
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('name') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           placeholder="nama@sekolah.sch.id"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('email') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Peran (Role) -->
                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Peran Sistem (Role) <span class="text-rose-500">*</span>
                    </label>
                    <select id="role" name="role" required
                            class="mt-1.5 w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        @foreach($roles as $role)
                            <option value="{{ $role->value }}" @selected(old('role', 'guru') === $role->value)>
                                {{ $role->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- NIP / NUPTK -->
                <div>
                    <label for="nip" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        NIP / NUPTK
                    </label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip') }}"
                           placeholder="Opsional"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('nip') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('nip') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- No HP -->
                <div>
                    <label for="no_hp" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Nomor Telepon / WA
                    </label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                           placeholder="08xxxxxxxxxx"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    @error('no_hp') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" required
                           placeholder="Minimal 8 karakter"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 {{ $errors->has('password') ? 'border-rose-400 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20' }}">
                    @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           placeholder="Ulangi kata sandi"
                           class="mt-1.5 w-full px-3.5 py-2 text-sm border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>

                <!-- Foto Profil -->
                <div class="sm:col-span-2">
                    <label for="foto" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Foto Profil (JPG/PNG, Maks. 2 MB)
                    </label>
                    <input type="file" id="foto" name="foto" accept="image/png,image/jpeg"
                           class="mt-1.5 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('foto') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <!-- Status Aktif -->
                <div class="sm:col-span-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-xs font-medium text-slate-700">Akun Aktif (pengguna dapat masuk ke sistem)</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs cursor-pointer">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
@endsection