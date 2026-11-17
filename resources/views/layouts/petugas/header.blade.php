<header class="sticky top-0 z-20 flex items-center justify-between h-16 px-4 bg-white border-b border-slate-200 sm:px-6">
    <!-- Left: Hamburger & Page Title -->
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = true" type="button"
                class="p-2 -ml-2 text-slate-500 rounded-lg lg:hidden hover:bg-slate-100 hover:text-slate-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div>
            <h1 class="text-base font-semibold text-slate-800 sm:text-lg">
                @yield('page-title', 'Dashboard Petugas')
            </h1>
            <p class="hidden text-xs text-slate-500 sm:block">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
    </div>

    <!-- Right: Quick Piket Button, Papan Info TV, Notifikasi & Profile -->
    <div class="flex items-center gap-2 sm:gap-3">
        
        <!-- Tombol Pintas Piket Hari Ini -->
        <a href="{{ Route::has('piket.hari-ini') ? route('piket.hari-ini') : '#' }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-xs transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="hidden sm:inline">Buka Piket Hari Ini</span>
            <span class="sm:hidden">Piket</span>
        </a>

        <!-- Pintasan Layar TV / Papan Info Kelas Kosong (F-21) -->
        <a href="{{ Route::has('papan-informasi') ? route('papan-informasi') : '#' }}" target="_blank"
           title="Buka Layar Informasi Kelas Kosong"
           class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100 border border-slate-200 rounded-lg hover:bg-slate-200 transition-colors">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span>Papan Info</span>
        </a>

        <!-- Dropdown Notifikasi -->
        <div x-data="{ notifOpen: false }" class="relative">
            <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false" type="button"
                    class="relative p-2 text-slate-500 rounded-lg hover:bg-slate-100 hover:text-slate-700 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
                @endif
            </button>

            <div x-show="notifOpen"
                 x-transition
                 class="absolute right-0 w-80 mt-2 bg-white border border-slate-200 rounded-xl shadow-lg py-2 z-50"
                 style="display: none;">
                <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-800">Notifikasi Terbaru</span>
                    <a href="{{ Route::has('notifikasi.index') ? route('notifikasi.index') : '#' }}" class="text-xs text-emerald-600 hover:underline">Lihat Semua</a>
                </div>
                <div class="max-h-64 overflow-y-auto divide-y divide-slate-100">
                    @if(auth()->check())
                        @forelse(auth()->user()->unreadNotifications->take(5) as $notif)
                            <a href="{{ $notif->data['url'] ?? '#' }}" class="block px-4 py-2.5 hover:bg-slate-50 transition-colors">
                                <p class="text-xs text-slate-700 font-medium">{{ $notif->data['pesan'] ?? 'Notifikasi baru' }}</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                            </a>
                        @empty
                            <div class="px-4 py-6 text-center text-xs text-slate-400">
                                Tidak ada notifikasi baru.
                            </div>
                        @endforelse
                    @endif
                </div>
            </div>
        </div>

        <!-- Dropdown Profil User -->
        <div x-data="{ profileOpen: false }" class="relative">
            <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" type="button"
                    class="flex items-center gap-2.5 p-1 rounded-lg hover:bg-slate-100 focus:outline-none">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-semibold text-sm">
                    {{ substr(auth()->user()->name ?? 'P', 0, 1) }}
                </div>
                <div class="hidden text-left md:block">
                    <p class="text-xs font-semibold text-slate-800 leading-none">{{ auth()->user()->name ?? 'Petugas Piket' }}</p>
                    <p class="text-[11px] text-slate-500 mt-1 capitalize">{{ auth()->user()->role->value ?? auth()->user()->role ?? 'Petugas' }}</p>
                </div>
                <svg class="w-4 h-4 text-slate-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="profileOpen"
                 x-transition
                 class="absolute right-0 w-48 mt-2 bg-white border border-slate-200 rounded-xl shadow-lg py-1.5 z-50"
                 style="display: none;">
                <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}"
                   class="block px-4 py-2 text-xs text-slate-700 hover:bg-slate-50">
                    Profil & Ubah Password
                </a>
                <div class="my-1 border-t border-slate-100"></div>
                <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-medium">
                        Keluar (Logout)
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>