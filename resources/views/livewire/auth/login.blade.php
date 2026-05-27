<div class="min-h-screen flex flex-col md:flex-row relative bg-slate-50 overflow-hidden">
    <!-- Left Pane: Interactive Banner (Red Glassmorphism) -->
    <div class="w-full md:w-5/12 bg-gradient-to-br from-red-600 via-rose-700 to-slate-900 p-8 sm:p-12 md:p-16 flex flex-col justify-between relative overflow-hidden shrink-0">
        <!-- Ambient Glowing Orbs -->
        <div class="absolute top-1/4 -left-1/4 w-96 h-96 bg-red-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 -right-1/4 w-96 h-96 bg-rose-400/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
        
        <!-- Top branding logo -->
        <div class="relative z-10 flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-white font-black text-sm">
                R
            </div>
            <span class="text-white text-xs font-black tracking-widest uppercase">Smart ATS Portal</span>
        </div>

        <!-- Middle copy -->
        <div class="relative z-10 my-auto py-12 md:py-0">
            <span class="inline-block px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-red-100 text-[10px] font-black uppercase tracking-widest border border-white/20 mb-6">
                Recruitment System
            </span>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white leading-tight tracking-tight">
                Selamat Datang di <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-100 to-rose-200">Portal Rekrutmen</span><br/>
                PT Indonesia
            </h1>
            <p class="mt-4 text-xs sm:text-sm text-red-100/80 leading-relaxed font-medium max-w-sm">
                Temukan potensi karir terbaik Anda. Portal rekrutmen kami ditenagai oleh AI Smart Screening untuk proses seleksi yang objektif, transparan, dan cepat.
            </p>

            <!-- Dynamic Bullets -->
            <div class="mt-8 space-y-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white text-xs">
                        ✓
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-white/90">AI Smart Screening & Matching</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white text-xs">
                        ✓
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-white/90">Pantau Status Lamaran Real-Time</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white text-xs">
                        ✓
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-white/90">Penilaian Wawancara Transparan</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="relative z-10 text-[10px] text-red-200/50 font-bold tracking-wider uppercase">
            © 2026 PT Indonesia. All rights reserved.
        </div>
    </div>

    <!-- Right Pane: Login Form -->
    <div class="flex-1 flex items-center justify-center p-6 sm:p-12 md:p-16 relative overflow-hidden bg-slate-50">
        <!-- Ambient Glowing Orbs -->
        <div class="absolute -top-1/4 -right-1/4 w-96 h-96 bg-red-100/50 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-1/4 -left-1/4 w-96 h-96 bg-indigo-100/50 rounded-full blur-3xl animate-pulse delay-1000"></div>

        <div class="w-full max-w-md bg-white/80 backdrop-blur-xl border border-red-50 p-8 sm:p-10 rounded-3xl shadow-2xl shadow-red-500/5 relative z-10">
            <!-- Top Accent Gradient line -->
            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-red-500 to-red-600 rounded-t-3xl"></div>

            <div class="mb-8">
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Sign In</h2>
                <p class="text-slate-500 text-xs mt-1 font-medium">Lanjutkan ke akun portal ATS Anda</p>
            </div>

            <form wire:submit.prevent="authenticate" class="space-y-5">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Alamat Email</label>
                    <input wire:model="email" type="email" id="email" required
                        class="block w-full px-4 py-3 bg-white border border-red-100 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all text-sm"
                        placeholder="nama@email.com">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Kata Sandi</label>
                    <input wire:model="password" type="password" id="password" required
                        class="block w-full px-4 py-3 bg-white border border-red-100 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all text-sm"
                        placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input wire:model="remember" id="remember" type="checkbox"
                        class="h-4 w-4 bg-white border-red-200 text-red-600 focus:ring-red-500 rounded transition-colors shadow-sm cursor-pointer">
                    <label for="remember" class="ml-2 block text-xs font-semibold text-slate-500 select-none cursor-pointer">Ingat saya di perangkat ini</label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-red-600 to-red-600 hover:from-red-700 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all shadow-lg shadow-red-500/20">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-red-200 group-hover:text-red-100 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </span>
                    <span>Masuk Portal</span>
                </button>
            </form>

            <div class="mt-6 text-center border-t border-slate-100 pt-6">
                <p class="text-xs font-semibold text-slate-500">
                    Belum memiliki akun pelamar? <br/>
                    <a href="{{ route('register') }}" class="font-bold text-red-600 hover:text-red-700 transition-colors inline-block mt-1">Buat Akun Sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>
