<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'HRD ATS System' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-red-50/50 font-sans text-slate-900 antialiased h-screen flex flex-col md:flex-row overflow-hidden w-full" x-data="{ sidebarOpen: false, desktopSidebarOpen: true }">

    <!-- Mobile Header -->
    <div class="md:hidden h-16 bg-gradient-to-r from-red-500 to-rose-600 flex items-center justify-between px-4 sm:px-6 z-20 flex-shrink-0 shadow-md">
        <span class="text-lg font-bold text-white tracking-wider flex items-center gap-2">
            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            LogoPerusahaan
        </span>
        <button @click="sidebarOpen = true" class="text-white hover:text-red-100 focus:outline-none p-1">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-40 md:hidden" style="display: none;"></div>

    <!-- Sidebar -->
    <aside :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen, 'md:w-64': desktopSidebarOpen, 'md:w-20': !desktopSidebarOpen }" class="fixed md:static inset-y-0 left-0 z-50 w-64 md:translate-x-0 bg-white/95 md:bg-white/80 backdrop-blur-xl border-r border-red-100 flex flex-col shadow-2xl md:shadow-lg shadow-red-500/5 transition-all duration-300 h-full overflow-hidden shrink-0">
        <div class="h-16 flex items-center border-b border-red-100 bg-gradient-to-r from-red-500 to-rose-600 shrink-0 transition-all w-full" :class="desktopSidebarOpen ? 'px-4 justify-between' : 'px-4 md:px-0 md:justify-center justify-between'">
            
            <!-- Logo -->
            <div class="flex items-center gap-2" :class="desktopSidebarOpen ? '' : 'md:hidden'">
                <svg class="w-6 h-6 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="text-xl font-bold text-white tracking-wider truncate">
                    LogoPerusahaan
                </span>
            </div>

            <!-- Desktop Toggle -->
            <button @click="desktopSidebarOpen = !desktopSidebarOpen" class="hidden md:block text-white hover:text-red-200 focus:outline-none shrink-0" title="Toggle Sidebar">
                <svg class="w-6 h-6 transition-transform duration-300" :class="desktopSidebarOpen ? '' : 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>

            <!-- Mobile Hide -->
            <div class="flex items-center ml-auto md:hidden">
                <button @click="sidebarOpen = false" class="text-white hover:text-red-200">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        
        <nav class="flex-1 py-6 space-y-2 overflow-y-auto" :class="desktopSidebarOpen ? 'px-4' : 'px-4 md:px-2'">
            <a href="{{ route('hrd.overview') }}" title="Dashboard"
               class="flex items-center py-3 rounded-xl transition-all {{ request()->routeIs('hrd.overview') ? 'bg-red-50 text-red-600 font-semibold' : 'text-slate-600 hover:bg-red-50/50 hover:text-red-500' }}"
               :class="desktopSidebarOpen ? 'px-4 gap-3' : 'px-4 md:px-0 md:justify-center'">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('hrd.overview') ? 'text-red-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span :class="desktopSidebarOpen ? '' : 'md:hidden'" class="whitespace-nowrap font-medium">Dashboard</span>
            </a>

            <a href="{{ route('hrd.dashboard') }}" title="Proses Kandidat"
               class="flex items-center py-3 rounded-xl transition-all {{ request()->routeIs('hrd.dashboard') ? 'bg-red-50 text-red-600 font-semibold' : 'text-slate-600 hover:bg-red-50/50 hover:text-red-500' }}"
               :class="desktopSidebarOpen ? 'px-4 gap-3' : 'px-4 md:px-0 md:justify-center'">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('hrd.dashboard') ? 'text-red-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span :class="desktopSidebarOpen ? '' : 'md:hidden'" class="whitespace-nowrap font-medium">Proses Kandidat</span>
            </a>
            
            <a href="{{ route('hrd.requirements') ?? '#' }}" title="Persiapan Lamaran"
               class="flex items-center py-3 rounded-xl transition-all {{ request()->routeIs('hrd.requirements') ? 'bg-red-50 text-red-600 font-semibold' : 'text-slate-600 hover:bg-red-50/50 hover:text-red-500' }}"
               :class="desktopSidebarOpen ? 'px-4 gap-3' : 'px-4 md:px-0 md:justify-center'">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('hrd.requirements') ? 'text-red-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span :class="desktopSidebarOpen ? '' : 'md:hidden'" class="whitespace-nowrap font-medium">Persiapan Lamaran</span>
            </a>
            
            <a href="{{ route('hrd.rejected') }}" title="Data Kandidat Gagal"
               class="flex items-center py-3 rounded-xl transition-all {{ request()->routeIs('hrd.rejected') ? 'bg-red-50 text-red-600 font-semibold' : 'text-slate-600 hover:bg-red-50/50 hover:text-red-500' }}"
               :class="desktopSidebarOpen ? 'px-4 gap-3' : 'px-4 md:px-0 md:justify-center'">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('hrd.rejected') ? 'text-red-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span :class="desktopSidebarOpen ? '' : 'md:hidden'" class="whitespace-nowrap font-medium">Data Kandidat Gagal</span>
            </a>
        </nav>
        
        <div class="border-t border-red-100 shrink-0 transition-all" :class="desktopSidebarOpen ? 'p-4' : 'p-4 md:p-3'">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" title="Logout" 
                        class="w-full flex items-center justify-center py-2 border border-red-200 rounded-xl text-red-600 bg-white hover:bg-red-50 transition-colors shadow-sm"
                        :class="desktopSidebarOpen ? 'px-4 gap-2' : 'px-0'">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span :class="desktopSidebarOpen ? '' : 'md:hidden'" class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-[calc(100vh-4rem)] md:h-screen overflow-hidden relative w-full md:w-auto">
        <div class="absolute inset-0 bg-gradient-to-br from-red-100/20 via-transparent to-rose-100/20 pointer-events-none -z-10"></div>
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 md:p-8 w-full">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
</body>
</html>
