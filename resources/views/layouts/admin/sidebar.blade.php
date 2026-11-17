<!-- Backdrop Mobile -->
<div x-show="sidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 z-30 bg-blue-950/60 backdrop-blur-sm lg:hidden"
     style="display: none;"></div>

<!-- Sidebar Container -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-40 flex flex-col w-64 bg-gradient-to-b from-[#0B192C] via-[#0F2342] to-[#0A1628] text-blue-100/80 border-r border-blue-900/50 shadow-2xl transition-transform duration-200 ease-in-out lg:static lg:translate-x-0 shrink-0">
    
    <!-- Brand / Logo Sekolah -->
    <div class="flex items-center justify-between h-16 px-5 bg-blue-950/50 border-b border-blue-800/40 backdrop-blur-md">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-sky-400 text-white font-bold shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                </svg>
            </div>
            <div>
                <span class="block text-sm font-extrabold tracking-wider text-white leading-none">E-PIKET</span>
                <span class="block text-[11px] font-medium text-sky-300/80 mt-1">Panel Administrator</span>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="p-1.5 rounded-lg text-blue-300 hover:text-white hover:bg-blue-800/50 lg:hidden transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto scrollbar-thin scrollbar-thumb-blue-800/60 scrollbar-track-transparent">
        
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Grup: DATA MASTER -->
        <div class="pt-5 pb-1.5 px-3.5">
            <p class="text-[10px] font-bold tracking-widest text-sky-400/70 uppercase">Data Master</p>
        </div>

        <a href="{{ Route::has('admin.users.index') ? route('admin.users.index') : '#' }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
            </svg>
            <span>Pengguna (Guru & Staf)</span>
        </a>

        <a href="{{ Route::has('admin.tahun-ajaran.index') ? route('admin.tahun-ajaran.index') : '#' }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('admin.tahun-ajaran.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('admin.tahun-ajaran.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
            </svg>
            <span>Tahun Ajaran</span>
        </a>

        <a href="{{ Route::has('admin.kelas.index') ? route('admin.kelas.index') : '#' }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('admin.kelas.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('admin.kelas.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
            </svg>
            <span>Kelas</span>
        </a>

        <a href="{{ Route::has('admin.siswa.index') ? route('admin.siswa.index') : '#' }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('admin.siswa.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('admin.siswa.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
            </svg>
            <span>Data Siswa</span>
        </a>

        <a href="{{ Route::has('admin.mapel.index') ? route('admin.mapel.index') : '#' }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('admin.mapel.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('admin.mapel.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
            </svg>
            <span>Mata Pelajaran</span>
        </a>

        <a href="{{ Route::has('admin.jam-pelajaran.index') ? route('admin.jam-pelajaran.index') : '#' }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('admin.jam-pelajaran.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('admin.jam-pelajaran.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Jam Pelajaran</span>
        </a>

        <a href="{{ Route::has('admin.jenis-pelanggaran.index') ? route('admin.jenis-pelanggaran.index') : '#' }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('admin.jenis-pelanggaran.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('admin.jenis-pelanggaran.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
            <span>Jenis Pelanggaran</span>
        </a>

        <!-- Grup: PENJADWALAN -->
        <div class="pt-5 pb-1.5 px-3.5">
            <p class="text-[10px] font-bold tracking-widest text-sky-400/70 uppercase">Penjadwalan</p>
        </div>

        <a href="{{ Route::has('admin.jadwal-pelajaran.index') ? route('admin.jadwal-pelajaran.index') : '#' }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('admin.jadwal-pelajaran.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('admin.jadwal-pelajaran.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
            </svg>
            <span>Jadwal Pelajaran</span>
        </a>

        <a href="{{ Route::has('admin.jadwal-piket.index') ? route('admin.jadwal-piket.index') : '#' }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('admin.jadwal-piket.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('admin.jadwal-piket.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
            </svg>
            <span>Jadwal Piket</span>
        </a>

        <!-- Grup: OPERASIONAL & MONITORING -->
        <div class="pt-5 pb-1.5 px-3.5">
            <p class="text-[10px] font-bold tracking-widest text-sky-400/70 uppercase">Monitoring & Laporan</p>
        </div>

        <a href="{{ Route::has('admin.monitoring.piket-hari-ini') ? route('admin.monitoring.piket-hari-ini') : '#' }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('piket.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('piket.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Pantau Piket Hari Ini</span>
        </a>

        <a href="{{ Route::has('admin.pengajuan-izin.index') ? route('admin.pengajuan-izin.index') : '#' }}"
           class="group flex items-center justify-between px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('pengajuan-izin.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('pengajuan-izin.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                <span>Pengajuan Izin</span>
            </div>
            @if(($izinMenungguCount ?? 0) > 0)
                <span class="px-2 py-0.5 text-[11px] font-bold bg-sky-400 text-blue-950 rounded-full shadow-sm">
                    {{ $izinMenungguCount }}
                </span>
            @endif
        </a>

        <a href="{{ Route::has('admin.laporan.index') ? route('admin.laporan.index') : '#' }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('laporan.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('laporan.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
            </svg>
            <span>Laporan & Rekap</span>
        </a>

        <!-- Grup: SISTEM -->
        <div class="pt-5 pb-1.5 px-3.5">
            <p class="text-[10px] font-bold tracking-widest text-sky-400/70 uppercase">Sistem</p>
        </div>

        <a href="{{ Route::has('admin.pengaturan-sekolah.index') ? route('admin.pengaturan-sekolah.index') : '#' }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('admin.pengaturan.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('admin.pengaturan.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Pengaturan</span>
        </a>

        <a href="{{ Route::has('admin.log-aktivitas.index') ? route('admin.log-aktivitas.index') : '#' }}"
           class="group flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-150 {{ request()->routeIs('admin.log-aktivitas.*') ? 'bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-md shadow-blue-600/30 ring-1 ring-sky-400/30' : 'hover:bg-blue-800/40 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0 transition-colors {{ request()->routeIs('admin.log-aktivitas.*') ? 'text-white' : 'text-sky-400/75 group-hover:text-sky-300' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/>
            </svg>
            <span>Log Aktivitas</span>
        </a>
    </nav>

    <!-- Tahun Ajaran Aktif Indicator -->
    <div class="p-3.5 border-t border-blue-800/40 bg-blue-950/60">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-blue-900/40 border border-blue-700/40">
            <span class="relative flex h-2.5 w-2.5 shrink-0">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-sky-400"></span>
            </span>
            <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-sky-300/80">Tahun Ajaran Aktif</p>
                <p class="text-xs font-bold text-white mt-0.5">{{ $tahunAjaranAktif->nama ?? '2026/2027' }} ({{ ucfirst($tahunAjaranAktif->semester ?? 'Ganjil') }})</p>
            </div>
        </div>
    </div>
</aside>