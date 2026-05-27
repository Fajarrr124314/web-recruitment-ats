<div>
    <div class="mb-8 md:flex md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Data Kandidat Tidak Lolos</h1>
            <p class="text-slate-500 text-sm mt-1">Daftar kandidat yang belum sesuai dengan kriteria rekrutmen saat ini.</p>
        </div>

        <div class="mt-4 md:mt-0">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama/email..." 
                    class="pl-9 pr-3 py-2 text-sm border border-red-200 rounded-xl focus:ring-red-500 focus:border-red-500 shadow-sm w-full md:w-64 transition-all">
                <svg class="h-4 w-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>

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
            
            <!-- Bulk Delete Action -->
            <button type="button" wire:click="triggerConfirm('bulkDelete', 'Hapus Massal Kandidat', 'Apakah Anda yakin ingin menghapus secara permanen {{ count($selectedApplications) }} data kandidat tidak lolos dari sistem? Mereka akan bisa melamar kembali segera.')"
                class="px-2.5 py-1 sm:px-3 sm:py-1.5 bg-gradient-to-r from-red-650 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white rounded-xl text-[10px] sm:text-xs font-bold transition-all shadow-md shadow-red-500/10 flex items-center gap-1 border border-red-500/30 shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                <span>Hapus <span class="hidden sm:inline">Permanen</span></span>
            </button>

            <button wire:click="clearSelection" class="text-slate-450 hover:text-white p-1 sm:p-1.5 rounded-xl hover:bg-slate-800 transition-colors shrink-0" title="Batal Pilih Semua">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-xl border border-red-100 rounded-2xl shadow-xl shadow-red-100/50 overflow-hidden">
        <div class="p-5 border-b border-red-100 bg-red-50/50">
            <h3 class="font-bold text-red-800">Riwayat Kandidat Gagal</h3>
            <p class="text-xs text-red-600 mt-1">Kandidat yang tidak lolos akan memiliki masa cooldown 1 bulan sebelum bisa melamar kembali. Anda juga bisa menghapus data mereka secara permanen.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-red-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-10">
                            <input type="checkbox" class="rounded border-slate-300 text-red-600 focus:ring-red-500"
                                wire:click="toggleSelectAll({{ json_encode($rejectedApplications->pluck('id')->toArray()) }})"
                                @if(count($selectedApplications) && count(array_intersect($rejectedApplications->pluck('id')->toArray(), $selectedApplications)) === $rejectedApplications->count()) checked @endif>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider w-10">No</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Kandidat</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Posisi</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal Gagal</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Alasan Penolakan</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-50 bg-white">
                    @forelse($rejectedApplications as $app)
                        <tr class="hover:bg-red-50/30 transition-colors {{ in_array($app->id, $selectedApplications) ? 'bg-red-50/40' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="rounded border-slate-300 text-red-600 focus:ring-red-500"
                                    wire:model.live="selectedApplications" value="{{ $app->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-400">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-bold text-slate-800">{{ $app->candidate->user->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $app->candidate->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-slate-600 font-medium">{{ $app->job_title }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-slate-500">{{ $app->rejected_at ? $app->rejected_at->format('d M Y') : '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600 truncate block max-w-xs" title="{{ $app->rejection_reason }}">{{ $app->rejection_reason ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button type="button" wire:click="triggerConfirm('deleteApplication', 'Hapus Kandidat', 'Yakin ingin menghapus kandidat {{ $app->candidate->user->name }} secara permanen? Mereka akan bisa melamar kembali tanpa menunggu periode cooldown 1 bulan.', {{ $app->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs shadow-md transition-all flex items-center gap-1.5 ml-auto">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500 italic">
                                Tidak ada data kandidat gagal.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rejectedApplications->hasPages())
            <div class="p-4 border-t border-red-100 bg-red-50/30">
                {{ $rejectedApplications->links() }}
            </div>
        @endif
    </div>

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
