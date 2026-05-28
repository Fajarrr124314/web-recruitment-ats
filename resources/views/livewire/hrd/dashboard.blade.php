<div class="max-w-7xl mx-auto py-4 sm:py-8">
    <!-- Dashboard Header & Controls -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Dashboard ATS</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola kandidat pelamar kerja dan penilaian hasil interview secara kolaboratif.</p>
        </div>

        <!-- Controls: Search, Filter, View Toggle -->
        <div class="flex flex-wrap items-center gap-3">
            <!-- Search Input -->
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama/email..." 
                    class="pl-9 pr-3 py-2 text-sm border border-red-200 rounded-xl focus:ring-red-500 focus:border-red-500 shadow-sm w-full sm:w-48 lg:w-64 transition-all">
                <svg class="h-4 w-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Job Filter Dropdown -->
            <select wire:model.live="jobTitleFilter" class="py-2.5 pl-3 pr-8 text-sm font-bold border border-red-200 rounded-xl focus:ring-red-500 shadow-sm text-slate-700 bg-white">
                <option value="">Semua Posisi</option>
                @foreach($availableJobTitles as $title)
                    <option value="{{ $title }}">{{ $title }}</option>
                @endforeach
            </select>

        </div>
    </div>

    <!-- Alert Success Board -->
    @if (session()->has('board_success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center space-x-2">
            <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('board_success') }}</span>
        </div>
    @endif

    <!-- Floating Bulk Actions Bar -->
    @if(count($selectedApplications) > 0)
        <div class="fixed bottom-4 sm:bottom-6 left-1/2 transform -translate-x-1/2 z-[999] bg-slate-900/95 backdrop-blur-md text-white px-2.5 py-2 sm:px-5 sm:py-3 rounded-2xl sm:rounded-3xl shadow-2xl flex flex-row flex-wrap items-center justify-center gap-1.5 sm:gap-3 border border-slate-700/80 max-w-[95vw] sm:max-w-[90vw] animate-slide-up text-[10px] sm:text-xs">
            <span class="font-black text-[10px] sm:text-xs bg-slate-800 px-2 py-1 sm:px-3 sm:py-1.5 rounded-full text-red-100 flex items-center gap-1 shrink-0 select-none border border-slate-700/50">
                <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-red-500 animate-ping"></span>
                ✓ {{ count($selectedApplications) }} <span class="hidden sm:inline">Terpilih</span>
            </span>
            
            <div class="h-5 w-px bg-slate-700 hidden sm:block"></div>
            
            <!-- Move status dropdown -->
            <select wire:change="bulkChangeStatus($event.target.value)" class="bg-slate-900 border border-slate-700 rounded-xl text-[10px] sm:text-xs py-1 sm:py-1.5 pl-2 pr-6 sm:pr-8 focus:ring-red-500 cursor-pointer shadow-inner font-bold text-slate-350 max-w-[95px] sm:max-w-none">
                <option value="" class="bg-slate-900">Pindah</option>
                <option value="Administrasi" class="bg-slate-900">Administrasi</option>
                <option value="Psikotes" class="bg-slate-900">Psikotes</option>
                <option value="Interview" class="bg-slate-900">Interview</option>
                <option value="MCU" class="bg-slate-900">MCU</option>
                <option value="Hired" class="bg-slate-900">Hired</option>
                <option value="Ditolak" class="bg-slate-900">Tolak / Gagal</option>
            </select>
            
            <!-- WhatsApp Massal button -->
            <button type="button" wire:click="openBulkModal" class="px-2 py-1 sm:px-3 sm:py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] sm:text-xs font-bold transition-all shadow-md shadow-emerald-500/10 flex items-center gap-1 border border-emerald-500/30 shrink-0">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.999 0C5.373 0 0 5.373 0 12c0 2.12.554 4.107 1.523 5.832L.053 23.404a.75.75 0 00.918.918l5.572-1.47A11.943 11.943 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 11.999 0zm.001 21.75a9.714 9.714 0 01-4.963-1.362l-.357-.212-3.705.977.977-3.591-.232-.369A9.718 9.718 0 012.25 12C2.25 6.615 6.614 2.25 12 2.25S21.75 6.615 21.75 12 17.386 21.75 12 21.75z"/></svg>
                <span>WA <span class="hidden sm:inline">Massal</span></span>
            </button>

            <!-- Gmail Massal button -->
            <button type="button" wire:click="openGmailCompose" class="px-2 py-1 sm:px-3 sm:py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[10px] sm:text-xs font-bold transition-all shadow-md shadow-rose-500/10 flex items-center gap-1 border border-rose-500/30 shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                <span>Gmail <span class="hidden sm:inline">Massal</span></span>
            </button>

            <div class="h-5 w-px bg-slate-700 hidden sm:block"></div>

            <!-- Gugurkan Massal button -->
            <button type="button" wire:click="openBulkReject" class="px-2 py-1 sm:px-3 sm:py-1.5 text-red-500 hover:text-red-400 rounded-xl text-[10px] sm:text-xs font-black tracking-widest uppercase transition-colors shrink-0">
                Gugur <span class="hidden sm:inline">Massal</span>
            </button>

            <button wire:click="clearSelection" class="text-slate-400 hover:text-white p-1 sm:p-1.5 rounded-xl hover:bg-slate-800 transition-colors shrink-0" title="Batal Pilih Semua">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    @endif


    @if($viewMode === 'kanban')
        @php
            $nowWib = \Carbon\Carbon::now('Asia/Jakarta');
            $hour = $nowWib->hour;
            
            if ($hour >= 5 && $hour < 11) {
                $greeting = 'Selamat Pagi';
                $icon = '<svg class="w-12 h-12 text-amber-500 hover:scale-105 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" /></svg>';
                $sub = 'Semangat pagi! Mari kita temukan talenta terbaik untuk tim hari ini.';
                $gradient = 'from-amber-500/20 via-orange-500/10 to-transparent';
                $borderColor = 'border-amber-200/50';
                $glowColor = 'shadow-amber-500/5';
            } elseif ($hour >= 11 && $hour < 15) {
                $greeting = 'Selamat Siang';
                $icon = '<svg class="w-12 h-12 text-orange-500 hover:scale-105 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" /></svg>';
                $sub = 'Hari yang produktif! Pantau perkembangan funnel rekrutmen terbaru Anda.';
                $gradient = 'from-blue-500/20 via-indigo-500/10 to-transparent';
                $borderColor = 'border-blue-200/50';
                $glowColor = 'shadow-blue-500/5';
            } elseif ($hour >= 15 && $hour < 18) {
                $greeting = 'Selamat Sore';
                $icon = '<svg class="w-12 h-12 text-rose-500 hover:scale-105 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1M12 19v1M21 12h-1M4 12H3m14.95-4.95l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" /></svg>';
                $sub = 'Sore yang menyenangkan. Mari tinjau kembali kemajuan seleksi kandidat.';
                $gradient = 'from-rose-500/20 via-pink-500/10 to-transparent';
                $borderColor = 'border-rose-200/50';
                $glowColor = 'shadow-rose-500/5';
            } else {
                $greeting = 'Selamat Malam';
                $icon = '<svg class="w-12 h-12 text-indigo-400 hover:scale-105 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>';
                $sub = 'Selamat beristirahat. Rekrutmen tetap berjalan secara otomatis ditenagai AI.';
                $gradient = 'from-indigo-950/40 via-purple-900/10 to-transparent';
                $borderColor = 'border-indigo-500/30';
                $glowColor = 'shadow-indigo-500/5';
            }
        @endphp

        <!-- Premium Glassmorphic Wide Greeting Card -->
        <div class="relative overflow-hidden rounded-3xl border {{ $borderColor }} bg-white/95 bg-gradient-to-r {{ $gradient }} p-6 sm:p-8 mb-8 shadow-xl {{ $glowColor }} flex flex-col md:flex-row items-center justify-between gap-6 transition-all duration-150 hover:shadow-2xl">
            <!-- Shimmer effect -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:24px_24px] opacity-40"></div>
            <div class="absolute -right-24 -top-24 w-60 h-60 bg-gradient-to-br from-red-500/10 to-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <!-- Left Info: Greeting & Motivation -->
            <div class="relative z-10 flex items-center gap-4 text-center md:text-left flex-col sm:flex-row">
                <div class="shrink-0 select-none hover:scale-105 transition-transform duration-300">
                    {!! $icon !!}
                </div>
                <div>
                    <span class="text-[9px] font-black text-red-500 uppercase tracking-widest bg-red-50 border border-red-200/50 px-2.5 py-0.5 rounded-full inline-block mb-1 shadow-sm">
                        {{ $nowWib->translatedFormat('l, d F Y') }}
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">
                        {{ $greeting }}, {{ auth()->user()->name }}!
                    </h2>
                    <p class="text-slate-500 text-xs sm:text-sm font-medium mt-1 max-w-xl leading-relaxed">
                        {{ $sub }} <span class="block mt-1 text-[11px] font-bold text-red-650">📊 Rekap & Analitik: Ringkasan data statistik funnel rekrutmen, tingkat penerimaan, dan performa kandidat secara menyeluruh.</span>
                    </p>
                </div>
            </div>

            <!-- Right Info: Stats Widget -->
            <div class="relative z-10 flex flex-wrap justify-center md:justify-end gap-3 sm:gap-4 w-full md:w-auto shrink-0 select-none">
                <!-- Stat 1 -->
                <div class="bg-white/60 backdrop-blur-md border border-slate-200/50 rounded-2xl p-4 shadow-sm text-center min-w-[100px] flex-1 sm:flex-initial">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Lowongan Aktif</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-800 block mt-0.5">{{ $activeJobCount }}</span>
                    <span class="text-[9px] font-semibold text-emerald-600">Posisi</span>
                </div>
                <!-- Stat 2 -->
                <div class="bg-white/60 backdrop-blur-md border border-slate-200/50 rounded-2xl p-4 shadow-sm text-center min-w-[100px] flex-1 sm:flex-initial">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Pelamar</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-800 block mt-0.5">{{ $totalCandidatesCount }}</span>
                    <span class="text-[9px] font-semibold text-slate-500">Kandidat</span>
                </div>
                <!-- Stat 3 -->
                <div class="bg-white/60 backdrop-blur-md border border-slate-200/50 rounded-2xl p-4 shadow-sm text-center min-w-[100px] flex-1 sm:flex-initial">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Masuk Hari Ini</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-800 block mt-0.5">{{ $todayApplicationsCount }}</span>
                    <span class="text-[9px] font-semibold text-red-500 font-bold">New Apply</span>
                </div>
            </div>
        </div>

        <!-- Kanban Board Grid (Modern Premium Progress Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            @foreach(['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired'] as $stage)
                @php
                    $totalCount = $stageSummaries[$stage]['totalCount'] ?? 0;
                    $percentage = $stageSummaries[$stage]['percentage'] ?? 0;
                    
                    // Premium gradients, shadows, icons and links for each stage
                    $cardTheme = match($stage) {
                        'Administrasi' => [
                            'gradient' => 'from-blue-600 to-indigo-500',
                            'bgLight' => 'bg-blue-50/50',
                            'badge' => 'bg-blue-50 text-blue-600 border-blue-200/60',
                            'icon' => '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                            'route' => route('hrd.process', 'administrasi'),
                            'glow' => 'shadow-blue-500/5 hover:shadow-blue-500/12 hover:border-blue-400/40',
                        ],
                        'Psikotes' => [
                            'gradient' => 'from-rose-500 to-red-500',
                            'bgLight' => 'bg-rose-50/50',
                            'badge' => 'bg-rose-50 text-rose-600 border-rose-200/60',
                            'icon' => '<svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>',
                            'route' => route('hrd.process', 'psikotes'),
                            'glow' => 'shadow-rose-500/5 hover:shadow-rose-500/12 hover:border-rose-400/40',
                        ],
                        'Interview' => [
                            'gradient' => 'from-amber-500 to-orange-500',
                            'bgLight' => 'bg-amber-50/50',
                            'badge' => 'bg-amber-50 text-amber-600 border-amber-200/60',
                            'icon' => '<svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>',
                            'route' => route('hrd.process', 'interview'),
                            'glow' => 'shadow-amber-500/5 hover:shadow-amber-500/12 hover:border-amber-400/40',
                        ],
                        'MCU' => [
                            'gradient' => 'from-purple-500 to-indigo-500',
                            'bgLight' => 'bg-purple-50/50',
                            'badge' => 'bg-purple-50 text-purple-600 border-purple-200/60',
                            'icon' => '<svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
                            'route' => route('hrd.process', 'mcu'),
                            'glow' => 'shadow-purple-500/5 hover:shadow-purple-500/12 hover:border-purple-400/40',
                        ],
                        'Hired' => [
                            'gradient' => 'from-emerald-500 to-green-500',
                            'bgLight' => 'bg-emerald-50/50',
                            'badge' => 'bg-emerald-50 text-emerald-600 border-emerald-200/60',
                            'icon' => '<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                            'route' => route('hrd.hired'),
                            'glow' => 'shadow-emerald-500/5 hover:shadow-emerald-500/12 hover:border-emerald-400/40',
                        ],
                    };
                @endphp

                <!-- Premium Progress Stage Card -->
                <div class="relative bg-white/80 border border-slate-200/50 rounded-3xl p-6 flex flex-col justify-between shadow-lg {{ $cardTheme['glow'] }} hover:-translate-y-1 transition-all duration-300 group overflow-hidden">
                    <!-- Subtle background blur gradient decorative blob -->
                    <div class="absolute -right-8 -top-8 w-24 h-24 bg-gradient-to-br {{ $cardTheme['gradient'] }} opacity-5 blur-2xl group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                    
                    <!-- Top border gradient indicator -->
                    <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r {{ $cardTheme['gradient'] }}"></div>
                    
                    <div class="space-y-4">
                        <!-- Card Header Info -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-xl {{ $cardTheme['bgLight'] }} border border-slate-100">
                                    {!! $cardTheme['icon'] !!}
                                </div>
                                <span class="font-extrabold text-slate-800 tracking-wide text-xs uppercase">{{ $stage }}</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black {{ $cardTheme['badge'] }} border shadow-inner">
                                {{ $percentage }}%
                            </span>
                        </div>
                        
                        <!-- Big Total Number -->
                        <div class="flex items-baseline gap-2 pt-1">
                            <span class="text-5xl font-black bg-gradient-to-r {{ $cardTheme['gradient'] }} bg-clip-text text-transparent tracking-tight">
                                {{ $totalCount }}
                            </span>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kandidat</span>
                        </div>
                        
                        <!-- Progress Bar (Percentage of active candidates) -->
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner relative">
                            <div class="bg-gradient-to-r {{ $cardTheme['gradient'] }} h-full rounded-full transition-all duration-700 ease-out shadow-[0_0_8px_rgba(79,70,229,0.3)]" style="width: {{ $percentage }}%"></div>
                        </div>

                        <!-- Sebaran Posisi (Job Position Distribution) -->
                        <div class="pt-3.5 border-t border-slate-100/80 space-y-2">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Sebaran Posisi</span>
                            @if(!empty($stageSummaries[$stage]['jobDistribution']))
                                <div class="space-y-1.5 max-h-[110px] overflow-y-auto pr-1 select-none">
                                    @foreach($stageSummaries[$stage]['jobDistribution'] as $job => $count)
                                        <div class="flex justify-between items-center text-[10px] font-bold text-slate-600">
                                            <span class="truncate max-w-[130px] text-slate-500 hover:text-slate-700 transition-colors" title="{{ $job }}">{{ $job }}</span>
                                            <span class="font-black text-[9px] text-slate-800 bg-slate-100 border border-slate-200/50 px-2 py-0.5 rounded-full shadow-sm shrink-0">{{ $count }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex items-center gap-1.5 py-1 text-slate-450 italic text-[10px]">
                                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6"></path></svg>
                                    <span>Tidak ada data</span>
                                </div>
                            @endif
                        </div>

                        <!-- Kandidat Terbaru (Latest Candidate) -->
                        <div class="pt-3 border-t border-slate-100/80">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Kandidat Terbaru</span>
                            @if($stageSummaries[$stage]['latestCandidateName'])
                                <span class="text-[11px] font-extrabold text-slate-700 truncate block flex items-center gap-1.5" title="{{ $stageSummaries[$stage]['latestCandidateName'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0 animate-pulse"></span>
                                    {{ $stageSummaries[$stage]['latestCandidateName'] }}
                                </span>
                            @else
                                <span class="text-[10px] text-slate-450 italic block">-</span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Footer Action -->
                    <div class="pt-4 mt-4 border-t border-slate-100/80 flex items-center justify-between">
                        <span class="text-[9px] font-bold text-slate-450 uppercase tracking-wider">Tinjauan Progres</span>
                        <a href="{{ $cardTheme['route'] }}" 
                           class="flex items-center gap-1 text-[10px] font-black tracking-wider uppercase bg-gradient-to-r {{ $cardTheme['gradient'] }} bg-clip-text text-transparent hover:scale-105 transition-transform duration-200">
                            <span>Kelola Tahap</span>
                            <svg class="w-3.5 h-3.5 text-current transform group-hover:translate-x-0.5 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Interactive Calendar Component -->
        <livewire:hrd.components.dashboard-calendar />

    @elseif($viewMode === 'table')
        @if($enableScreening)
            <!-- AI Smart Screening Panel -->
            <div class="mb-6 p-6 bg-white/80 backdrop-blur-xl border border-indigo-200 rounded-3xl shadow-xl shadow-indigo-100/30 relative overflow-hidden animate-fade-in">
                <!-- Glowing Top border -->
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-indigo-500 to-blue-600"></div>
                
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest border border-indigo-100">Smart Screening AI</span>
                    <h3 class="text-md font-extrabold text-slate-800 tracking-tight">Kriteria Penyaringan Otomatis</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Skill Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Keahlian (Skill)</label>
                        <input type="text" wire:model.live.debounce.300ms="filterSkill" placeholder="Contoh: Laravel, PHP, Excel..."
                            class="w-full px-4 py-2.5 text-xs border border-indigo-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                    </div>

                    <!-- Education Filter Dropdown (SMA, SMK, D3, S1, S2, S3) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Pendidikan Terakhir</label>
                        <select wire:model.live="filterEducation"
                            class="w-full px-4 py-2.5 text-xs border border-indigo-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm bg-white font-semibold text-slate-700">
                            <option value="">Semua Pendidikan</option>
                            <option value="SMA">SMA</option>
                            <option value="SMK">SMK</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>

                    <!-- Experience Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Min. Pengalaman (Tahun)</label>
                        <input type="number" wire:model.live.debounce.300ms="filterExperience" min="0" placeholder="Contoh: 1, 2, 5..."
                            class="w-full px-4 py-2.5 text-xs border border-indigo-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all shadow-sm">
                    </div>
                </div>
            </div>
        @endif

        <!-- Table View Container -->
        <div class="bg-white border border-red-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-red-50">
                    <thead class="bg-slate-50 border-b border-red-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 w-10"></th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider w-10">No</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Kandidat</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Posisi</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Rating</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Match Score</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal Masuk</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-50 bg-white">
                        @forelse($tableApplications as $app)
                            @php
                                $avgRating = $app->interviewScores->avg('rating');
                                $ratingsCount = count($app->interviewScores);
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors {{ in_array($app->id, $selectedApplications) ? 'bg-red-50/40' : '' }}">
                                <td class="px-6 py-4 w-10 whitespace-nowrap">
                                    <div class="flex items-center" wire:click.stop>
                                        <input type="checkbox" wire:model.live="selectedApplications" value="{{ $app->id }}" class="rounded border-slate-300 text-red-500 focus:ring-red-500 w-4 h-4 cursor-pointer shadow-sm">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-400">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center cursor-pointer group" @click="$dispatch('show-candidate-details', { applicationId: {{ $app->id }} })">
                                        <div>
                                            <div class="text-sm font-bold text-slate-900 group-hover:text-red-600 transition-colors">{{ $app->candidate->user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $app->candidate->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-800">{{ $app->job_title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($ratingsCount > 0)
                                        <div class="flex items-center space-x-1 text-xs text-amber-600 font-semibold bg-amber-50 py-1 px-2 rounded-md border border-amber-100 w-fit">
                                            <span>★ {{ number_format($avgRating, 1) }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum Dinilai</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-lg border
                                        {{ $app->status === 'Administrasi' ? 'bg-blue-50 text-blue-600 border-blue-200' : '' }}
                                        {{ $app->status === 'Psikotes' ? 'bg-red-50 text-red-600 border-red-200' : '' }}
                                        {{ $app->status === 'Interview' ? 'bg-amber-50 text-amber-600 border-amber-200' : '' }}
                                        {{ $app->status === 'MCU' ? 'bg-purple-50 text-purple-600 border-purple-200' : '' }}
                                        {{ $app->status === 'Hired' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : '' }}">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $score = $app->match_score ?? 0;
                                            $scoreColor = $score >= 70 ? 'bg-green-50 text-green-700 border-green-200' : ($score >= 40 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-red-50 text-red-700 border-red-200');
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold border shadow-sm {{ $scoreColor }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                                            {{ $score }}%
                                        </span>
                                    </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $app->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end items-center gap-2">
                                    <select wire:change="changeStatus({{ $app->id }}, $event.target.value)" class="text-xs font-medium border-red-200 rounded-lg py-1.5 pl-3 pr-8 bg-white text-slate-700 shadow-sm focus:ring-red-500">
                                        @foreach(['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired'] as $status)
                                            <option value="{{ $status }}" {{ $app->status === $status ? 'selected' : '' }}>Pindah: {{ $status }}</option>
                                        @endforeach
                                    </select>
                                    <button @click="$dispatch('show-candidate-details', { applicationId: {{ $app->id }} })" class="text-white bg-slate-800 hover:bg-slate-700 px-4 py-1.5 rounded-lg text-xs font-semibold shadow-sm transition-colors">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-slate-500 italic">
                                    <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Tidak ada data kandidat ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($hasMoreTable)
                <div class="bg-slate-50 px-6 py-4 border-t border-red-100 flex justify-center">
                    <button wire:click="loadMoreTable" class="px-6 py-2 border border-red-200 rounded-xl text-sm font-bold text-red-600 bg-white hover:bg-red-50 shadow-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        Muat Lebih Banyak Data
                    </button>
                </div>
            @endif
        </div>
    @    <!-- Candidate Detail Slide-out Drawer Component -->
    <livewire:hrd.components.candidate-detail-drawer />
    @endif

    <!-- Inline Mass Gmail Compose Modal -->
    @if($showGmailCompose)
        <div class="fixed inset-0 z-[9999] overflow-y-auto flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div wire:click="closeGmailCompose" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
            
            <!-- Modal Box -->
            <div class="bg-white/95 backdrop-blur-xl border border-red-100 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-red-500/10 max-w-lg w-full relative z-10 animate-scale-up">
                <!-- Top border accent -->
                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-red-500 to-red-600 rounded-t-3xl"></div>
                
                <div class="flex items-center justify-between border-b border-red-50 pb-4 mb-4">
                    <div>
                        <span class="px-2.5 py-0.5 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest border border-red-100">Mass Email</span>
                        <h3 class="text-lg font-black text-slate-800 mt-1">Kirim Gmail Massal</h3>
                    </div>
                    <button type="button" wire:click="closeGmailCompose" class="text-slate-400 hover:text-red-600 p-1.5 rounded-full hover:bg-red-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Recipients -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bcc Penerima (Otomatis)</label>
                        <textarea wire:model="gmailBcc" disabled rows="2" class="w-full text-xs p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 select-none font-semibold"></textarea>
                    </div>

                    <!-- Template Selector -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pilih Template Pesan</label>
                        <select wire:model.live="gmailTemplateType" class="w-full text-xs px-4 py-2.5 border border-red-100 rounded-xl bg-white font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-red-500 transition-all">
                            <option value="umum">Informasi Umum</option>
                            <option value="psikotes">Panggilan Psikotes</option>
                            <option value="interview">Panggilan Wawancara</option>
                            <option value="mcu">Panggilan MCU</option>
                        </select>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Subjek Email</label>
                        <input type="text" wire:model="gmailSubject" class="w-full text-xs px-4 py-2.5 border border-red-100 rounded-xl focus:ring-2 focus:ring-red-500 shadow-sm transition-all" placeholder="Undangan Tahap Seleksi...">
                    </div>

                    <!-- Body -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Isi Email</label>
                        <textarea wire:model="gmailBody" rows="5" class="w-full text-xs p-3 border border-red-100 rounded-xl focus:ring-2 focus:ring-red-500 shadow-sm transition-all" placeholder="Tulis isi pesan email Anda di sini..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 border-t border-slate-100 pt-4">
                    <button type="button" wire:click="closeGmailCompose" class="px-4 py-2 bg-slate-50 border border-slate-200 hover:bg-slate-100 text-slate-600 rounded-xl text-xs font-bold transition-all">
                        Batal
                    </button>
                    <button type="button" wire:click="sendGmailBulk" class="px-5 py-2 bg-gradient-to-r from-red-600 to-red-600 hover:from-red-700 hover:to-rose-700 text-white rounded-xl text-xs font-bold shadow-md shadow-red-500/10 transition-all">
                        Kirim Massal
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Red Glassmorphic Bulk Reject Modal -->
    @if($showBulkRejectModal)
        <div class="fixed inset-0 z-[9999] overflow-y-auto flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div wire:click="closeBulkRejectModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
            
            <!-- Modal Box -->
            <div class="relative w-full max-w-lg bg-red-950/85 backdrop-blur-2xl border border-red-500/30 p-6 rounded-3xl shadow-2xl overflow-hidden animate-scale-up text-white">
                <!-- Decorative top color bar -->
                <div class="absolute left-[1px] right-[1px] top-[1px] h-[3.5px] bg-gradient-to-r from-red-500 to-orange-500 rounded-t-[22px]"></div>
                <!-- Shimmer grid -->
                <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(239,68,68,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(239,68,68,0.03)_1px,transparent_1px)] bg-[size:16px_16px] pointer-events-none"></div>

                <!-- Modal Content -->
                <div class="relative z-10 flex flex-col gap-4">
                    <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                        <div class="w-10 h-10 rounded-xl bg-red-500/20 border border-red-500/30 flex items-center justify-center text-red-400 shadow-inner shrink-0">
                            <svg class="h-5 w-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-red-400 uppercase tracking-widest bg-red-500/10 border border-red-500/20 px-2 py-0.5 rounded-full shadow-sm">
                                Tindakan Massal
                            </span>
                            <h3 class="text-lg font-black tracking-tight mt-0.5">Tolak Kandidat Massal ({{ count($selectedApplications) }} Orang)</h3>
                        </div>
                    </div>

                    <p class="text-xs text-red-200/90 leading-relaxed font-medium bg-red-500/10 border border-red-500/20 p-3 rounded-xl">
                        Mohon berikan alasan penolakan yang sopan dan profesional. Alasan ini akan langsung tampil di portal karir kandidat masing-masing.
                    </p>

                    <!-- Presets -->
                    <div class="bg-white/5 border border-white/10 p-3.5 rounded-2xl space-y-2">
                        <span class="text-[9px] font-black text-red-400 uppercase tracking-widest block">Pilih Template Alasan Cepat:</span>
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" wire:click="$set('bulkRejectReason', 'Mohon maaf, profil dan kualifikasi administrasi Anda belum sesuai dengan kriteria yang kami butuhkan saat ini. Tetap semangat dan terus kembangkan potensi Anda!')" 
                                class="px-2.5 py-1 bg-white/5 hover:bg-white/10 border border-white/10 text-white rounded-lg text-[9px] font-bold transition-all">
                                Administrasi
                            </button>
                            <button type="button" wire:click="$set('bulkRejectReason', 'Terima kasih atas waktu Anda. Berdasarkan hasil evaluasi Psikotes, kami mohon maaf belum dapat meloloskan Anda ke tahapan rekrutmen berikutnya. Tetap semangat!')" 
                                class="px-2.5 py-1 bg-white/5 hover:bg-white/10 border border-white/10 text-white rounded-lg text-[9px] font-bold transition-all">
                                Psikotes
                            </button>
                            <button type="button" wire:click="$set('bulkRejectReason', 'Terima kasih telah meluangkan waktu mengikuti sesi wawancara. Sayangnya, saat ini kami memutuskan untuk melangkah bersama kandidat lain yang lebih sesuai.')" 
                                class="px-2.5 py-1 bg-white/5 hover:bg-white/10 border border-white/10 text-white rounded-lg text-[9px] font-bold transition-all">
                                Wawancara
                            </button>
                            <button type="button" wire:click="$set('bulkRejectReason', 'Terima kasih atas partisipasi Anda. Berdasarkan hasil evaluasi MCU / Kesehatan, dengan sangat menyesal kami belum dapat memproses berkas Anda ke tahap penawaran.')" 
                                class="px-2.5 py-1 bg-white/5 hover:bg-white/10 border border-white/10 text-white rounded-lg text-[9px] font-bold transition-all">
                                MCU
                            </button>
                        </div>
                    </div>

                    <!-- Textarea -->
                    <div>
                        <textarea wire:model="bulkRejectReason" rows="4" 
                            class="w-full text-xs p-3.5 bg-slate-950/60 border border-red-500/20 rounded-2xl text-white placeholder-slate-400 focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all shadow-inner" 
                            placeholder="Ketik alasan penolakan..."></textarea>
                        @error('bulkRejectReason')<span class="text-xs text-red-400 font-bold mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div class="flex justify-end gap-3 border-t border-white/10 pt-4 mt-2">
                        <button type="button" wire:click="closeBulkRejectModal" class="px-4 py-2 bg-white/5 border border-white/10 hover:bg-white/10 text-white rounded-xl text-xs font-bold transition-all">
                            Batalkan
                        </button>
                        <button type="button" wire:click="confirmBulkReject" class="px-5 py-2 bg-gradient-to-r from-red-650 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white rounded-xl text-xs font-black shadow-md shadow-red-500/20 transition-all">
                            Konfirmasi Tolak Massal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Siaran Massal WhatsApp Modal -->
    @if($showBulkModal)
        <div class="fixed inset-0 z-[9999] overflow-y-auto flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div wire:click="closeBulkModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
            
            <!-- Modal Box -->
            <div class="bg-white/95 backdrop-blur-xl border border-red-100 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-red-500/10 max-w-lg w-full relative z-10 animate-scale-up text-slate-800">
                <!-- Top border accent -->
                <div class="absolute left-[1px] right-[1px] top-[1px] h-[3.5px] bg-gradient-to-r from-emerald-500 to-green-600 rounded-t-[22px]"></div>
                
                <div class="flex items-center justify-between border-b border-red-50 pb-4 mb-4">
                    <div>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest border border-emerald-100">WhatsApp Massal</span>
                        <h3 class="text-lg font-black text-slate-800 mt-1">Kirim WhatsApp Massal</h3>
                    </div>
                    <button type="button" wire:click="closeBulkModal" class="text-slate-400 hover:text-red-650 p-1.5 rounded-full hover:bg-red-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Recipients -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">No. Telepon / Penerima (Otomatis)</label>
                        <textarea disabled rows="2" class="w-full text-xs p-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 select-none font-semibold">@php
                            $phones = \App\Models\Application::whereIn('id', $selectedApplications)->with('candidate')->get()->map(fn($app) => $app->candidate->phone)->toArray();
                            echo implode(', ', $phones);
                        @endphp</textarea>
                    </div>

                    <!-- Template Selector -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Pilih Template Pesan</label>
                        <select wire:model.live="bulkTemplateType" class="w-full text-xs px-4 py-2.5 border border-red-100 rounded-xl bg-white font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-red-500 transition-all font-semibold">
                            @foreach($templates as $key => $tpl)
                                <option value="{{ $key }}">{{ $tpl['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Message Body -->
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Isi Pesan WhatsApp</label>
                        <textarea wire:model="bulkMessageText" rows="5" class="w-full text-xs p-3 border border-red-100 rounded-xl focus:ring-2 focus:ring-red-500 shadow-sm transition-all" placeholder="Tulis isi pesan siaran..."></textarea>
                    </div>

                    <!-- Recipient WhatsApp Dispatch Section -->
                    <div class="space-y-2 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-wider block">Kirim WhatsApp Satu Per Satu</span>
                        
                        <div class="space-y-2 max-h-[160px] overflow-y-auto pr-1">
                            @foreach(\App\Models\Application::whereIn('id', $selectedApplications)->with('candidate.user')->get() as $app)
                                @php
                                    $isSent = in_array($app->id, $sentWhatsappIds);
                                @endphp
                                <div class="bg-white border border-slate-200 p-2.5 rounded-xl flex items-center justify-between shadow-sm transition-all hover:border-emerald-300">
                                    <div class="truncate max-w-[200px]">
                                        <span class="text-xs font-bold text-slate-800 block truncate">{{ $app->candidate->user->name }}</span>
                                        <span class="text-[10px] text-slate-500 block truncate">{{ $app->candidate->phone }} ({{ $app->job_title }})</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        @if($isSent)
                                            <span class="text-emerald-600 font-extrabold text-[10px] bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full flex items-center gap-1 shrink-0 select-none">
                                                ✓ Sent
                                            </span>
                                        @endif
                                        
                                        <a href="{{ $this->getWhatsappUrl($app, $this->bulkMessageText) }}" target="_blank"
                                            wire:click="markWhatsappAsSent({{ $app->id }})"
                                            class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-[10px] font-extrabold shadow-sm transition-all hover:-translate-y-0.5 flex items-center gap-1 shrink-0">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.999 0C5.373 0 0 5.373 0 12c0 2.12.554 4.107 1.523 5.832L.053 23.404a.75.75 0 00.918.918l5.572-1.47A11.943 11.943 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 11.999 0zm.001 21.75a9.714 9.714 0 01-4.963-1.362l-.357-.212-3.705.977.977-3.591-.232-.369A9.718 9.718 0 012.25 12C2.25 6.615 6.614 2.25 12 2.25S21.75 6.615 21.75 12 17.386 21.75 12 21.75z"/></svg>
                                            Kirim WA
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-4 border-t border-slate-100 pt-4">
                    <button type="button" wire:click="closeBulkModal" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(count($selectedApplications) > 0)
        <!-- Dynamic mobile-friendly spacer so bottom elements are never blocked by the floating actions bar -->
        <div class="h-24 sm:h-28 w-full pointer-events-none block shrink-0"></div>
    @endif
</div>

