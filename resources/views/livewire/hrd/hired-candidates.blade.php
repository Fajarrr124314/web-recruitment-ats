<div class="max-w-7xl mx-auto py-4 sm:py-8 space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Karyawan Diterima</h1>
            <p class="text-slate-500 text-sm mt-1">Daftar seluruh kandidat yang telah resmi diterima sebagai karyawan.</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <!-- Search -->
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari pelamar..."
                    class="pl-9 pr-3 py-2 text-sm border border-red-200 rounded-xl focus:ring-red-500 focus:border-red-500 shadow-sm w-full sm:w-56 transition-all">
                <svg class="h-4 w-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <!-- Export Button -->
            <button wire:click="exportExcel"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-md transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Export Excel
            </button>
        </div>
    </div>

    <!-- Success Flash -->
    @if (session()->has('board_success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center space-x-2">
            <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
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
            
            <!-- Bulk Delete Action -->
            <button type="button" wire:click="triggerConfirm('bulkDelete', 'Hapus Massal Karyawan', 'Apakah Anda yakin ingin menghapus secara permanen {{ count($selectedApplications) }} data karyawan dari sistem?')"
                class="px-2.5 py-1 sm:px-3 sm:py-1.5 bg-gradient-to-r from-red-650 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white rounded-xl text-[10px] sm:text-xs font-bold transition-all shadow-md shadow-red-500/10 flex items-center gap-1 border border-red-500/30 shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                <span>Hapus <span class="hidden sm:inline">Permanen</span></span>
            </button>

            <button wire:click="clearSelection" class="text-slate-450 hover:text-white p-1 sm:p-1.5 rounded-xl hover:bg-slate-800 transition-colors shrink-0" title="Batal Pilih Semua">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-10">
                            <input type="checkbox" class="rounded border-slate-300 text-red-600 focus:ring-red-500"
                                wire:click="toggleSelectAll({{ json_encode($hiredApplications->pluck('id')->toArray()) }})"
                                @if(count($selectedApplications) && count(array_intersect($hiredApplications->pluck('id')->toArray(), $selectedApplications)) === $hiredApplications->count()) checked @endif>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kandidat</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Posisi</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Diterima</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Karyawan Diterima</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai Keseluruhan</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @forelse($hiredApplications as $app)
                        @php
                            $avgRating = $app->interviewScores->avg('rating');
                            $ratingsCount = $app->interviewScores->count();
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors {{ in_array($app->id, $selectedApplications) ? 'bg-red-50/40' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="rounded border-slate-300 text-red-600 focus:ring-red-500"
                                    wire:model.live="selectedApplications" value="{{ $app->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                        {{ mb_strtoupper(mb_substr($app->candidate->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800 cursor-pointer hover:text-emerald-600 transition-colors" wire:click="selectApplication({{ $app->id }})">
                                            {{ $app->candidate->user->name }}
                                        </div>
                                        <div class="text-xs text-slate-500">{{ $app->candidate->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $app->job_title }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-slate-600">{{ $app->updated_at ? $app->updated_at->format('d M Y') : '-' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                    Hired
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ratingsCount > 0)
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center space-x-1 text-xs text-amber-600 font-semibold bg-amber-50 py-1 px-2 rounded-md border border-amber-100 w-fit">
                                            <span>★ {{ number_format($avgRating, 1) }}</span>
                                        </div>
                                        <span class="text-[9px] font-bold text-slate-400">Nilai Keseluruhan</span>
                                    </div>
                                @else
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs text-slate-400 italic">Belum Dinilai</span>
                                        <span class="text-[9px] font-bold text-slate-300">Nilai Keseluruhan</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- WhatsApp -->
                                    <a href="{{ $this->getWhatsappUrl($app) }}" target="_blank"
                                        class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Hubungi via WhatsApp">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.999 0C5.373 0 0 5.373 0 12c0 2.12.554 4.107 1.523 5.832L.053 23.404a.75.75 0 00.918.918l5.572-1.47A11.943 11.943 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 11.999 0zm.001 21.75a9.714 9.714 0 01-4.963-1.362l-.357-.212-3.705.977.977-3.591-.232-.369A9.718 9.718 0 012.25 12C2.25 6.615 6.614 2.25 12 2.25S21.75 6.615 21.75 12 17.386 21.75 12 21.75z"/></svg>
                                    </a>
                                    <!-- Delete -->
                                    <button type="button" wire:click="triggerConfirm('deleteApplication', 'Hapus Karyawan', 'Hapus data karyawan {{ $app->candidate->user->name }} secara permanen?', {{ $app->id }})"
                                        class="p-1.5 text-red-500 hover:bg-red-550 rounded-lg transition-colors" title="Hapus Permanen">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-slate-450">
                                    <svg class="w-12 h-12 text-slate-350" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                    <p class="font-medium">Belum ada karyawan yang diterima.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($hiredApplications->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $hiredApplications->links() }}
            </div>
        @endif
    </div>

    <!-- Candidate Detail Slide-Over -->
    @if($selectedApplication)
        <div class="fixed inset-0 z-50 overflow-hidden" wire:click.self="closeDetails">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="closeDetails"></div>
            <div class="absolute inset-y-0 right-0 w-full max-w-lg bg-white shadow-2xl flex flex-col overflow-y-auto">
                <!-- Header -->
                <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-emerald-600 to-teal-600 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider bg-white/20 text-white rounded-full">✅ Karyawan Diterima</span>
                        <button wire:click="closeDetails" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <h2 class="text-xl font-extrabold">{{ $selectedApplication->candidate->user->name }}</h2>
                    <p class="text-emerald-100 text-sm">{{ $selectedApplication->candidate->user->email }}</p>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-5 flex-1">
                    <!-- Position & Date -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Posisi</p>
                            <p class="text-sm font-bold text-slate-800">{{ $selectedApplication->job_title }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Tanggal Diterima</p>
                            <p class="text-sm font-bold text-slate-800">{{ $selectedApplication->updated_at?->format('d M Y') ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-2">Kontak</p>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                            <a href="{{ $this->getWhatsappUrl($selectedApplication) }}" target="_blank" class="text-sm font-bold text-emerald-600 hover:text-emerald-700">
                                {{ $selectedApplication->candidate->phone }}
                            </a>
                        </div>
                    </div>

                    <!-- Ratings Summary -->
                    @php
                        $scores = $selectedApplication->interviewScores;
                        $avgRating = $scores->avg('rating');
                        $ratingsCount = $scores->count();
                    @endphp
                    @if($ratingsCount > 0)
                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-2">Nilai Keseluruhan</p>
                            <div class="flex items-center gap-2">
                                <span class="text-3xl font-black text-amber-600">{{ number_format($avgRating, 1) }}</span>
                                <span class="text-slate-400 text-sm">/ 5.0</span>
                                <span class="ml-auto text-amber-400 text-lg">★★★★★</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Berdasarkan {{ $ratingsCount }} penilaian dari rekruter.</p>
                        </div>
                    @endif

                    <!-- Answer Summary -->
                    @if($selectedApplication->answers->count())
                        <div>
                            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-2">Jawaban Formulir</p>
                            <div class="space-y-2">
                                @foreach($selectedApplication->answers->take(5) as $answer)
                                    <div class="bg-slate-50 rounded-lg p-3">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">{{ $answer->requirement?->question }}</p>
                                        <p class="text-xs text-slate-700 font-medium">{{ $answer->answer ?? '-' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer Actions -->
                <div class="p-4 border-t border-slate-100 flex gap-2">
                    <a href="{{ $this->getWhatsappUrl($selectedApplication) }}" target="_blank"
                        class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                        Hubungi HRD via WA
                    </a>
                    <button wire:click="closeDetails" class="px-4 py-2.5 border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl text-sm font-semibold transition-all">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Premium Glassmorphic Confirmation Modal -->
    @if($showConfirmModal)
        <div class="fixed inset-0 z-[9999] overflow-y-auto flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div wire:click="cancelConfirm" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>
            
            <!-- Modal Box -->
            <div class="relative w-full max-w-md bg-slate-900/90 backdrop-blur-2xl border border-red-500/30 p-6 rounded-3xl shadow-2xl overflow-hidden animate-scale-up text-white">
                <!-- Decorative top color bar -->
                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-red-500 to-rose-600"></div>
                <!-- Shimmer grid -->
                <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(239,68,68,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(239,68,68,0.03)_1px,transparent_1px)] bg-[size:16px_16px] pointer-events-none"></div>

                <!-- Modal Content -->
                <div class="relative z-10 flex flex-col gap-4 text-center items-center">
                    <div class="w-14 h-14 rounded-2xl bg-red-500/20 border border-red-500/30 flex items-center justify-center text-red-400 shadow-inner">
                        <svg class="h-6 w-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-lg font-black tracking-tight">{{ $confirmTitle }}</h3>
                        <p class="text-xs text-slate-300 mt-2 leading-relaxed font-semibold">
                            {{ $confirmMessage }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3 w-full mt-2">
                        <button type="button" wire:click="cancelConfirm" class="flex-1 py-2.5 bg-white/5 border border-white/10 hover:bg-white/10 text-white rounded-xl text-xs font-bold transition-all">
                            Batalkan
                        </button>
                        <button type="button" wire:click="executeConfirmedAction" class="flex-1 py-2.5 bg-gradient-to-r from-red-650 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white rounded-xl text-xs font-black shadow-md shadow-red-500/20 transition-all">
                            Ya, Konfirmasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(count($selectedApplications) > 0)
        <!-- Dynamic mobile-friendly spacer so bottom elements are never blocked by the floating actions bar -->
        <div class="h-24 w-full pointer-events-none block shrink-0"></div>
    @endif
</div>