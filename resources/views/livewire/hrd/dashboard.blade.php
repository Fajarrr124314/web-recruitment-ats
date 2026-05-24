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
            <select wire:model.live="jobTitleFilter" class="py-2 pl-3 pr-8 text-sm border border-red-200 rounded-xl focus:ring-red-500 shadow-sm text-slate-600 bg-white">
                <option value="">Semua Posisi</option>
                @foreach($availableJobTitles as $title)
                    <option value="{{ $title }}">{{ $title }}</option>
                @endforeach
            </select>

            <!-- View Toggle -->
            <div class="flex bg-slate-100 rounded-xl p-1 border border-slate-200">
                <button wire:click="setViewMode('kanban')" class="p-1.5 rounded-lg transition-colors {{ $viewMode === 'kanban' ? 'bg-white shadow-sm text-red-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}" title="Kanban View">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                    </svg>
                </button>
                <button wire:click="setViewMode('table')" class="p-1.5 rounded-lg transition-colors {{ $viewMode === 'table' ? 'bg-white shadow-sm text-red-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}" title="Table View">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </button>
            </div>
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
        <div class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 bg-slate-800/95 backdrop-blur-sm text-white px-6 py-3 rounded-2xl shadow-2xl flex items-center gap-4 border border-slate-700">
            <span class="font-bold text-sm bg-slate-700 px-3 py-1 rounded-full text-red-100 flex items-center gap-1">
                <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                {{ count($selectedApplications) }} Terpilih
            </span>
            <div class="h-6 w-px bg-slate-600"></div>
            <select wire:change="bulkChangeStatus($event.target.value)" class="bg-slate-700 text-white border border-slate-600 rounded-lg text-sm py-1.5 focus:ring-red-500 cursor-pointer shadow-inner">
                <option value="">-- Pindah Serentak Ke --</option>
                <option value="Administrasi">Administrasi</option>
                <option value="Psikotes">Psikotes</option>
                <option value="Interview">Interview</option>
                <option value="MCU">MCU</option>
                <option value="Hired">Hired</option>
                <option value="Ditolak">Tolak / Gagal</option>
            </select>
            <button wire:click="clearSelection" class="text-slate-400 hover:text-white p-1 rounded-full transition-colors ml-1" title="Batal Pilih Semua">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    @endif

    @if($viewMode === 'kanban')
        <!-- Mobile View Tabs Navigation -->
        <div class="block md:hidden mb-6">
            <nav class="flex space-x-2 border-b border-red-100 pb-px overflow-x-auto" aria-label="Tabs">
                @foreach(['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired'] as $stage)
                    @php
                        $count = count($groupedApplications[$stage]);
                        $isActive = $activeMobileTab === $stage;
                    @endphp
                    <button wire:click="$set('activeMobileTab', '{{ $stage }}')"
                        class="flex-shrink-0 flex-1 pb-3 px-2 text-[10px] sm:text-xs font-bold tracking-wider uppercase text-center border-b-2 transition-all {{ $isActive ? 'border-red-500 text-red-500 font-extrabold' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                        {{ $stage }} ({{ $count }})
                    </button>
                @endforeach
            </nav>
        </div>

        <!-- Kanban Board Container (Horizontal Scroll Support) -->
        <div class="flex overflow-x-auto pb-6 snap-x gap-6" style="-webkit-overflow-scrolling: touch;">
            @foreach(['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired'] as $stage)
                @php
                    $headerGradient = match($stage) {
                        'Administrasi' => 'from-blue-500 to-indigo-500',
                        'Psikotes' => 'from-rose-500 to-red-500',
                        'Interview' => 'from-amber-500 to-orange-500',
                        'MCU' => 'from-purple-500 to-pink-500',
                        'Hired' => 'from-emerald-500 to-teal-500',
                    };
                    $badgeBg = match($stage) {
                        'Administrasi' => 'bg-blue-50 text-blue-600 border-blue-200',
                        'Psikotes' => 'bg-rose-50 text-rose-600 border-rose-200',
                        'Interview' => 'bg-amber-50 text-amber-600 border-amber-200',
                        'MCU' => 'bg-purple-50 text-purple-600 border-purple-200',
                        'Hired' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                    };
                    $cards = $groupedApplications[$stage];
                    $isMobileVisible = $activeMobileTab === $stage;
                @endphp

                <!-- Column (Min Width prevents squishing on small screens) -->
                <div class="{{ $isMobileVisible ? 'block' : 'hidden' }} md:block space-y-4 w-full md:w-72 snap-start shrink-0">
                    <!-- Column Header -->
                    <div class="bg-white/80 backdrop-blur-md border border-slate-200 rounded-xl p-3 flex items-center justify-between shadow-sm relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b {{ $headerGradient }}"></div>
                        <div class="flex items-center space-x-2 pl-2">
                            <span class="font-bold text-slate-800 tracking-wide text-sm">{{ $stage }}</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $badgeBg }} border">
                                {{ count($cards) }}
                            </span>
                        </div>
                    </div>

                    <!-- Cards List Drop Zone -->
                    <div class="space-y-2 bg-slate-50/50 border border-slate-200 rounded-2xl p-2 min-h-[300px] transition-all relative"
                        x-data="{ isDropping: false }"
                        x-on:dragover.prevent="isDropping = true"
                        x-on:dragleave.prevent="isDropping = false"
                        x-on:drop="
                            isDropping = false;
                            let id = event.dataTransfer.getData('text/plain');
                            if (id) {
                                @this.changeStatus(id, '{{ $stage }}');
                            }
                        "
                        :class="{ 'ring-2 ring-red-400 bg-red-50 shadow-inner': isDropping }"
                    >
                        <!-- Drag Placeholder Message -->
                        <div x-show="isDropping" class="absolute inset-0 flex items-center justify-center z-10 bg-red-50/80 rounded-2xl backdrop-blur-sm" style="display: none;">
                            <span class="text-sm font-bold text-red-600 flex items-center gap-2">
                                <svg class="w-5 h-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                                Lepas Disini
                            </span>
                        </div>

                        @forelse($cards as $index => $app)
                            @php
                                $avgRating = $app->interviewScores->avg('rating');
                                $ratingsCount = count($app->interviewScores);
                            @endphp
                            
                            <!-- Sleek List Card (Draggable) -->
                            <div class="bg-white border hover:border-red-300 rounded-lg p-3 flex items-center justify-between cursor-move shadow-sm hover:shadow relative group transition-all {{ in_array($app->id, $selectedApplications) ? 'border-red-400 ring-1 ring-red-400 bg-red-50/20' : 'border-slate-200' }}"
                                draggable="true"
                                x-on:dragstart="
                                    event.dataTransfer.setData('text/plain', '{{ $app->id }}');
                                    event.dataTransfer.effectAllowed = 'move';
                                    event.target.classList.add('opacity-50', 'scale-95');
                                "
                                x-on:dragend="
                                    event.target.classList.remove('opacity-50', 'scale-95');
                                "
                                title="Klik & Geser (Drag) untuk memindahkan"
                            >
                                <!-- Number Index -->
                                <div class="text-[10px] font-bold text-slate-400 bg-slate-50 w-5 h-5 flex items-center justify-center rounded-full mr-2 shrink-0 border border-slate-100">
                                    {{ $loop->iteration }}
                                </div>

                                <!-- Drag Handle Icon (Visual Cue) -->
                                <div class="text-slate-300 mr-2 group-hover:text-red-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" /></svg>
                                </div>

                                <!-- Bulk Checkbox -->
                                <div class="mr-3 flex items-center shrink-0" wire:click.stop>
                                    <input type="checkbox" wire:model.live="selectedApplications" value="{{ $app->id }}" class="rounded border-slate-300 text-red-500 focus:ring-red-500 w-4 h-4 cursor-pointer transition-colors shadow-sm">
                                </div>

                                <!-- Card Content (Click to open details) -->
                                <div wire:click="selectApplication({{ $app->id }})" class="flex-1 cursor-pointer overflow-hidden">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-bold text-slate-800 text-sm truncate group-hover:text-red-600 transition-colors">{{ $app->candidate->user->name }}</h4>
                                        <!-- Rating Inline -->
                                        @if($ratingsCount > 0)
                                            <span class="text-[10px] font-bold text-amber-500 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100 flex items-center shrink-0">
                                                ★ {{ number_format($avgRating, 1) }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ $app->job_title }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-6 px-4 text-center border border-transparent border-dashed rounded-xl h-full">
                                <span class="text-xs text-slate-400 italic">Kosong</span>
                            </div>
                        @endforelse
                        
                        <!-- Load More Kanban -->
                        @if($hasMore[$stage])
                            <button wire:click="loadMore('{{ $stage }}')" class="w-full py-2 mt-2 text-[11px] text-red-600 font-bold bg-white hover:bg-red-50 rounded-lg border border-red-100 shadow-sm transition-colors flex items-center justify-center gap-1 relative z-20">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                Muat Lebih Banyak
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @elseif($viewMode === 'table')
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
                                    <div class="flex items-center cursor-pointer group" wire:click="selectApplication({{ $app->id }})">
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
                                        {{ $app->status === 'Psikotes' ? 'bg-rose-50 text-rose-600 border-rose-200' : '' }}
                                        {{ $app->status === 'Interview' ? 'bg-amber-50 text-amber-600 border-amber-200' : '' }}
                                        {{ $app->status === 'MCU' ? 'bg-purple-50 text-purple-600 border-purple-200' : '' }}
                                        {{ $app->status === 'Hired' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : '' }}">
                                        {{ $app->status }}
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
                                    <button wire:click="selectApplication({{ $app->id }})" class="text-white bg-slate-800 hover:bg-slate-700 px-4 py-1.5 rounded-lg text-xs font-semibold shadow-sm transition-colors">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-500 italic">
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
    @endif

    <!-- Candidate Detail Slide-out Drawer / Modal -->
    @if($selectedApplication)
        <div class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <!-- Backdrop overlay -->
                <div wire:click="closeDetails" class="absolute inset-0 bg-slate-800/40 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

                <!-- Sliding Container -->
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div class="pointer-events-auto w-screen max-w-2xl transform transition-all duration-300 ease-in-out sm:duration-500">
                        <div class="flex h-full flex-col overflow-y-scroll bg-white border-l border-red-100 shadow-2xl relative">
                            
                            <!-- Drawer Top Gradient Border Line -->
                            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-red-500 to-rose-600"></div>

                            <!-- Header -->
                            <div class="px-6 py-5 border-b border-red-100 flex items-center justify-between bg-white/80 backdrop-blur-md sticky top-0 z-10">
                                <div>
                                    <h2 class="text-xl font-bold text-slate-800" id="slide-over-title">
                                        Detail Pelamar: <span class="bg-clip-text text-transparent bg-gradient-to-r from-red-500 to-rose-500">{{ $selectedApplication->candidate->user->name }}</span>
                                    </h2>
                                    <p class="text-xs text-slate-500 mt-0.5">Lamaran masuk pada {{ $selectedApplication->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <button type="button" wire:click="closeDetails"
                                    class="rounded-lg p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors focus:outline-none">
                                    <span class="sr-only">Close panel</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Content Body -->
                            <div class="flex-1 p-6 space-y-6">
                                <!-- Status Changer & Actions -->
                                <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col gap-4">
                                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                        <!-- Stage status control -->
                                        <div class="flex-1">
                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Ubah Tahap Rekrutmen</label>
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach(['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired'] as $targetStage)
                                                    @php
                                                        $isCurrent = $selectedApplication->status === $targetStage;
                                                        $btnTheme = match($targetStage) {
                                                            'Administrasi' => $isCurrent ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 border-slate-200 hover:border-blue-300',
                                                            'Psikotes' => $isCurrent ? 'bg-rose-600 text-white' : 'bg-white text-rose-600 border-slate-200 hover:border-rose-300',
                                                            'Interview' => $isCurrent ? 'bg-amber-600 text-white' : 'bg-white text-amber-600 border-slate-200 hover:border-amber-300',
                                                            'MCU' => $isCurrent ? 'bg-purple-600 text-white' : 'bg-white text-purple-600 border-slate-200 hover:border-purple-300',
                                                            'Hired' => $isCurrent ? 'bg-emerald-600 text-white' : 'bg-white text-emerald-600 border-slate-200 hover:border-emerald-300',
                                                        };
                                                    @endphp
                                                    <button wire:click="changeStatus({{ $selectedApplication->id }}, '{{ $targetStage }}')"
                                                        class="px-2.5 py-1 rounded text-xs font-semibold transition-all border shadow-sm hover:shadow {{ $btnTheme }}">
                                                        {{ $targetStage }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- WhatsApp Auto -->
                                        <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                                            <a href="{{ $this->getWhatsappUrl($selectedApplication) }}" target="_blank"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold shadow-sm transition-transform hover:-translate-y-0.5 w-full sm:w-auto">
                                                <svg class="h-4 w-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.517 2.266 2.27 3.507 5.289 3.507 8.495 0 6.6-5.334 11.938-11.948 11.938-2.008-.002-3.98-.51-5.772-1.472L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.97C16.579 2.008 14.12 1.01 11.56 1.01c-5.436 0-9.861 4.372-9.865 9.8.001 1.83.501 3.61 1.447 5.17l-1.018 3.715 3.823-.991zm11.303-6.843c-.3-.15-1.771-.875-2.04-.972-.27-.099-.467-.15-.662.15-.195.3-.757.971-.928 1.17-.172.195-.344.22-.643.07-.3-.15-1.269-.465-2.417-1.485-.89-.79-1.49-1.77-1.665-2.07-.173-.3-.022-.46.128-.608.133-.135.3-.349.45-.524.15-.175.2-.299.3-.499.1-.2.05-.375-.025-.524-.075-.15-.662-1.597-.907-2.192-.24-.575-.48-.497-.66-.505-.171-.007-.368-.008-.567-.008-.199 0-.523.075-.797.375-.272.3-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.771-.724 2.02-1.424.249-.699.249-1.299.175-1.424-.075-.125-.27-.199-.57-.349z" /></svg>
                                                Undang via WA
                                            </a>
                                        </div>
                                    </div>
                                    <div class="border-t border-slate-200 pt-3 flex items-center justify-between">
                                        <span class="text-[10px] text-slate-500 font-semibold">Opsi Lainnya:</span>
                                        <button wire:click="openRejectModal" class="px-3 py-1 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 rounded text-xs font-bold transition-colors">
                                            Tolak Kandidat (Gagal)
                                        </button>
                                    </div>
                                </div>

                                <!-- Reject Modal Overlay (Inside Drawer) -->
                                @if($showRejectModal)
                                    <div class="bg-rose-50 border border-rose-200 p-4 rounded-xl shadow-inner">
                                        <h3 class="text-rose-800 font-bold text-sm mb-2">Konfirmasi Penolakan Kandidat</h3>
                                        <p class="text-xs text-rose-600 mb-3">Mohon berikan alasan profesional yang konstruktif. Alasan ini akan ditampilkan kepada kandidat beserta info jeda waktu pendaftaran 1 bulan.</p>
                                        
                                        <!-- Quick Templates -->
                                        <div class="mb-3">
                                            <p class="text-[10px] font-bold text-rose-700 uppercase tracking-wider mb-2">Pilih Template Alasan Cepat:</p>
                                            <div class="flex flex-wrap gap-2">
                                                <button type="button" wire:click="$set('rejectReason', 'Mohon maaf, profil dan kualifikasi administrasi Anda belum sesuai dengan kriteria yang kami butuhkan saat ini. Tetap semangat dan terus kembangkan potensi Anda!')" 
                                                    class="px-2 py-1 bg-white border border-rose-300 text-rose-700 hover:bg-rose-100 rounded text-[10px] font-semibold transition-colors">
                                                    Gagal Administrasi
                                                </button>
                                                <button type="button" wire:click="$set('rejectReason', 'Terima kasih telah mengikuti sesi interview. Sayangnya, untuk saat ini kami memutuskan untuk melangkah dengan kandidat lain yang lebih sesuai dengan kebutuhan spesifik kami.')" 
                                                    class="px-2 py-1 bg-white border border-rose-300 text-rose-700 hover:bg-rose-100 rounded text-[10px] font-semibold transition-colors">
                                                    Gagal Interview
                                                </button>
                                                <button type="button" wire:click="$set('rejectReason', 'Terima kasih atas partisipasi Anda. Berdasarkan hasil evaluasi lanjutan dan MCU, dengan berat hati kami belum dapat melanjutkan proses rekrutmen Anda.')" 
                                                    class="px-2 py-1 bg-white border border-rose-300 text-rose-700 hover:bg-rose-100 rounded text-[10px] font-semibold transition-colors">
                                                    Gagal MCU
                                                </button>
                                                <button type="button" wire:click="$set('rejectReason', 'Terima kasih atas waktu Anda. Berdasarkan hasil Psikotes yang telah Anda ikuti, kami mohon maaf belum bisa meloloskan Anda ke tahap selanjutnya. Tetap semangat!')" 
                                                    class="px-2 py-1 bg-white border border-rose-300 text-rose-700 hover:bg-rose-100 rounded text-[10px] font-semibold transition-colors">
                                                    Gagal Psikotes
                                                </button>
                                            </div>
                                        </div>

                                        <textarea wire:model="rejectReason" rows="3" class="w-full text-sm p-3 border border-rose-200 rounded-lg focus:ring-rose-500 focus:border-rose-500 bg-white" placeholder="Atau ketik alasan Anda sendiri di sini..."></textarea>
                                        @error('rejectReason')<span class="text-xs text-rose-600 font-bold block mt-1">{{ $message }}</span>@enderror

                                        <div class="flex justify-end gap-2 mt-3">
                                            <button wire:click="closeRejectModal" class="px-4 py-1.5 bg-white border border-slate-300 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-50">Batal</button>
                                            <button wire:click="rejectCandidate" class="px-4 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-bold hover:bg-rose-700 shadow-md">Kirim Penolakan</button>
                                        </div>
                                    </div>
                                @endif

                                <!-- Candidate Profile Info Card -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white border border-slate-100 p-4 rounded-xl shadow-sm">
                                        <h3 class="text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 mb-2">Info Kontak & Posisi</h3>
                                        <p class="text-sm font-semibold text-slate-800">{{ $selectedApplication->job_title }}</p>
                                        <div class="mt-2 text-xs text-slate-600 space-y-1">
                                            <p><span class="text-slate-400">Email:</span> {{ $selectedApplication->candidate->user->email }}</p>
                                            <p><span class="text-slate-400">WhatsApp:</span> {{ $selectedApplication->candidate->phone }}</p>
                                        </div>
                                    </div>

                                    <div class="bg-white border border-slate-100 p-4 rounded-xl shadow-sm">
                                        <h3 class="text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 mb-2">Keahlian (Skill)</h3>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @forelse($selectedApplication->candidate->skills ?? [] as $skill)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-red-50 text-red-600 border border-red-100">
                                                    {{ $skill }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-slate-400 italic">Tidak ada skill khusus.</span>
                                            @endforelse
                                        </div>
                                    </div>
                                    
                                    <div class="md:col-span-2 bg-white border border-slate-100 p-4 rounded-xl shadow-sm">
                                        <h3 class="text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 mb-2">Pengalaman / Tentang</h3>
                                        <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-wrap break-words">{{ $selectedApplication->candidate->work_history }}</p>
                                    </div>
                                </div>
                                
                                <!-- Dynamic Form Answers -->
                                @if($selectedApplication->answers->count() > 0)
                                <div class="space-y-3">
                                    <h3 class="text-[10px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200 pb-2">Berkas & Form Dinamis</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($selectedApplication->answers as $ans)
                                            <div class="bg-slate-50 border border-slate-200 p-3 rounded-lg">
                                                <p class="text-[10px] font-bold text-slate-500 mb-1 line-clamp-1" title="{{ $ans->requirement->question }}">{{ $ans->requirement->question }}</p>
                                                @if($ans->requirement->type === 'file')
                                                    <a href="{{ Storage::url($ans->answer) }}" target="_blank" class="text-red-600 font-semibold text-xs hover:underline flex items-center gap-1 w-fit bg-red-50 px-2 py-1 rounded border border-red-100">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                                        Lihat File / Berkas
                                                    </a>
                                                @else
                                                    <p class="text-sm text-slate-800 break-words leading-tight">{{ $ans->answer }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- Collaborative Recruiter Scoring -->
                                <div class="space-y-4 pt-4 border-t border-slate-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Penilaian Internal ({{ count($selectedApplication->interviewScores) }})</h3>
                                    </div>

                                    @if(session()->has('rating_success'))
                                        <div class="p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-xs flex items-center space-x-2">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            <span>{{ session('rating_success') }}</span>
                                        </div>
                                    @endif

                                    <!-- Feed of scores -->
                                    <div class="space-y-3">
                                        @forelse($selectedApplication->interviewScores as $score)
                                            <div class="bg-white border border-slate-100 p-3 rounded-lg flex gap-3 shadow-sm">
                                                <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-xs shrink-0">
                                                    {{ substr($score->interviewer->name, 0, 1) }}
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs font-bold text-slate-700">{{ $score->interviewer->name }}</span>
                                                        <div class="flex items-center text-amber-500">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <svg class="h-3 w-3 {{ $score->rating >= $i ? 'fill-current' : 'text-slate-200' }}" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    @if($score->notes)
                                                        <p class="text-xs text-slate-600 mt-1 italic leading-tight">"{{ $score->notes }}"</p>
                                                    @endif
                                                    <span class="text-[9px] text-slate-400 mt-1 block">{{ $score->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-400 italic">Belum dinilai.</p>
                                        @endforelse
                                    </div>

                                    <!-- Score Input Form -->
                                    <form wire:submit.prevent="submitAssessment" class="bg-red-50/50 p-4 border border-red-100 rounded-xl space-y-3">
                                        <div class="flex justify-between items-center">
                                            <label class="block text-xs font-bold text-slate-700">Beri Penilaian</label>
                                            <div class="flex items-center space-x-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <button type="button" wire:click="$set('rating', {{ $i }})" class="focus:outline-none hover:scale-110 transition-transform">
                                                        <svg class="h-5 w-5 {{ $rating >= $i ? 'text-amber-500 fill-current' : 'text-slate-300' }}" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    </button>
                                                @endfor
                                            </div>
                                        </div>

                                        <div>
                                            <textarea wire:model="ratingNotes" rows="2"
                                                class="block w-full px-3 py-2 text-xs bg-white border border-red-100 rounded-lg focus:ring-1 focus:ring-red-400 focus:border-transparent transition-all shadow-sm"
                                                placeholder="Catatan evaluasi tambahan..."></textarea>
                                            @error('ratingNotes')<p class="mt-1 text-[10px] text-rose-500">{{ $message }}</p>@enderror
                                        </div>

                                        <div class="flex justify-end">
                                            <button type="submit" class="px-4 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-bold transition-all shadow-sm">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
