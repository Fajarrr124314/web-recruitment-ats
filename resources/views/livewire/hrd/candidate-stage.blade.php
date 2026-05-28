@php
    $theme = match($stage) {
        'Administrasi' => [
            'color' => 'blue',
            'gradient' => 'from-blue-500 to-indigo-600 shadow-blue-500/10',
            'badge' => 'bg-blue-50 text-blue-600 border-blue-200',
            'ring' => 'focus:ring-blue-500',
            'text' => 'text-blue-600',
            'bgLight' => 'bg-blue-50/50',
            'border' => 'border-blue-100',
            'iconBg' => 'bg-blue-100',
        ],
        'Psikotes' => [
            'color' => 'red',
            'gradient' => 'from-red-500 to-rose-600 shadow-red-500/10',
            'badge' => 'bg-red-50 text-red-600 border-red-200',
            'ring' => 'focus:ring-red-500',
            'text' => 'text-red-600',
            'bgLight' => 'bg-red-50/50',
            'border' => 'border-red-100',
            'iconBg' => 'bg-red-100',
        ],
        'Interview' => [
            'color' => 'amber',
            'gradient' => 'from-amber-500 to-orange-600 shadow-amber-500/10',
            'badge' => 'bg-amber-50 text-amber-600 border-amber-200',
            'ring' => 'focus:ring-amber-500',
            'text' => 'text-amber-600',
            'bgLight' => 'bg-amber-50/50',
            'border' => 'border-amber-100',
            'iconBg' => 'bg-amber-100',
        ],
        'MCU' => [
            'color' => 'purple',
            'gradient' => 'from-purple-500 to-indigo-600 shadow-purple-500/10',
            'badge' => 'bg-purple-50 text-purple-600 border-purple-200',
            'ring' => 'focus:ring-purple-500',
            'text' => 'text-purple-600',
            'bgLight' => 'bg-purple-50/50',
            'border' => 'border-purple-100',
            'iconBg' => 'bg-purple-100',
        ],
        default => [
            'color' => 'red',
            'gradient' => 'from-red-500 to-rose-600 shadow-red-500/10',
            'badge' => 'bg-red-50 text-red-600 border-red-200',
            'ring' => 'focus:ring-red-500',
            'text' => 'text-red-600',
            'bgLight' => 'bg-red-50/50',
            'border' => 'border-red-100',
            'iconBg' => 'bg-red-100',
        ]
    };
    
    // Dynamic Dimension Labels
    $dimensions = \App\Support\StageRubric::getDimensions($stage);
@endphp

<div class="max-w-7xl mx-auto py-4 sm:py-8">
    <!-- Header & Breadcrumbs -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8 gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">
                <a href="{{ route('hrd.overview') }}" class="hover:text-red-500">Dashboard</a>
                <span>/</span>
                <span class="{{ $theme['text'] }}">Tahap {{ $stage }}</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2.5">
                Proses Kandidat: <span class="bg-clip-text text-transparent bg-gradient-to-r {{ $theme['gradient'] }}">{{ $stage }}</span>
            </h1>
            <p class="text-slate-500 text-sm mt-1">Lakukan penyaringan lanjutan, AI Smart Screening, serta beri penilaian objektif per kandidat.</p>
        </div>

        <!-- Smart Screening & AI Engine Controller (Premium AI Button) -->
        <button wire:click="$toggle('enableScreening')" type="button" 
            class="relative overflow-hidden inline-flex items-center gap-3 px-5 py-3 rounded-2xl text-xs font-black tracking-wider uppercase transition-all duration-300 shadow-lg group shrink-0
            {{ $enableScreening 
                ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white border-transparent shadow-[0_0_20px_rgba(79,70,229,0.5)] ring-2 ring-indigo-300 ring-offset-2 hover:shadow-[0_0_30px_rgba(79,70,229,0.7)] hover:scale-105' 
                : 'bg-white border-2 border-slate-200 text-slate-650 hover:border-indigo-400 hover:text-indigo-600 hover:bg-indigo-50/30' }}">
            
            <!-- Animated neon glowing backdrop when active -->
            @if($enableScreening)
                <span class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 via-purple-600 to-pink-500 rounded-2xl blur opacity-45 group-hover:opacity-75 transition duration-1000 group-hover:duration-200 animate-pulse"></span>
            @endif
            
            <div class="relative flex items-center gap-2.5 z-10">
                <!-- AI Magic Sparkles icon -->
                <svg class="w-4 h-4 {{ $enableScreening ? 'animate-spin text-white' : 'text-indigo-500' }}" style="animation-duration: 4s;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l-.813-5.096L3 15l5.096-.813L9 9l.813 5.096L15 15l-5.096.813zM19.071 4.929l-.353 2.213-2.214.353 2.214.354.353 2.213.354-2.213 2.213-.354-2.213-.353-.354-2.213z" />
                </svg>
                <div class="text-left">
                    <span class="text-[8px] font-bold block {{ $enableScreening ? 'text-indigo-200' : 'text-slate-400' }} tracking-widest leading-none">Smart Matching</span>
                    <span class="text-xs font-black block leading-tight">AI Smart Screening: {{ $enableScreening ? 'AKTIF' : 'NONAKTIF' }}</span>
                </div>
            </div>
        </button>
    </div>

    <!-- Active Success Toast Notification -->
    @if (session()->has('stage_success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center space-x-2 shadow-sm animate-fade-in-down">
            <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('stage_success') }}</span>
        </div>
    @endif

    <!-- Filtering Panel (Collapsible Smart Screening UI) -->
    <div class="bg-white/80 backdrop-blur-xl border border-slate-200/60 p-5 rounded-2xl shadow-sm mb-6 space-y-4">
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="flex flex-wrap gap-3 items-center w-full sm:w-auto">
                <!-- Search -->
                <div class="relative w-full sm:w-64">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email..." 
                        class="w-full pl-9 pr-3 py-2 text-xs border border-red-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white/50 transition-all shadow-inner">
                    <svg class="h-4 w-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Position filter -->
                <select wire:model.live="jobTitleFilter" 
                    class="py-2 pl-3 pr-8 text-xs border border-red-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white text-slate-700 shadow-sm">
                    <option value="">Semua Posisi</option>
                    @foreach(App\Models\JobPosition::orderBy('title')->pluck('title') as $title)
                        <option value="{{ $title }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 tracking-wide shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                <span>Urutkan:</span>
                <button wire:click="setSortBy('latest')" class="px-2.5 py-1 rounded-lg {{ $sortBy === 'latest' ? 'bg-red-50 text-red-600 font-bold border border-red-100' : 'hover:bg-slate-50 text-slate-500' }}">Terbaru</button>
                <button wire:click="setSortBy('rating_desc')" class="px-2.5 py-1 rounded-lg {{ $sortBy === 'rating_desc' ? 'bg-red-50 text-red-600 font-bold border border-red-100' : 'hover:bg-slate-50 text-slate-500' }}">Rating Tertinggi</button>
            </div>
        </div>

        @if($enableScreening)
            <!-- Active Smart Screening Panel -->
            <div class="p-4 bg-gradient-to-tr from-slate-50 to-slate-100/50 border border-slate-200 rounded-xl grid grid-cols-1 sm:grid-cols-3 gap-4 animate-fade-in">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">1. Filter Keahlian (Skills)</label>
                    <input type="text" wire:model.live.debounce.400ms="filterSkill" placeholder="Contoh: Laravel, Figma, Excel..."
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 bg-white">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">2. Filter Pendidikan</label>
                    <input type="text" wire:model.live.debounce.400ms="filterEducation" placeholder="Contoh: S1 Informatika, SMK..."
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 bg-white">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">3. Min. Pengalaman Kerja</label>
                    <select wire:model.live="filterExperience" 
                        class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 bg-white text-slate-700">
                        <option value="">Tampilkan Semua</option>
                        <option value="1">Minimal 1 Tahun</option>
                        <option value="2">Minimal 2 Tahun</option>
                        <option value="3">Minimal 3 Tahun</option>
                        <option value="5">Minimal 5 Tahun</option>
                    </select>
                </div>
            </div>
        @endif
    </div>

    <!-- Candidate List Table Card -->
    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/75 border-b border-slate-150">
                    <tr>
                        <th class="px-6 py-4 w-10 text-center">
                            <input type="checkbox" wire:click="toggleSelectAll" class="rounded border-slate-300 text-red-500 focus:ring-red-500 w-4 h-4 cursor-pointer">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-12">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kandidat</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Posisi Lowongan</th>
                        @if($enableScreening)
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-36">AI Match Score</th>
                        @endif
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Rating Rata-rata</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Submit</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($applications as $app)
                        @php
                            $avgRating = $app->interviewScores->avg('rating');
                            $ratingsCount = count($app->interviewScores);
                            $isSelected = in_array($app->id, $selectedApplications);
                            $matchScore = $enableScreening ? ($app->match_score ?? $this->calculateMatchScore($app)) : 100;
                            
                            $scorePill = match(true) {
                                $matchScore >= 80 => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                $matchScore >= 50 => 'bg-amber-50 text-amber-700 border-amber-200',
                                default => 'bg-red-50 text-red-700 border-red-200'
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors {{ $isSelected ? 'bg-red-50/10' : '' }}">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" wire:model.live="selectedApplications" value="{{ $app->id }}" class="rounded border-slate-300 text-red-500 focus:ring-red-500 w-4 h-4 cursor-pointer">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-slate-400">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center cursor-pointer group" wire:click="selectApplication({{ $app->id }})">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs shrink-0 border border-slate-200">
                                        {{ substr($app->candidate->user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-bold text-slate-900 group-hover:text-red-600 transition-colors flex items-center gap-1.5">
                                            <span>{{ $app->candidate->user->name }}</span>
                                        </div>
                                        <div class="text-xs text-slate-400 font-medium">{{ $app->candidate->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-slate-700 font-semibold">{{ $app->job_title }}</span>
                            </td>
                            @if($enableScreening)
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $scorePill }}">
                                        {{ $matchScore }}% Match
                                    </span>
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ratingsCount > 0)
                                    <div class="flex items-center space-x-1 text-xs text-amber-600 font-extrabold bg-amber-50 py-1 px-2 rounded-md border border-amber-100 w-fit">
                                        <span>★ {{ number_format($avgRating, 1) }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic font-medium">Belum Dinilai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 font-bold">
                                {{ $app->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-bold flex justify-end items-center gap-2">
                                <select wire:change="changeStatus({{ $app->id }}, $event.target.value)" class="text-[11px] font-extrabold border-slate-200 rounded-lg py-1.5 pl-2.5 pr-7 bg-white text-slate-700 shadow-sm focus:outline-none">
                                    <option value="">Pindah Tahap</option>
                                    @foreach(['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired'] as $status)
                                        <option value="{{ $status }}" {{ $app->status === $status ? 'selected' : '' }}>Pindah: {{ $status }}</option>
                                    @endforeach
                                </select>
                                <button wire:click="selectApplication({{ $app->id }})" class="text-white bg-slate-800 hover:bg-slate-700 px-4 py-1.5 rounded-lg font-bold shadow-sm transition-colors">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $enableScreening ? 8 : 7 }}" class="px-6 py-16 text-center text-slate-400 italic">
                                <svg class="mx-auto h-12 w-12 text-slate-350 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Tidak ada data kandidat ditemukan untuk tahap {{ $stage }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($hasMore)
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-center">
                <button wire:click="loadMore" class="px-6 py-2.5 border border-slate-200 rounded-xl text-xs font-extrabold text-slate-600 bg-white hover:bg-slate-50 shadow-sm transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                    Muat Lebih Banyak Kandidat
                </button>
            </div>
        @endif
    </div>

    <!-- Candidate Detail Slide-out Drawer Component -->
    <livewire:hrd.components.candidate-detail-drawer />

    <!-- Floating Bulk Actions Bar (Absolute Bottom Center) -->
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
            
            <button wire:click="clearSelection" class="text-slate-450 hover:text-white p-1 sm:p-1.5 rounded-xl hover:bg-slate-800 transition-colors shrink-0" title="Batal Pilih Semua">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
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
                        <select wire:model.live="gmailTemplateType" class="w-full text-xs px-4 py-2.5 border border-red-100 rounded-xl bg-white font-bold text-slate-700 shadow-sm focus:ring-2 focus:ring-red-500 transition-all font-semibold">
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
                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-red-500 to-orange-500"></div>
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
                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-emerald-500 to-green-600 rounded-t-3xl"></div>
                
                <div class="flex items-center justify-between border-b border-red-50 pb-4 mb-4">
                    <div>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest border border-emerald-100">WhatsApp Massal</span>
                        <h3 class="text-lg font-black text-slate-800 mt-1">Kirim WhatsApp Massal</h3>
                    </div>
                    <button type="button" wire:click="closeBulkModal" class="text-slate-400 hover:text-red-655 p-1.5 rounded-full hover:bg-red-50 transition-colors">
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