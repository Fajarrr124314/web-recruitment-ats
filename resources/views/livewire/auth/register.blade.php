<div class="min-h-[80vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12 relative overflow-hidden bg-gradient-to-br from-red-50 to-white">
    <!-- Background Gradients -->
    <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-red-300/30 rounded-full blur-3xl -z-10 animate-pulse"></div>
    <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-rose-300/30 rounded-full blur-3xl -z-10 animate-pulse delay-1000"></div>

    <!-- Register Card -->
    <div class="w-full max-w-md bg-white/80 backdrop-blur-xl border border-white p-8 rounded-2xl shadow-2xl shadow-red-500/10 relative">
        <!-- Top Gradient Border line -->
        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-red-500 to-rose-600 rounded-t-2xl"></div>

        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-800">Buat Akun Baru</h2>
            <p class="mt-2 text-sm text-slate-500">Mulailah dengan membuat akun portal ATS Anda</p>
        </div>

        <form wire:submit.prevent="register" class="space-y-5">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input wire:model="name" type="text" id="name" required
                        class="block w-full px-4 py-3 bg-white/50 border border-red-100 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all sm:text-sm"
                        placeholder="John Doe">
                </div>
                @error('name')
                    <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Alamat Email</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input wire:model="email" type="email" id="email" required
                        class="block w-full px-4 py-3 bg-white/50 border border-red-100 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all sm:text-sm"
                        placeholder="nama@email.com">
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

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi Kata Sandi</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input wire:model="password_confirmation" type="password" id="password_confirmation" required
                        class="block w-full px-4 py-3 bg-white/50 border border-red-100 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all sm:text-sm"
                        placeholder="••••••••">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all transform hover:-translate-y-0.5 active:translate-y-0 shadow-lg shadow-red-500/20">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-red-200 group-hover:text-red-100 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </span>
                    <span>Daftar Akun</span>
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-slate-500">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-medium text-red-600 hover:text-red-500 transition-colors">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
