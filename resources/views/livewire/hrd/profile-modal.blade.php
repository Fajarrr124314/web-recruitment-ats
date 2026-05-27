<div x-data="{ show: @entangle('isOpen') }" x-show="show" class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">
    <!-- Backdrop Blur -->
    <div x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="@this.closeModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity"></div>

    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
        <!-- Modal Card -->
        <div x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-[2rem] bg-white border border-red-100 text-left shadow-2xl transition-all w-full max-w-lg p-6 sm:p-8">
            
            <!-- Top Gradient Border -->
            <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-red-500 via-red-600 to-red-700"></div>

            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-red-50 pb-4 mb-6">
                <div class="flex items-center space-x-3">
                    <span class="p-2.5 bg-red-50 text-red-500 rounded-2xl border border-red-100 shadow-inner">
                        <svg class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800">Pengaturan Akun HRD</h3>
                        <p class="text-xs text-slate-500 font-light">Perbarui nama lengkap, email, atau ganti kata sandi Anda.</p>
                    </div>
                </div>
                <button type="button" @click="@this.closeModal()" class="text-slate-400 hover:text-red-500 p-1.5 rounded-full hover:bg-red-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="save" class="space-y-5">
                <!-- Nama -->
                <div>
                    <label for="profile_name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <input type="text" id="profile_name" wire:model.defer="name" required
                            class="w-full px-4 py-3 border border-red-100 rounded-xl focus:ring-2 focus:ring-red-500/30 focus:border-red-500 text-sm bg-white/50 text-slate-800 placeholder-slate-400 transition-all focus:outline-none">
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="profile_email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Email</label>
                    <div class="relative">
                        <input type="email" id="profile_email" wire:model.defer="email" required
                            class="w-full px-4 py-3 border border-red-100 rounded-xl focus:ring-2 focus:ring-red-500/30 focus:border-red-500 text-sm bg-white/50 text-slate-800 placeholder-slate-400 transition-all focus:outline-none">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="border-t border-slate-100 pt-4">
                    <span class="block text-xs font-bold text-red-500/80 uppercase tracking-wider mb-3">Ubah Kata Sandi (Opsional)</span>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="profile_password" class="block text-xs font-medium text-slate-600 mb-1.5">Kata Sandi Baru</label>
                            <input type="password" id="profile_password" wire:model.defer="password" placeholder="Kosongkan jika tidak ingin mengubah"
                                class="w-full px-4 py-3 border border-red-100 rounded-xl focus:ring-2 focus:ring-red-500/30 focus:border-red-500 text-sm bg-white/50 text-slate-800 placeholder-slate-400 transition-all focus:outline-none">
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="profile_password_confirmation" class="block text-xs font-medium text-slate-600 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" id="profile_password_confirmation" wire:model.defer="password_confirmation" placeholder="Tulis ulang kata sandi baru"
                                class="w-full px-4 py-3 border border-red-100 rounded-xl focus:ring-2 focus:ring-red-500/30 focus:border-red-500 text-sm bg-white/50 text-slate-800 placeholder-slate-400 transition-all focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-3 border-t border-red-50 pt-5 mt-6">
                    <button type="button" @click="@this.closeModal()"
                        class="px-4 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition-all active:scale-95 focus:outline-none">
                        Batal
                    </button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 rounded-xl shadow-md shadow-red-500/10 hover:shadow-red-500/25 transition-all transform active:scale-95 flex items-center gap-2 focus:outline-none">
                        <!-- Spinner when loading -->
                        <svg wire:loading class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
