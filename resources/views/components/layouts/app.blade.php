<!DOCTYPE html>
<html lang="id" class="min-h-screen bg-gradient-to-br from-red-50 via-white to-cyan-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Recruitment Portal (ATS)' }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen text-slate-800 flex flex-col antialiased">
    <!-- Navbar -->
    <nav class="bg-white/70 backdrop-blur-md border-b border-red-100 sticky top-0 z-40" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center space-x-2">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-red-500 to-red-700 flex items-center justify-center shadow-lg shadow-red-500/25">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-red-500 to-red-700">LogoPerusahaan</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex md:items-center md:space-x-4">
                    @auth
                        @if(auth()->user()->isHrd())
                            <a href="{{ route('hrd.overview') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('hrd.overview') ? 'bg-red-50 text-red-600 border border-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                                Dashboard HRD
                            </a>
                        @else
                            <a href="{{ route('candidate.apply') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('candidate.apply') ? 'bg-red-50 text-red-600 border border-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                                Form Lamaran
                            </a>
                        @endif
                        
                        <div class="h-6 w-px bg-red-100"></div>

                        <!-- User Profile Info -->
                        <span class="text-sm text-slate-500">
                            {{ auth()->user()->name }} 
                            <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs font-semibold {{ auth()->user()->isHrd() ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-red-500/10 text-red-600 border border-red-500/20' }}">
                                {{ auth()->user()->role === 'hrd' ? 'HRD' : 'Kandidat' }}
                            </span>
                        </span>

                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="ml-2 px-3 py-2 rounded-lg text-sm font-medium text-red-500 hover:bg-red-500/10 transition-colors flex items-center space-x-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-red-600 px-3 py-2 text-sm font-medium">Masuk</a>
                        <a href="{{ route('register') }}" class="ml-2 bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md shadow-red-500/15 hover:shadow-red-500/25 transition-all">Daftar</a>
                    @endauth

                    </div>
                </div>

                <!-- Hamburger Button (Mobile) -->
                <div class="flex md:hidden">
                    <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 focus:outline-none transition-colors" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" :class="{'hidden': open, 'block': !open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="h-6 w-6" :class="{'block': open, 'hidden': !open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden" id="mobile-menu" x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white/95 backdrop-blur-md border-b border-red-100">
                @auth
                    @if(auth()->user()->isHrd())
                        <a href="{{ route('hrd.overview') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('hrd.overview') ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                            Dashboard HRD
                        </a>
                    @else
                        <a href="{{ route('candidate.apply') }}" class="block px-3 py-2 rounded-lg text-base font-medium {{ request()->routeIs('candidate.apply') ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                            Form Lamaran
                        </a>
                    @endif

                    <div class="pt-4 pb-2 border-t border-red-100">
                        <div class="flex items-center px-3">
                            <div class="text-base font-medium text-slate-800">{{ auth()->user()->name }}</div>
                        </div>
                        <div class="mt-1 px-3 text-sm text-slate-500">{{ auth()->user()->email }} ({{ auth()->user()->role === 'hrd' ? 'HRD' : 'Kandidat' }})</div>
                    </div>

                    <!-- Mobile Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-3 py-2 rounded-lg text-base font-medium text-red-500 hover:bg-red-500/10 transition-colors">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-600 hover:bg-red-50 hover:text-red-600">Masuk</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-base font-medium bg-gradient-to-r from-red-500 to-red-700 text-white">Daftar</a>
                @endauth

                
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white/50 backdrop-blur-sm border-t border-red-100 py-6 text-center text-xs text-slate-500">
        <p>&copy; {{ date('Y') }} Recruitment ATS Portal. All rights reserved.</p>
    </footer>
    <!-- Custom Alpine.js Toast Container -->
    <div x-data="{ toasts: [] }" 
         @show-toast.window="
            const id = Date.now();
            toasts.push({ id, message: $event.detail.message, type: $event.detail.type || 'success' });
            setTimeout(() => { toasts = toasts.filter(t => t.id !== id) }, 4000);
         "
         class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-2 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 :class="{
                    'bg-slate-900 border-slate-800 text-white shadow-slate-900/20': toast.type === 'success',
                    'bg-red-600 border-red-500 text-white shadow-red-600/20': toast.type === 'error'
                 }"
                 class="px-4 py-3 rounded-2xl shadow-xl border border-white/10 flex items-center gap-3 max-w-sm pointer-events-auto">
                <span class="p-1 rounded-full bg-white/20 text-white shrink-0">
                    <template x-if="toast.type === 'success'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </template>
                </span>
                <span class="text-xs font-bold leading-tight" x-text="toast.message"></span>
            </div>
        </template>
    </div>
</body>
</html>
