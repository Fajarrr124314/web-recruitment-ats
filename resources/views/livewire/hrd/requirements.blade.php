<div class="space-y-10">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Persiapan Lamaran</h1>
        <p class="mt-1 text-sm text-slate-500">Atur posisi lowongan yang dibuka dan persyaratan dokumen/jawaban untuk kandidat.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Left Card: Portal Activation (Red/Green Glassmorphism) -->
        <div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 shadow-xl backdrop-blur-xl border transition-all duration-500 
            {{ $globalIsActive 
                ? 'bg-gradient-to-br from-green-500/10 to-emerald-500/20 border-green-200/50 shadow-green-500/20' 
                : 'bg-gradient-to-br from-red-500/10 to-rose-500/20 border-red-200/50 shadow-red-500/20' }}">
            
            <div class="absolute inset-0 bg-white/40 mix-blend-overlay"></div>
            
            <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest block mb-2
                        {{ $globalIsActive ? 'text-green-600' : 'text-red-600' }}">
                        Status Portal Utama
                    </span>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                        Akses Pendaftaran
                    </h2>
                    <p class="text-slate-600 text-xs mt-2 leading-relaxed font-medium">
                        {{ $globalIsActive 
                            ? 'Kandidat saat ini dapat melihat lowongan dan mengirimkan lamaran kerja secara langsung.' 
                            : 'Portal sedang ditutup. Kandidat tidak dapat mengirimkan lamaran saat ini.' }}
                    </p>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <span id="portal-status-badge" class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-extrabold tracking-wide uppercase shadow-sm
                        {{ $globalIsActive ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300' }}">
                        ● {{ $globalIsActive ? 'PORTAL AKTIF' : 'PORTAL NONAKTIF' }}
                    </span>

                    <!-- Instant Toggle Switch (Alpine.js optimistic UI — zero-lag) -->
                    <button
                        x-data="{ active: {{ $globalIsActive ? 'true' : 'false' }} }"
                        @click="active = !active; $wire.toggleGlobalActive()"
                        :class="active 
                            ? 'bg-gradient-to-r from-green-500 to-emerald-500 focus:ring-green-500 shadow-lg shadow-green-500/40' 
                            : 'bg-gradient-to-r from-red-500 to-rose-500 focus:ring-red-500 shadow-lg shadow-red-500/40'"
                        class="relative inline-flex h-8 w-14 shrink-0 cursor-pointer items-center justify-center rounded-full focus:outline-none focus:ring-4 focus:ring-offset-2 transition-colors duration-200 ease-in-out"
                        role="switch"
                        :aria-checked="active.toString()">
                        <span aria-hidden="true"
                            :class="active ? 'translate-x-3' : '-translate-x-3'"
                            class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                        </span>
                    </button>
                </div>
                
                @if (session()->has('success_global'))
                    <div class="mt-2 p-3 rounded-xl border text-[11px] font-bold text-center {{ $globalIsActive ? 'bg-green-500/10 border-green-200/50 text-green-700' : 'bg-red-500/10 border-red-200/50 text-red-700' }} animate-pulse">
                        {{ session('success_global') }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Card: Scheduled Opening (Red Glassmorphism) -->
        <div class="relative overflow-hidden rounded-3xl p-6 sm:p-8 shadow-xl backdrop-blur-xl border border-rose-200/50 bg-gradient-to-br from-rose-500/10 via-red-500/5 to-orange-500/10 shadow-rose-500/10">
            <div class="absolute inset-0 bg-white/50 mix-blend-overlay"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <div>
                    <span class="text-[10px] font-extrabold text-rose-600 uppercase tracking-widest block mb-2">Automasi Waktu</span>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight">
                        Jadwal Buka Otomatis
                    </h2>
                    <p class="text-slate-600 text-xs mt-2 leading-relaxed font-medium">
                        Atur kapan portal akan terbuka secara otomatis. Sebuah hitung mundur (countdown) akan ditampilkan di bawah.
                    </p>
                </div>

                <div class="mt-6 flex-1 flex flex-col justify-end">
                    @if (session()->has('success_schedule'))
                        <div class="mb-3 bg-green-50/80 backdrop-blur-sm border border-green-200 text-green-700 px-4 py-2 rounded-xl text-[11px] font-bold text-center">
                            {{ session('success_schedule') }}
                        </div>
                    @endif

                    @if($globalScheduledOpenAt)
                        <!-- Active Schedule Mode -->
                        <div class="bg-white/60 backdrop-blur-md border border-rose-200 rounded-2xl p-4 flex flex-col items-center justify-center text-center shadow-sm">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Akan Terbuka Pada</span>
                            <div class="flex items-center gap-2 text-rose-600">
                                <svg class="w-5 h-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-black tracking-tight">
                                    {{ \Illuminate\Support\Carbon::parse($globalScheduledOpenAt)->translatedFormat('d M Y, H:i') }}
                                </span>
                            </div>
                            
                            <button wire:click="cancelSchedule" 
                                class="mt-4 px-4 py-2 rounded-xl text-[10px] font-extrabold text-white bg-slate-800 hover:bg-slate-900 transition-colors uppercase tracking-widest shadow-md">
                                Batalkan Jadwal
                            </button>
                        </div>
                    @else
                        <!-- Set Schedule Mode -->
                        <div class="space-y-3">
                            <input type="datetime-local" wire:model="globalScheduledOpenAt"
                                class="w-full px-4 py-3 border border-rose-200/80 rounded-xl focus:ring-rose-500 focus:border-rose-500 text-xs shadow-sm bg-white/80 text-slate-800 font-medium font-mono">
                            @error('globalScheduledOpenAt') <p class="text-[10px] text-red-500 font-bold px-1">{{ $message }}</p> @enderror
                            
                            <button wire:click="saveScheduledOpen"
                                class="w-full px-5 py-3 rounded-xl text-xs font-extrabold tracking-wider uppercase text-white bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 transition-all shadow-md shadow-rose-500/20 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Aktifkan Hitung Mundur
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($globalScheduledOpenAt && !\Illuminate\Support\Carbon::parse($globalScheduledOpenAt)->isPast())
        <!-- Automatic Scheduled Countdown (Premium Red Gradient Banner) -->
        <div class="relative overflow-hidden rounded-3xl p-6 bg-gradient-to-r from-red-600 via-rose-600 to-orange-500 text-white shadow-xl shadow-red-500/10 animate-fade-in"
             x-data="{
                deadline: new Date('{{ \Carbon\Carbon::parse($globalScheduledOpenAt)->toIso8601String() }}').getTime(),
                days: '00', hours: '00', minutes: '00', seconds: '00',
                init() {
                    this.updateTimer();
                    setInterval(() => this.updateTimer(), 1000);
                },
                updateTimer() {
                    const now = new Date().getTime();
                    const t = this.deadline - now;
                    if (t >= 0) {
                        this.days = String(Math.floor(t / (1000 * 60 * 60 * 24))).padStart(2, '0');
                        this.hours = String(Math.floor((t % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                        this.minutes = String(Math.floor((t % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                        this.seconds = String(Math.floor((t % (1000 * 60)) / 1000)).padStart(2, '0');
                    } else {
                        window.location.reload();
                    }
                }
             }">
             <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:24px_24px] opacity-30"></div>
             <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                 <div class="flex items-center gap-4 text-center md:text-left">
                     <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shrink-0 shadow-inner">
                         <svg class="w-6 h-6 text-white animate-spin" style="animation-duration: 6s;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                         </svg>
                     </div>
                     <div>
                         <span class="text-[9px] font-black uppercase tracking-widest bg-white/20 border border-white/10 px-2.5 py-0.5 rounded-full inline-block mb-1">Hitung Mundur Pembukaan Portal</span>
                         <h3 class="text-lg font-black tracking-tight">Portal Registrasi Dibuka Otomatis</h3>
                         <p class="text-[11px] text-red-100 font-semibold mt-0.5">Penjadwalan aktif: membuka pendaftaran pada {{ \Carbon\Carbon::parse($globalScheduledOpenAt)->translatedFormat('d F Y, H:i') }} WIB</p>
                     </div>
                 </div>

                 <!-- Countdown Blocks -->
                 <div class="flex items-center gap-3 sm:gap-4 bg-black/10 border border-white/10 p-3 px-5 rounded-2xl backdrop-blur-md">
                     <div class="flex flex-col items-center">
                         <span class="text-xl sm:text-2xl font-black font-mono tracking-tight" x-text="days"></span>
                         <span class="text-[8px] font-bold text-red-200 uppercase tracking-widest">Hari</span>
                     </div>
                     <span class="text-xl font-bold text-red-200/50">:</span>
                     <div class="flex flex-col items-center">
                         <span class="text-xl sm:text-2xl font-black font-mono tracking-tight" x-text="hours"></span>
                         <span class="text-[8px] font-bold text-red-200 uppercase tracking-widest">Jam</span>
                     </div>
                     <span class="text-xl font-bold text-red-200/50">:</span>
                     <div class="flex flex-col items-center">
                         <span class="text-xl sm:text-2xl font-black font-mono tracking-tight" x-text="minutes"></span>
                         <span class="text-[8px] font-bold text-red-200 uppercase tracking-widest">Menit</span>
                     </div>
                     <span class="text-xl font-bold text-red-200/50">:</span>
                     <div class="flex flex-col items-center">
                         <span class="text-xl sm:text-2xl font-black font-mono tracking-tight" x-text="seconds"></span>
                         <span class="text-[8px] font-bold text-red-200 uppercase tracking-widest">Detik</span>
                     </div>
                 </div>
             </div>
        </div>
    @endif

    <!-- Job Positions Section -->
    <div>
        <h2 class="text-xl font-bold text-slate-800 mb-4 border-b border-red-100 pb-2">1. Pengaturan Posisi Lowongan</h2>
        
        @if (session()->has('success_job'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                {{ session('success_job') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Add Job -->
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6 h-fit">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Tambah Posisi</h3>
                <form wire:submit.prevent="addJobPosition" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nama Posisi</label>
                        <input type="text" wire:model="job_title" class="mt-1 w-full px-3 py-2 border border-red-200 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Contoh: Laravel Developer">
                        @error('job_title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Persyaratan / Kualifikasi</label>
                        <textarea wire:model="job_requirements" rows="4" class="mt-1 w-full px-3 py-2 border border-red-200 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Contoh:&#10;- Minimal 2 tahun pengalaman Laravel&#10;- Memahami RESTful API&#10;- Bisa bekerja dalam tim"></textarea>
                        @error('job_requirements') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-sm font-medium text-slate-700">Otomatis Tutup Pada (Opsional)</label>
                            @if($expires_at)
                                <button type="button" wire:click="setPresetTime('clear')" class="text-[10px] text-red-500 font-bold hover:underline">Hapus</button>
                            @endif
                        </div>
                        <input type="datetime-local" wire:model="expires_at" class="w-full px-3 py-2 border border-red-200 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm font-mono bg-slate-50">
                        @error('expires_at') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        
                        <div class="mt-2 flex flex-wrap gap-1">
                            <button type="button" wire:click="setPresetTime('1d')" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded text-[10px] font-bold transition-colors">1 Hari</button>
                            <button type="button" wire:click="setPresetTime('1w')" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded text-[10px] font-bold transition-colors">1 Minggu</button>
                            <button type="button" wire:click="setPresetTime('1m')" class="px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded text-[10px] font-bold transition-colors">1 Bulan</button>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2.5 mt-2 px-4 border border-transparent rounded-xl text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-md font-bold text-sm transition-all">
                        Tambah Posisi
                    </button>
                </form>
            </div>

            <!-- List Jobs -->
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($jobPositions as $job)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-red-100 flex flex-col justify-between transition-all hover:shadow-md h-fit gap-4">
                        <div>
                            <span class="font-bold text-slate-800 text-sm block">{{ $job->title }}</span>
                            @if($job->requirements)
                                <div class="mt-2 text-xs text-slate-500 bg-slate-50/60 p-3 rounded-xl border border-slate-100 whitespace-pre-line font-medium leading-relaxed">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Syarat & Kualifikasi:</span>
                                    {{ $job->requirements }}
                                </div>
                            @endif
                            @if($job->expires_at)
                                <span class="text-[10px] font-semibold flex items-center gap-1 mt-2.5 {{ \Illuminate\Support\Carbon::parse($job->expires_at)->isPast() ? 'text-red-500' : 'text-amber-600' }}">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ \Illuminate\Support\Carbon::parse($job->expires_at)->isPast() ? 'Telah Ditutup' : 'Tutup: ' . \Illuminate\Support\Carbon::parse($job->expires_at)->translatedFormat('d M Y H:i') }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-50 pt-3">
                        <button
                            x-data="{ active: {{ $job->is_active ? 'true' : 'false' }} }"
                            @click="active = !active; $wire.toggleJobActive({{ $job->id }})"
                            :class="active 
                                ? 'border-green-200 text-green-600 bg-green-50' 
                                : 'border-slate-200 text-slate-500 bg-slate-50'"
                            class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg border transition-colors duration-150">
                            <span x-text="active ? 'Aktif' : 'Nonaktif'"></span>
                        </button>
                            <div class="flex items-center gap-1">
                                <button wire:click="editJobPosition({{ $job->id }})" class="text-blue-500 hover:text-blue-700 p-1 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Posisi">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="deleteJobPosition({{ $job->id }})" class="text-red-500 hover:text-red-700 p-1 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Posisi">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-6 text-sm text-slate-500 italic bg-white/50 rounded-2xl border border-red-100 border-dashed">
                        Belum ada posisi lowongan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Requirements Section -->
    <div>
        <h2 class="text-xl font-bold text-slate-800 mb-4 border-b border-red-100 pb-2">2. Pengaturan Persyaratan / Pertanyaan</h2>
        
        @if (session()->has('success_req'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                {{ session('success_req') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Add Req -->
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6 h-fit">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Tambah Persyaratan</h3>
                
                <form wire:submit.prevent="addRequirement" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Pertanyaan / Label</label>
                        <input type="text" wire:model="question" class="mt-1 w-full px-3 py-2 border border-red-200 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Contoh: Upload KTP">
                        @error('question') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tipe Input</label>
                        <select wire:model.live="type" class="mt-1 w-full px-3 py-2 border border-red-200 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm font-medium">
                            <option value="text">Teks Singkat</option>
                            <option value="textarea">Teks Panjang (Paragraf)</option>
                            <option value="file">Upload File (PDF/Image)</option>
                            <option value="select">Pilihan Ganda (Dropdown)</option>
                        </select>
                        @error('type') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    @if($type === 'select')
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Opsi Pilihan (Dropdown)</label>
                            
                            @foreach($optionsList as $index => $opt)
                                <div class="flex items-center gap-2">
                                    <input type="text" wire:model="optionsList.{{ $index }}" class="flex-1 px-3 py-1.5 border border-slate-300 rounded-md focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Opsi {{ $index + 1 }}">
                                    @if(count($optionsList) > 1)
                                        <button type="button" wire:click="removeOptionInput({{ $index }})" class="p-1.5 text-red-500 hover:bg-red-100 rounded-md transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                            
                            @error('optionsList') <span class="text-xs text-red-500 block">{{ $message }}</span> @enderror
                            
                            <button type="button" wire:click="addOptionInput" class="text-xs font-bold text-red-600 hover:text-red-700 flex items-center gap-1 mt-2">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                Tambah Opsi Lainnya
                            </button>
                        </div>
                    @endif

                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_required" id="is_required" class="rounded border-red-300 text-red-600 focus:ring-red-500 h-4 w-4">
                        <label for="is_required" class="ml-2 text-sm text-slate-700">Wajib Diisi</label>
                    </div>

                    <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-xl text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-md font-medium text-sm transition-all">
                        Tambah Persyaratan
                    </button>
                </form>
            </div>

            <!-- List Reqs -->
            <div class="lg:col-span-2 space-y-4">
                @forelse($requirements as $req)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-red-100 flex items-center justify-between transition-all hover:shadow-md">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-slate-800">{{ $req->question }}</span>
                                @if($req->is_required)
                                    <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Wajib</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 capitalize flex items-center gap-1 font-medium">
                                <svg class="w-4 h-4 text-slate-450" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    @if($req->type === 'file')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    @endif
                                </svg>
                                Tipe: {{ $req->type }}
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <button
                                x-data="{ active: {{ $req->is_active ? 'true' : 'false' }} }"
                                @click="active = !active; $wire.toggleActive({{ $req->id }})"
                                :class="active 
                                    ? 'border-green-200 text-green-600 bg-green-50' 
                                    : 'border-slate-200 text-slate-500 bg-slate-50'"
                                class="text-xs font-extrabold uppercase tracking-wider px-2.5 py-1 rounded-lg border transition-colors duration-150">
                                <span x-text="active ? 'Aktif' : 'Nonaktif'"></span>
                            </button>
                            <button wire:click="editRequirement({{ $req->id }})" class="text-blue-500 hover:text-blue-700 p-1.5 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Persyaratan">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button wire:click="deleteRequirement({{ $req->id }})" class="text-red-500 hover:text-red-700 p-1.5 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Persyaratan">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white/50 rounded-2xl border border-red-100 border-dashed">
                        <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">Belum ada persyaratan</h3>
                        <p class="mt-1 text-sm text-slate-500">Silakan tambahkan persyaratan baru di form sebelah kiri.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Floating edit modal for Job Positions -->
    @if($editingJobId)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[999] flex items-center justify-center p-4 animate-fade-in">
            <div class="bg-white/90 backdrop-blur-xl border border-red-200/50 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative overflow-hidden transform scale-100 transition-all duration-300">
                <div class="absolute -right-16 -top-16 w-36 h-36 bg-gradient-to-br from-red-500/10 to-orange-500/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-16 -bottom-16 w-36 h-36 bg-gradient-to-br from-slate-500/10 to-slate-400/10 rounded-full blur-2xl"></div>
                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-red-500 to-rose-600"></div>

                <div class="relative z-10 space-y-5">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-[9px] font-black text-red-500 uppercase tracking-widest block">Ubah Data Posisi</span>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">Edit Posisi Lowongan</h3>
                        </div>
                        <button type="button" wire:click="closeEditJob" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="updateJobPosition" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Posisi</label>
                            <input type="text" wire:model="editingJobTitle" class="w-full px-3.5 py-2.5 text-xs border border-red-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all shadow-inner bg-white">
                            @error('editingJobTitle') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Persyaratan / Kualifikasi</label>
                            <textarea wire:model="editingJobRequirements" rows="4" class="w-full px-3.5 py-2.5 text-xs border border-red-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all shadow-inner bg-white" placeholder="Sebutkan syarat..."></textarea>
                            @error('editingJobRequirements') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Otomatis Tutup Pada</label>
                            <input type="datetime-local" wire:model="editingJobExpiresAt" class="w-full px-3.5 py-2.5 text-xs border border-red-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all shadow-inner bg-white font-mono">
                            @error('editingJobExpiresAt') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
                            <button type="button" wire:click="closeEditJob" class="px-4.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-red-500/10">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Floating edit modal for Requirements -->
    @if($editingRequirementId)
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[999] flex items-center justify-center p-4 animate-fade-in">
            <div class="bg-white/90 backdrop-blur-xl border border-red-200/50 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative overflow-hidden transform scale-100 transition-all duration-300">
                <div class="absolute -right-16 -top-16 w-36 h-36 bg-gradient-to-br from-red-500/10 to-orange-500/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-16 -bottom-16 w-36 h-36 bg-gradient-to-br from-slate-500/10 to-slate-400/10 rounded-full blur-2xl"></div>
                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-red-500 to-rose-600"></div>

                <div class="relative z-10 space-y-5">
                    <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                        <div>
                            <span class="text-[9px] font-black text-red-500 uppercase tracking-widest block">Ubah Data Persyaratan</span>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">Edit Persyaratan / Pertanyaan</h3>
                        </div>
                        <button type="button" wire:click="closeEditRequirement" class="text-slate-400 hover:text-slate-650 p-1.5 rounded-xl hover:bg-slate-100 transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="updateRequirement" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pertanyaan / Label</label>
                            <input type="text" wire:model="editingRequirementQuestion" class="w-full px-3.5 py-2.5 text-xs border border-red-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all shadow-inner bg-white">
                            @error('editingRequirementQuestion') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tipe Input</label>
                            <select wire:model.live="editingRequirementType" class="w-full px-3.5 py-2.5 text-xs border border-red-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all shadow-inner bg-white font-semibold text-slate-750">
                                <option value="text">Teks Singkat</option>
                                <option value="textarea">Teks Panjang (Paragraf)</option>
                                <option value="file">Upload File (PDF/Image)</option>
                                <option value="select">Pilihan Ganda (Dropdown)</option>
                            </select>
                            @error('editingRequirementType') <span class="text-[10px] text-red-500 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        @if($editingRequirementType === 'select')
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3">
                                <label class="block text-[10px] font-extrabold text-slate-700 uppercase tracking-wider">Opsi Pilihan (Dropdown)</label>
                                
                                @foreach($editingRequirementOptionsList as $index => $opt)
                                    <div class="flex items-center gap-2">
                                        <input type="text" wire:model="editingRequirementOptionsList.{{ $index }}" class="flex-1 px-3 py-1.5 border border-slate-300 rounded-md focus:ring-red-500 focus:border-red-500 text-xs bg-white" placeholder="Opsi {{ $index + 1 }}">
                                        @if(count($editingRequirementOptionsList) > 1)
                                            <button type="button" wire:click="removeEditingOptionInput({{ $index }})" class="p-1.5 text-red-500 hover:bg-red-100 rounded-md transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                                
                                @error('editingRequirementOptionsList') <span class="text-xs text-red-500 block">{{ $message }}</span> @enderror
                                
                                <button type="button" wire:click="addEditingOptionInput" class="text-xs font-bold text-red-650 hover:text-red-700 flex items-center gap-1 mt-2">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                    Tambah Opsi Lainnya
                                </button>
                            </div>
                        @endif

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="editingRequirementIsRequired" id="edit_is_required" class="rounded border-red-300 text-red-600 focus:ring-red-500 h-4 w-4">
                            <label for="edit_is_required" class="ml-2 text-xs font-bold text-slate-700 uppercase tracking-wider select-none">Wajib Diisi</label>
                        </div>

                        <div class="flex justify-end gap-2.5 pt-3 border-t border-slate-100">
                            <button type="button" wire:click="closeEditRequirement" class="px-4.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-red-500/10">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
