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
        <a href="{{ route('guru.dashboard') }}" class="flex items-center gap-3">
            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-sky-600 text-white font-bold shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <span class="block text-sm font-bold tracking-wide text-white leading-none">E-PIKET</span>
                <span class="block text-[11px] text-sky-400 mt-1">Portal Guru & Wali Kelas</span>
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
        <a href="{{ route('guru.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('guru.dashboard') ? 'bg-sky-600 text-white shadow-sm' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Grup: PEMBELAJARAN & TUGAS -->
        <div class="pt-4 pb-1 px-3">
            <p class="text-[11px] font-semibold tracking-wider text-slate-500 uppercase">Pembelajaran & Kelas</p>
        </div>

        <a href="{{ Route::has('guru.jadwal-saya') ? route('guru.jadwal-saya') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('guru.jadwal-saya') ? 'bg-sky-600 text-white' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Jadwal Saya</span>
        </a>

        <a href="{{ Route::has('guru.tugas-kelas.index') ? route('guru.tugas-kelas.index') : '#' }}"
           class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('guru.tugas-kelas.*') ? 'bg-sky-600 text-white' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span>Tugas Kelas</span>
            </div>
            @if(($tugasKosongCount ?? 0) > 0)
                <span title="Kelas ditinggalkan yang perlu diisi tugas" class="px-2 py-0.5 text-[11px] font-semibold bg-amber-500 text-slate-950 rounded-full">
                    {{ $tugasKosongCount }}
                </span>
            @endif
        </a>

        <!-- Grup: PERIZINAN -->
        <div class="pt-4 pb-1 px-3">
            <p class="text-[11px] font-semibold tracking-wider text-slate-500 uppercase">Perizinan</p>
        </div>

        <a href="{{ Route::has('pengajuan-izin.create') ? route('pengajuan-izin.create') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pengajuan-izin.create') ? 'bg-sky-600 text-white' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Ajukan Izin</span>
        </a>

        <a href="{{ Route::has('pengajuan-izin.index') ? route('pengajuan-izin.index') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pengajuan-izin.index') ? 'bg-sky-600 text-white' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Riwayat Izin Saya</span>
        </a>

        <!-- Grup: KEDISIPLINAN & REKAP -->
        <div class="pt-4 pb-1 px-3">
            <p class="text-[11px] font-semibold tracking-wider text-slate-500 uppercase">Kesiswaan & Laporan</p>
        </div>

        <a href="{{ Route::has('pelanggaran.create') ? route('pelanggaran.create') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pelanggaran.*') ? 'bg-sky-600 text-white' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>Catat Pelanggaran</span>
        </a>

        <a href="{{ Route::has('guru.rekap-saya') ? route('guru.rekap-saya') : '#' }}"
           class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('guru.rekap-saya') ? 'bg-sky-600 text-white' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Rekap Saya & Kelas Wali</span>
        </a>

        <a href="{{ Route::has('notifikasi.index') ? route('notifikasi.index') : '#' }}"
           class="flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('notifikasi.*') ? 'bg-sky-600 text-white' : 'hover:bg-slate-800/80 hover:text-white' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span>Notifikasi</span>
            </div>
            @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                <span class="px-2 py-0.5 text-[11px] font-semibold bg-rose-500 text-white rounded-full">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </a>
    </nav>

    <!-- Info Wali Kelas (Jika guru mengampu wali kelas) -->
    <div class="p-4 border-t border-slate-800 bg-slate-950/40">
        @if(!empty($kelasWali))
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-sky-400"></span>
                <div>
                    <p class="text-[11px] text-slate-400">Wali Kelas</p>
                    <p class="text-xs font-semibold text-white">{{ $kelasWali->nama }}</p>
                </div>
            </div>
        @else
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-slate-500"></span>
                <div>
                    <p class="text-[11px] text-slate-400">Peran Aktif</p>
                    <p class="text-xs font-semibold text-white">Guru Mata Pelajaran</p>
                </div>
            </div>
        @endif
    </div>
</aside>