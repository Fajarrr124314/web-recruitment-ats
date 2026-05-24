<div>
    <div class="mb-8 md:flex md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Data Kandidat Tidak Lolos</h1>
            <p class="text-slate-500 text-sm mt-1">Daftar kandidat yang belum sesuai dengan kriteria rekrutmen saat ini.</p>
        </div>

        <div class="mt-4 md:mt-0">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama/email..." 
                    class="pl-9 pr-3 py-2 text-sm border border-rose-200 rounded-xl focus:ring-rose-500 focus:border-rose-500 shadow-sm w-full md:w-64 transition-all">
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

    <div class="bg-white/80 backdrop-blur-xl border border-rose-100 rounded-2xl shadow-xl shadow-rose-100/50 overflow-hidden">
        <div class="p-5 border-b border-rose-100 bg-rose-50/50">
            <h3 class="font-bold text-rose-800">Riwayat Kandidat Gagal</h3>
            <p class="text-xs text-rose-600 mt-1">Kandidat yang tidak lolos akan memiliki masa cooldown 1 bulan sebelum bisa melamar kembali. Anda juga bisa menghapus data mereka secara permanen.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-rose-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider w-10">No</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Kandidat</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Posisi</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal Gagal</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Alasan Penolakan</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rose-50 bg-white">
                    @forelse($rejectedApplications as $app)
                        <tr class="hover:bg-rose-50/30 transition-colors">
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
                                <button wire:click="deleteApplication({{ $app->id }})" wire:confirm="Yakin ingin menghapus kandidat ini permanen? Mereka akan bisa melamar kembali tanpa menunggu 1 bulan." class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs shadow-md transition-all flex items-center gap-1.5 ml-auto">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500 italic">
                                Tidak ada data kandidat gagal.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rejectedApplications->hasPages())
            <div class="p-4 border-t border-rose-100 bg-rose-50/30">
                {{ $rejectedApplications->links() }}
            </div>
        @endif
    </div>
</div>
