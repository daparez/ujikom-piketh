@extends('layouts.admin.admin')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Data Pengguna (Guru & Staf)')

@section('content')
    <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden">
        <!-- Header & Tombol Tambah -->
        <div class="p-5 border-b border-slate-200 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Daftar Akun Pengguna</h2>
                <p class="text-xs text-slate-500">Kelola hak akses Admin, Petugas Piket, dan Guru Pengajar</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Pengguna</span>
            </a>
        </div>

        <!-- Baris Filter & Pencarian -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="p-4 bg-slate-50/70 border-b border-slate-200">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                <div class="sm:col-span-2">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama, NIP, atau email..."
                           class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div>
                    <select name="role"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Peran (Role)</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->value }}" @selected(request('role') === $role->value)>
                                {{ $role->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <select name="status"
                            class="w-full px-3.5 py-2 text-xs bg-white border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="1" @selected(request('status') === '1')>Aktif</option>
                        <option value="0" @selected(request('status') === '0')>Nonaktif</option>
                    </select>

                    <button type="submit"
                            class="px-4 py-2 text-xs font-semibold text-white bg-slate-800 rounded-lg hover:bg-slate-900 transition-colors cursor-pointer">
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'role', 'status']))
                        <a href="{{ route('admin.users.index') }}"
                           class="px-3 py-2 text-xs font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-100">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Tabel Data Pengguna -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5">Pengguna</th>
                        <th class="px-5 py-3.5">NIP / NUPTK</th>
                        <th class="px-5 py-3.5">Peran</th>
                        <th class="px-5 py-3.5">No. HP</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if($user->foto)
                                        <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->name }}"
                                             class="w-9 h-9 rounded-full object-cover border border-slate-200">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-slate-800 text-xs sm:text-sm">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-xs text-slate-600">
                                {{ $user->nip ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5">
                                @php
                                    $roleBadge = match($user->role->value) {
                                        'admin' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                        'petugas' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        default => 'bg-sky-100 text-sky-700 border-sky-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $roleBadge }}">
                                    {{ $user->role->label() }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-600">
                                {{ $user->no_hp ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5">
                                <x-badge-status :status="$user->is_active ? 'aktif' : 'nonaktif'" />
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="px-2.5 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </a>

                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                              onsubmit="return confirm('Hapus pengguna {{ $user->name }}? Data riwayat transaksi tetap aman tersimpan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-2.5 py-1.5 text-xs font-medium text-rose-700 bg-rose-50 rounded-lg hover:bg-rose-100 transition-colors cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-xs text-slate-400">
                                Data pengguna tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-5 py-3.5 border-t border-slate-200 bg-slate-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection