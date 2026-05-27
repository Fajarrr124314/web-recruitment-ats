<div class="max-w-7xl mx-auto py-4 sm:py-8 space-y-6">
    <!-- Header Block with Security Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-red-950 p-6 rounded-3xl border border-slate-700/50 shadow-2xl relative overflow-hidden">
        <!-- Background Glow -->
        <div class="absolute -right-24 -top-24 w-52 h-52 bg-red-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -left-24 -bottom-24 w-52 h-52 bg-slate-500/20 rounded-full blur-3xl"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-red-500 text-white shadow-lg shadow-red-500/20 animate-pulse">
                        Sistem Jejak Audit
                    </span>
                    <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-slate-700 text-slate-300">
                        Read-Only (Non-Modifiable)
                    </span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">
                    Log Riwayat & Aktivitas Recruiter
                </h1>
                <p class="text-slate-300 text-sm max-w-2xl">
                    Arsip permanen pencatatan aktivitas pemindahan status, penolakan massal, dan penilaian kandidat. Riwayat ini tidak dapat diubah atau dihapus oleh pihak HRD guna menjamin transparansi rekrutmen.
                </p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md border border-white/10 p-4 rounded-2xl text-center shrink-0 min-w-[120px] shadow-inner">
                <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Log</div>
                <div class="text-3xl font-extrabold text-white mt-1">{{ $logs->total() }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">entri</div>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="flex flex-col sm:flex-row gap-3">
        <!-- Search -->
        <div class="relative flex-1">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama recruiter, kandidat, atau deskripsi..."
                class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-red-500 focus:border-red-500 bg-white shadow-sm">
            <svg class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <!-- Action Filter -->
        <select wire:model.live="filterAction" class="py-2.5 pl-3 pr-8 text-sm border border-slate-200 rounded-xl focus:ring-red-500 bg-white shadow-sm text-slate-700">
            <option value="">Semua Tipe Aksi</option>
            <option value="status_change">Pindah Tahap</option>
            <option value="rejected">Tolak Kandidat</option>
            <option value="scoring">Beri Penilaian</option>
        </select>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Recruiter</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kandidat</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    @forelse($logs as $log)
                        @php
                            $actionConfig = match($log->action) {
                                'status_change'  => ['color' => 'text-blue-600 bg-blue-50 border-blue-200', 'icon' => '⇄', 'label' => 'Pindah Tahap'],
                                'rejected'       => ['color' => 'text-red-600 bg-red-50 border-red-200', 'icon' => '✕', 'label' => 'Tolak Kandidat'],
                                'scoring'        => ['color' => 'text-amber-600 bg-amber-50 border-amber-200', 'icon' => '★', 'label' => 'Penilaian'],
                                'hired'          => ['color' => 'text-emerald-600 bg-emerald-50 border-emerald-200', 'icon' => '✓', 'label' => 'Hired'],
                                default          => ['color' => 'text-slate-600 bg-slate-50 border-slate-200', 'icon' => '•', 'label' => $log->action],
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-700 font-medium">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-slate-400">{{ $log->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="h-7 w-7 rounded-full bg-gradient-to-br from-slate-600 to-slate-800 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                        {{ mb_strtoupper(mb_substr($log->user?->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-semibold text-slate-800">{{ $log->user?->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->application?->candidate?->user)
                                    <div class="text-sm font-semibold text-slate-800">{{ $log->application->candidate->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $log->application->job_title }}</div>
                                @else
                                    <span class="text-xs text-slate-400 italic">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold border {{ $actionConfig['color'] }}">
                                    <span>{{ $actionConfig['icon'] }}</span>
                                    {{ $actionConfig['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <p class="text-sm text-slate-600 truncate" title="{{ $log->description }}">{{ $log->description }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-slate-400">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="font-medium">Belum ada log aktivitas.</p>
                                    <p class="text-xs text-slate-300">Log akan otomatis muncul saat ada aktivitas rekrutmen.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>