<div class="min-h-[80vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12 relative overflow-hidden bg-gradient-to-br from-red-50 to-white">
    <!-- Background Gradients -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-red-300/30 rounded-full blur-3xl -z-10 animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-cyan-300/30 rounded-full blur-3xl -z-10 animate-pulse delay-1000"></div>

    <!-- Login Card -->
    <div class="w-full max-w-md bg-white/80 backdrop-blur-xl border border-white p-8 rounded-2xl shadow-2xl shadow-red-500/10 relative">
        <!-- Top Gradient Border line -->
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-red-500 to-rose-600 rounded-t-2xl"></div>

        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-800">Selamat Datang</h2>
            <p class="mt-2 text-sm text-slate-500">Masuk ke portal ATS Anda</p>
        </div>

        <form wire:submit.prevent="authenticate" class="space-y-6">
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Alamat Email</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input wire:model="email" type="email" id="email" required
                        class="block w-full px-4 py-3 bg-white/50 border border-red-100 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all sm:text-sm"
                        placeholder="nama@perusahaan.com">
                </div>
                @error('email')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Kata Sandi</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input wire:model="password" type="password" id="password" required
                        class="block w-full px-4 py-3 bg-white/50 border border-red-100 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all sm:text-sm"
                        placeholder="••••••••">
                </div>
                @error('password')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input wire:model="remember" id="remember" type="checkbox"
                        class="h-4 w-4 bg-white border-red-200 text-red-600 focus:ring-red-500 rounded transition-colors">
                    <label for="remember" class="ml-2 block text-sm text-slate-500 select-none">Ingat saya</label>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all transform hover:-translate-y-0.5 active:translate-y-0 shadow-lg shadow-red-500/20">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-red-200 group-hover:text-red-100 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </span>
                    <span>Masuk</span>
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-slate-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-medium text-red-600 hover:text-red-500 transition-colors">Daftar sekarang</a>
            </p>
        </div>
    </div>
</div>
