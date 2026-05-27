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
                Registration
            </span>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white leading-tight tracking-tight">
                Buat Akun Anda & <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-100 to-rose-200">Mulai Berkarir</span><br/>
                Bersama Kami
            </h1>
            <p class="mt-4 text-xs sm:text-sm text-red-100/80 leading-relaxed font-medium max-w-sm">
                Daftarkan akun pelamar Anda untuk mengakses lowongan pekerjaan aktif, submit berkas lamaran, dan pantau hasil seleksi secara real-time.
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

    <!-- Right Pane: Register Form -->
    <div class="flex-1 flex items-center justify-center p-6 sm:p-12 md:p-16 relative overflow-hidden bg-slate-50">
        <!-- Ambient Glowing Orbs -->
        <div class="absolute -top-1/4 -right-1/4 w-96 h-96 bg-red-100/50 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-1/4 -left-1/4 w-96 h-96 bg-indigo-100/50 rounded-full blur-3xl animate-pulse delay-1000"></div>

        <div class="w-full max-w-md bg-white/80 backdrop-blur-xl border border-red-50 p-8 sm:p-10 rounded-3xl shadow-2xl shadow-red-500/5 relative z-10">
            <!-- Top Accent Gradient line -->
            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-red-500 to-red-600 rounded-t-3xl"></div>

            <div class="mb-8">
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Sign Up</h2>
                <p class="text-slate-500 text-xs mt-1 font-medium">Daftar portal rekrutmen pelamar</p>
            </div>

            <form wire:submit.prevent="register" class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input wire:model="name" type="text" id="name" required
                        class="block w-full px-4 py-2.5 bg-white border border-red-100 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all text-sm"
                        placeholder="John Doe">
                    @error('name')
                        <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <input wire:model="email" type="email" id="email" required
                        class="block w-full px-4 py-2.5 bg-white border border-red-100 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all text-sm"
                        placeholder="nama@email.com">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <input wire:model="password" type="password" id="password" required
                        class="block w-full px-4 py-2.5 bg-white border border-red-100 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all text-sm"
                        placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi</label>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation" required
                        class="block w-full px-4 py-2.5 bg-white border border-red-100 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all text-sm"
                        placeholder="••••••••">
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-red-600 to-red-600 hover:from-red-700 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all shadow-lg shadow-red-500/20 mt-2">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-red-200 group-hover:text-red-100 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </span>
                    <span>Daftar Akun</span>
                </button>
            </form>

            <div class="mt-6 text-center border-t border-slate-100 pt-6">
                <p class="text-xs font-semibold text-slate-500">
                    Sudah memiliki akun pelamar? <br/>
                    <a href="{{ route('login') }}" class="font-bold text-red-600 hover:text-red-700 transition-colors inline-block mt-1">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
