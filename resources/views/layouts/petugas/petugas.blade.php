<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Petugas Piket') - Sistem Informasi Piket Sekolah</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Vite Assets (Tailwind CSS 4 + Alpine.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-slate-800" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Petugas -->
        @include('layouts.petugas.sidebar')

        <!-- Content Wrapper -->
        <div class="flex flex-col flex-1 w-0 overflow-hidden">
            
            <!-- Top Header -->
            @include('layouts.petugas.header')

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto focus:outline-none p-4 sm:p-6 lg:p-8">
                <div class="max-w-7xl mx-auto space-y-6">
                    
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-transition
                             class="flex items-center justify-between p-4 text-sm text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl shadow-xs">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">&times;</button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" x-transition
                             class="flex items-center justify-between p-4 text-sm text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-xs">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button @click="show = false" class="text-rose-600 hover:text-rose-800">&times;</button>
                        </div>
                    @endif

                    <!-- Page Content -->
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            @include('layouts.petugas.footer')
        </div>
    </div>

    @stack('scripts')
</body>
</html>