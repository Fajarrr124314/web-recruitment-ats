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
    <div class="md:hidden h-16 bg-white/80 backdrop-blur-md border-b border-red-100 flex items-center justify-between px-4 sm:px-6 z-20 flex-shrink-0 shadow-sm">
        <span class="text-lg font-bold text-red-700 tracking-wider flex items-center gap-2">
            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            LogoPerusahaan
        </span>
        <button @click="sidebarOpen = true" class="text-red-600 hover:text-red-800 focus:outline-none p-1 hover:bg-red-50 rounded-lg">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/50 z-40 md:hidden" style="display: none;"></div>

    <!-- Sidebar -->
    <aside :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen, 'md:w-64': desktopSidebarOpen, 'md:w-20': !desktopSidebarOpen }" class="fixed md:static inset-y-0 left-0 z-50 w-64 md:translate-x-0 bg-white/95 md:bg-white/80 backdrop-blur-xl border-r border-red-100 flex flex-col shadow-2xl md:shadow-lg shadow-red-500/5 transition-all duration-300 h-full overflow-hidden shrink-0">
        <div class="h-16 flex items-center border-b border-red-100 bg-white/40 backdrop-blur-md shrink-0 transition-all w-full" :class="desktopSidebarOpen ? 'px-4 justify-between' : 'px-4 md:px-0 md:justify-center justify-between'">
            
            <!-- Logo wrapped in a beautiful premium red gradient box -->
            <div class="flex items-center gap-2 bg-gradient-to-br from-red-600/90 to-red-800/85 border border-red-500/20 px-3 py-1.5 rounded-xl shadow-md shadow-red-500/10" :class="desktopSidebarOpen ? '' : 'md:hidden'">
                <svg class="w-5 h-5 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="text-sm font-black text-white tracking-wider uppercase font-sans truncate">
                    LogoPerusahaan
                </span>
            </div>

            <!-- Desktop Toggle -->
            <button @click="desktopSidebarOpen = !desktopSidebarOpen" class="hidden md:block text-slate-500 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg focus:outline-none shrink-0 transition-colors" title="Toggle Sidebar">
                <svg class="w-5 h-5 transition-transform duration-300" :class="desktopSidebarOpen ? '' : 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>

            <!-- Mobile Hide -->
            <div class="flex items-center ml-auto md:hidden">
                <button @click="sidebarOpen = false" class="text-slate-500 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        
        <nav class="flex-1 py-2 space-y-0.5 overflow-y-auto" :class="desktopSidebarOpen ? 'px-4' : 'px-4 md:px-2'">
            <!-- Dashboard Link -->
            <a href="{{ route('hrd.overview') }}" title="Dashboard"
               class="group flex items-center py-2 rounded-xl transition-all duration-300 hover:translate-x-1 hover:shadow-sm {{ request()->routeIs('hrd.overview') ? 'bg-gradient-to-r from-red-50/80 to-red-100/50 border-l-4 border-red-500 text-red-600 font-semibold shadow-sm' : 'text-slate-600 hover:bg-red-50/50 hover:text-red-500 border-l-4 border-transparent font-semibold' }}"
               :class="desktopSidebarOpen ? 'px-4 gap-3' : 'px-4 md:px-0 md:justify-center'">
                <svg class="w-5 h-5 shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 {{ request()->routeIs('hrd.overview') ? 'text-red-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span :class="desktopSidebarOpen ? '' : 'md:hidden'" class="truncate font-semibold text-sm">Dashboard</span>
            </a>

            <!-- Tambah Persyaratan Link -->
            <a href="{{ route('hrd.requirements') ?? '#' }}" title="Tambah Persyaratan"
               class="group flex items-center py-2 rounded-xl transition-all duration-300 hover:translate-x-1 hover:shadow-sm {{ request()->routeIs('hrd.requirements') ? 'bg-gradient-to-r from-red-50/80 to-red-100/50 border-l-4 border-red-500 text-red-600 font-semibold shadow-sm' : 'text-slate-600 hover:bg-red-50/50 hover:text-red-500 border-l-4 border-transparent font-semibold' }}"
               :class="desktopSidebarOpen ? 'px-4 gap-3' : 'px-4 md:px-0 md:justify-center'">
                <svg class="w-5 h-5 shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 {{ request()->routeIs('hrd.requirements') ? 'text-red-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span :class="desktopSidebarOpen ? '' : 'md:hidden'" class="truncate font-semibold text-sm">Tambah Persyaratan</span>
            </a>

            <!-- Proses Kandidat Dropdown Menu -->
            <div x-data="{ open: {{ (request()->routeIs('hrd.dashboard') || request()->routeIs('hrd.process')) ? 'true' : 'false' }} }" class="space-y-0.5">
                <button @click="open = !open" title="Proses Kandidat"
                   class="group flex items-center justify-between w-full py-2.5 rounded-xl transition-all duration-300 hover:translate-x-1 hover:shadow-sm {{ (request()->routeIs('hrd.dashboard') || request()->routeIs('hrd.process')) ? 'bg-gradient-to-r from-red-50/40 to-red-100/20 border-l-4 border-red-400 text-red-600 font-semibold' : 'text-slate-600 hover:bg-red-50/50 hover:text-red-500 border-l-4 border-transparent font-semibold' }}"
                   :class="desktopSidebarOpen ? 'px-4' : 'px-4 md:px-0 md:justify-center'">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 {{ (request()->routeIs('hrd.dashboard') || request()->routeIs('hrd.process')) ? 'text-red-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 11-4 0z" />
                        </svg>
                        <span :class="desktopSidebarOpen ? '' : 'md:hidden'" class="truncate font-semibold text-sm">Proses Kandidat</span>
                    </div>
                    <svg :class="[desktopSidebarOpen ? '' : 'md:hidden', open ? 'rotate-180' : '']" class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Sub-menu Items -->
                <div x-show="open" x-collapse x-transition.duration.300ms class="space-y-0.5 pl-4" :class="desktopSidebarOpen ? 'block' : 'md:hidden'" style="display: none;">
                    <!-- Kanban Board / Dashboard -->
                    <a href="{{ route('hrd.dashboard') }}"
                       class="group flex items-center gap-2 py-2 px-3 rounded-lg text-sm transition-all duration-300 hover:translate-x-1 {{ request()->routeIs('hrd.dashboard') ? 'bg-red-500 text-white font-bold shadow-md shadow-red-500/20' : 'text-slate-500 hover:bg-red-50/30 hover:text-red-500 font-semibold' }}">
                        <svg class="w-4 h-4 shrink-0 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                        </svg>
                        <span>Papan Kanban</span>
                    </a>

                    <!-- Administrasi -->
                    <a href="{{ route('hrd.process', 'administrasi') }}"
                       class="group flex items-center gap-2.5 py-2 px-3 rounded-lg text-sm transition-all duration-300 hover:translate-x-1 {{ request()->is('hrd/process/administrasi') ? 'bg-blue-600 text-white font-bold shadow-lg shadow-blue-500/20' : 'text-slate-500 hover:bg-blue-50/40 hover:text-blue-600 font-semibold' }}">
                        <div class="h-2 w-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->is('hrd/process/administrasi') ? 'bg-white' : 'bg-blue-400' }}"></div>
                        <span>Administrasi</span>
                    </a>

                    <!-- Psikotes -->
                    <a href="{{ route('hrd.process', 'psikotes') }}"
                       class="group flex items-center gap-2.5 py-2 px-3 rounded-lg text-sm transition-all duration-300 hover:translate-x-1 {{ request()->is('hrd/process/psikotes') ? 'bg-red-600 text-white font-bold shadow-lg shadow-red-500/20' : 'text-slate-500 hover:bg-red-50/40 hover:text-red-600' }}">
                        <div class="h-2 w-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->is('hrd/process/psikotes') ? 'bg-white' : 'bg-red-400' }}"></div>
                        <span>Psikotes</span>
                    </a>

                    <!-- Interview -->
                    <a href="{{ route('hrd.process', 'interview') }}"
                       class="group flex items-center gap-2.5 py-2 px-3 rounded-lg text-sm transition-all duration-300 hover:translate-x-1 {{ request()->is('hrd/process/interview') ? 'bg-amber-600 text-white font-bold shadow-lg shadow-amber-500/20' : 'text-slate-500 hover:bg-amber-50/40 hover:text-amber-600' }}">
                        <div class="h-2 w-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->is('hrd/process/interview') ? 'bg-white' : 'bg-amber-400' }}"></div>
                        <span>Interview</span>
                    </a>

                    <!-- MCU -->
                    <a href="{{ route('hrd.process', 'mcu') }}"
                       class="group flex items-center gap-2.5 py-2 px-3 rounded-lg text-sm transition-all duration-300 hover:translate-x-1 {{ request()->is('hrd/process/mcu') ? 'bg-purple-600 text-white font-bold shadow-lg shadow-purple-500/20' : 'text-slate-500 hover:bg-purple-50/40 hover:text-purple-600 font-semibold' }}">
                        <div class="h-2 w-2 rounded-full transition-transform duration-300 group-hover:scale-125 {{ request()->is('hrd/process/mcu') ? 'bg-white' : 'bg-purple-400' }}"></div>
                        <span>MCU</span>
                    </a>
                </div>
            </div>

            <!-- Karyawan Diterima Link -->
            <a href="{{ route('hrd.hired') }}" title="Karyawan Diterima"
               class="group flex items-center py-2.5 rounded-xl transition-all duration-300 hover:translate-x-1 hover:shadow-sm {{ request()->routeIs('hrd.hired') ? 'bg-gradient-to-r from-red-50/80 to-red-100/50 border-l-4 border-red-500 text-red-600 font-semibold shadow-sm' : 'text-slate-600 hover:bg-red-50/50 hover:text-red-500 border-l-4 border-transparent font-semibold' }}"
               :class="desktopSidebarOpen ? 'px-4 gap-3' : 'px-4 md:px-0 md:justify-center'">
                <svg class="w-5 h-5 shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 {{ request()->routeIs('hrd.hired') ? 'text-red-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span :class="desktopSidebarOpen ? '' : 'md:hidden'" class="truncate font-semibold text-sm">Karyawan Diterima</span>
            </a>

            <!-- Kandidat Ditolak Link -->
            <a href="{{ route('hrd.rejected') }}" title="Kandidat Ditolak"
               class="group flex items-center py-2.5 rounded-xl transition-all duration-300 hover:translate-x-1 hover:shadow-sm {{ request()->routeIs('hrd.rejected') ? 'bg-gradient-to-r from-red-50/80 to-red-100/50 border-l-4 border-red-500 text-red-600 font-semibold shadow-sm' : 'text-slate-600 hover:bg-red-50/50 hover:text-red-500 border-l-4 border-transparent font-semibold' }}"
               :class="desktopSidebarOpen ? 'px-4 gap-3' : 'px-4 md:px-0 md:justify-center'">
                <svg class="w-5 h-5 shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 {{ request()->routeIs('hrd.rejected') ? 'text-red-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span :class="desktopSidebarOpen ? '' : 'md:hidden'" class="truncate font-semibold text-sm">Kandidat Ditolak</span>
            </a>
        </nav>
        
        <div class="border-t border-red-100 shrink-0 transition-all" :class="desktopSidebarOpen ? 'p-3' : 'p-3 md:p-2'">
            
            </div>

            <!-- HRD Profile Settings Widget -->
            <div class="bg-gradient-to-r from-red-50/90 to-red-100/40 backdrop-blur-md border border-red-100 p-2.5 rounded-2xl mb-2.5 shadow-inner hover:shadow transition-all duration-300 relative group"
                 :class="desktopSidebarOpen ? 'block' : 'md:p-1 md:flex md:justify-center'">
                <div class="flex items-center gap-2.5" :class="desktopSidebarOpen ? '' : 'md:flex-col md:gap-1'">
                    <!-- Profile initial circle -->
                    @php
                        $initials = collect(explode(' ', auth()->user()->name))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->join('');
                    @endphp
                    <div class="h-8 w-8 rounded-xl bg-gradient-to-br from-red-500 to-red-700 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-red-500/15 shrink-0">
                        {{ strtoupper($initials) }}
                    </div>
                    
                    <!-- HRD Details -->
                    <div class="flex-1 min-w-0" :class="desktopSidebarOpen ? 'block' : 'md:hidden'">
                        <h4 class="text-xs font-bold text-slate-800 truncate leading-tight">{{ auth()->user()->name }}</h4>
                        <span class="text-[9px] text-slate-400 font-semibold uppercase tracking-wider block">Admin HRD</span>
                    </div>

                    <!-- Settings Button -->
                    <button type="button" @click="$dispatch('openProfileModal')" title="Pengaturan Profil"
                            class="p-1 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-500/10 transition-colors shrink-0"
                            :class="desktopSidebarOpen ? '' : 'md:p-0.5'">
                        <svg class="w-3.5 h-3.5 text-slate-500 hover:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>
            </div>


            <!-- Activity Log Link Button -->
            <a href="{{ route('hrd.activity-logs') }}" title="Log Aktivitas"
               class="group w-full flex items-center justify-center py-2 border rounded-xl transition-all duration-300 hover:scale-[1.02] hover:shadow-sm font-semibold mb-1.5 text-xs
               {{ request()->routeIs('hrd.activity-logs') 
                   ? 'bg-gradient-to-r from-red-50 to-red-100/40 border-red-300 text-red-600' 
                   : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50' }}"
               :class="desktopSidebarOpen ? 'px-4 gap-2' : 'px-0'">
                <svg class="w-4 h-4 shrink-0 transition-transform duration-300 group-hover:scale-110 {{ request()->routeIs('hrd.activity-logs') ? 'text-red-500 animate-pulse' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span :class="desktopSidebarOpen ? '' : 'md:hidden'" class="font-medium">Log Aktivitas</span>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" title="Keluar" 
                        class="group w-full flex items-center justify-center py-2 border border-red-200 rounded-xl text-red-600 bg-white hover:bg-red-50 transition-all duration-300 hover:scale-[1.02] hover:shadow-sm font-semibold text-xs"
                        :class="desktopSidebarOpen ? 'px-4 gap-2' : 'px-0'">
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span :class="desktopSidebarOpen ? '' : 'md:hidden'" class="font-medium">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-[calc(100vh-4rem)] md:h-screen overflow-hidden relative w-full md:w-auto">
        <div class="absolute inset-0 bg-gradient-to-br from-red-100/20 via-transparent to-red-200/10 pointer-events-none -z-10"></div>
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 md:p-8 w-full">
            {{ $slot }}
        </div>
    </main>

    @livewire('hrd.profile-modal')
    @livewireScripts
</body>
</html>
