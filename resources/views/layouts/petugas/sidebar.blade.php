<!-- Backdrop Mobile -->
<div x-show="sidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 z-30 bg-slate-900/50 backdrop-blur-xs lg:hidden"
     style="display: none;"></div>

<!-- Sidebar Container -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-40 flex flex-col w-64 bg-slate-900 text-slate-300 transition-transform duration-200 ease-in-out lg:static lg:translate-x-0 shrink-0">
    
    <!-- Brand / Logo Sekolah -->
    <div class="flex items-center justify-between h-16 px-5 bg-slate-950/60 border-b border-slate-800">
        <a href="{{ route('petugas.dashboard') }}" class="flex items-center gap-3">
            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-600 text-white font-bold shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <span class="block text-sm font-bold tracking-wide text-white leading-none">E-PIKET</span>
                <span class="block text-[11px] text-emerald-400 mt-1">Panel Petugas Piket</span>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 lg:hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-700">
        
        <!-- Dashboard -->
        <a href="{{ route('petugas.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('petugas.dashboard') ? 'bg-emerald-600 text-white shadow-sm' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Grup: OPERASIONAL PIKET -->
        <div class="pt-4 pb-1 px-3">
            <p class="text-[11px] font-semibold tracking-wider text-slate-500 uppercase">Operasional Piket</p>
        </div>

        <a href="{{ Route::has('piket.hari-ini') ? route('piket.hari-ini') : '#' }}"
           class="flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('piket.hari-ini') ? 'bg-emerald-600 text-white shadow-sm' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span>Piket Hari Ini</span>
            </div>
            @if(($kelasKosongCount ?? 0) > 0)
                <span title="Kelas kosong belum ditangani" class="px-2 py-0.5 text-[11px] font-semibold bg-rose-500 text-white rounded-full">
                    {{ $kelasKosongCount }}
                </span>
            @endif
        </a>

        <a href="{{ Route::has('piket.validasi-izin.index') ? route('piket.validasi-izin.index') : '#' }}"
           class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('piket.validasi-izin.*') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Validasi Izin</span>
            </div>
            @if(($izinMenungguCount ?? 0) > 0)
                <span class="px-2 py-0.5 text-[11px] font-semibold bg-amber-500 text-slate-950 rounded-full">
                    {{ $izinMenungguCount }}
                </span>
            @endif
        </a>

        <a href="{{ Route::has('pelanggaran.index') ? route('pelanggaran.index') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pelanggaran.*') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>Pelanggaran</span>
        </a>

        <a href="{{ Route::has('laporan.index') ? route('laporan.index') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('laporan.*') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Laporan & Rekap</span>
        </a>

        <!-- Grup: MENU PRIBADI (GURU) -->
        <div class="pt-4 pb-1 px-3">
            <p class="text-[11px] font-semibold tracking-wider text-slate-500 uppercase">Menu Guru & Pribadi</p>
        </div>

        <a href="{{ Route::has('pengajuan-izin.index') ? route('pengajuan-izin.index') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pengajuan-izin.*') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <span>Pengajuan Izin Saya</span>
        </a>

        <a href="{{ Route::has('guru.jadwal-saya') ? route('guru.jadwal-saya') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('guru.jadwal-saya') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Jadwal Saya</span>
        </a>

        <a href="{{ Route::has('notifikasi.index') ? route('notifikasi.index') : '#' }}"
           class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('notifikasi.*') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span>Notifikasi</span>
            </div>
            @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                <span class="px-2 py-0.5 text-[11px] font-semibold bg-indigo-500 text-white rounded-full">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </a>
    </nav>

    <!-- Status Tugas Hari Ini -->
    <div class="p-4 border-t border-slate-800 bg-slate-950/40">
        <div class="flex items-center gap-2.5">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <div>
                <p class="text-[11px] text-slate-400">Status Peran</p>
                <p class="text-xs font-semibold text-white">Petugas Piket Aktif</p>
            </div>
        </div>
    </div>
</aside>