<div class="max-w-7xl mx-auto py-4 sm:py-8 space-y-8">
    <!-- Header Block with premium styling exactly matching activity logs -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-red-950 p-6 rounded-3xl border border-slate-700/50 shadow-2xl relative overflow-hidden">
        <!-- Background Glow -->
        <div class="absolute -right-24 -top-24 w-52 h-52 bg-red-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -left-24 -bottom-24 w-52 h-52 bg-slate-500/20 rounded-full blur-3xl"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-red-500 text-white shadow-lg shadow-red-500/20 animate-pulse">
                        Rekap & Analitik
                    </span>
                    <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-slate-700 text-slate-300">
                        Analitik Sistem
                    </span>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">
                    Analitik Rekrutmen & Ringkasan ATS
                </h1>
                <p class="text-slate-300 text-sm max-w-2xl leading-relaxed">
                    Ringkasan data statistik funnel rekrutmen, tingkat penerimaan, dan performa kandidat secara menyeluruh. Terintegrasi secara real-time.
                </p>
            </div>

            <!-- A premium total candidate widget matching activity logs counter -->
            <div class="bg-white/10 backdrop-blur-md border border-white/10 p-4 rounded-2xl text-center shrink-0 min-w-[120px] shadow-inner">
                <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Pelamar</div>
                <div class="text-3xl font-extrabold text-white mt-1">{{ $totalCandidates }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5">kandidat</div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Total Candidates -->
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-50 rounded-full"></div>
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Pelamar</div>
            <div class="text-3xl font-extrabold text-slate-800">{{ $totalCandidates }}</div>
            <div class="text-xs text-blue-600 mt-1 font-semibold">Semua Tahap</div>
        </div>
        <!-- Active -->
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-50 rounded-full"></div>
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Kandidat Aktif</div>
            <div class="text-3xl font-extrabold text-amber-600">{{ $totalActive }}</div>
            <div class="text-xs text-amber-600 mt-1 font-semibold">Sedang Diproses</div>
        </div>
        <!-- Hired -->
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-emerald-50 rounded-full"></div>
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Diterima (Hired)</div>
            <div class="text-3xl font-extrabold text-emerald-600">{{ $totalHired }}</div>
            <div class="text-xs text-emerald-600 mt-1 font-semibold">{{ $acceptanceRate }}% Acceptance Rate</div>
        </div>
        <!-- Rejected -->
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-red-50 rounded-full"></div>
            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Ditolak / Gagal</div>
            <div class="text-3xl font-extrabold text-red-600">{{ $totalRejected }}</div>
            <div class="text-xs text-red-600 mt-1 font-semibold">{{ $rejectionRate }}% Rejection Rate</div>
        </div>
    </div>

    <!-- Funnel + Time to Hire Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recruitment Funnel (2/3 width) -->
        <div class="lg:col-span-2 bg-white border border-slate-100 rounded-3xl shadow-xl shadow-red-100/10 p-6 sm:p-8 relative overflow-hidden">
            <!-- Ambient Glowing background -->
            <div class="absolute -right-24 -bottom-24 w-52 h-52 bg-rose-500/5 rounded-full blur-3xl"></div>
            
            <div class="border-b border-slate-100 pb-4 mb-6">
                <span class="px-2.5 py-0.5 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest border border-red-100">AI pipeline</span>
                <h2 class="text-lg font-extrabold text-slate-800 tracking-tight mt-1">Funnel Rekrutmen</h2>
            </div>

            <!-- Funnel Layout (Centered decreasing bars) -->
            <div class="flex flex-col items-center justify-center space-y-4 py-4">
                @php
                    $funnelColors = [
                        'Administrasi' => ['bg' => 'from-blue-500 to-indigo-500', 'glow' => 'shadow-blue-500/20', 'icon' => '📄'],
                        'Psikotes'     => ['bg' => 'from-red-500 to-rose-500', 'glow' => 'shadow-red-500/20', 'icon' => '🧠'],
                        'Interview'    => ['bg' => 'from-amber-500 to-orange-500', 'glow' => 'shadow-amber-500/20', 'icon' => '💬'],
                        'MCU'          => ['bg' => 'from-purple-500 to-fuchsia-500', 'glow' => 'shadow-purple-500/20', 'icon' => '🏥'],
                        'Hired'        => ['bg' => 'from-emerald-500 to-green-500', 'glow' => 'shadow-emerald-500/20', 'icon' => '🏆'],
                    ];
                    $maxCount = collect($conversionRates)->max('count') ?: 1;
                @endphp
                @foreach($conversionRates as $stageName => $stageData)
                    @php
                        $colors = $funnelColors[$stageName] ?? ['bg' => 'from-slate-400 to-slate-500', 'glow' => 'shadow-slate-500/20', 'icon' => '•'];
                        $stepDownRatio = match($stageName) {
                            'Administrasi' => 100,
                            'Psikotes'     => 85,
                            'Interview'    => 70,
                            'MCU'          => 55,
                            'Hired'        => 40,
                        };
                        $widthPercent = $stepDownRatio;
                    @endphp
                    <!-- Funnel Stage Bar wrapper -->
                    <div class="w-full flex justify-center transform hover:scale-[1.02] transition-all duration-300 relative group" style="max-width: {{ $widthPercent }}%">
                        <!-- The Bar itself -->
                        <div class="w-full bg-gradient-to-r {{ $colors['bg'] }} rounded-2xl p-4 flex items-center justify-between shadow-lg {{ $colors['glow'] }} text-white relative overflow-hidden">
                            <!-- Shimmer animation overlay -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
                            
                            <!-- Left: Stage Icon & Label -->
                            <div class="flex items-center gap-3 relative z-10">
                                <span class="text-xl shrink-0">{{ $colors['icon'] }}</span>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-black tracking-wide">{{ $stageName }}</h4>
                                    <span class="text-[9px] font-bold text-white/70 block uppercase tracking-wider">{{ $stageData['label'] }}</span>
                                </div>
                            </div>

                            <!-- Right: Count & Percentage -->
                            <div class="text-right relative z-10">
                                <span class="text-base sm:text-lg font-black">{{ $stageData['count'] }}</span>
                                <span class="text-[10px] text-white/80 block font-bold">Kandidat ({{ $stageData['rate'] }}%)</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Time to Hire & Stats (1/3 width) -->
        <div class="space-y-4">
            <!-- Time to Hire -->
            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6 text-center">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Rata-rata Waktu Rekrutmen</div>
                @if($timeToHire !== null)
                    <div class="text-5xl font-black text-red-600">{{ $timeToHire }}</div>
                    <div class="text-slate-400 text-sm font-medium mt-1">hari</div>
                    <p class="text-xs text-slate-400 mt-3">Dihitung dari tanggal lamaran masuk sampai kandidat berstatus Hired.</p>
                @else
                    <div class="text-slate-300 text-2xl font-bold">—</div>
                    <p class="text-xs text-slate-400 mt-2">Belum ada data karyawan yang diterima.</p>
                @endif
            </div>

            <!-- Acceptance vs Rejection -->
            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Komposisi Hasil</div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-emerald-600">✓ Diterima</span>
                        <span class="text-sm font-extrabold text-emerald-600">{{ $acceptanceRate }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $acceptanceRate }}%"></div>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-xs font-semibold text-red-500">✕ Ditolak</span>
                        <span class="text-sm font-extrabold text-red-500">{{ $rejectionRate }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $rejectionRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Candidates Leaderboard + Job Position Analysis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Candidates -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6">
            <h2 class="text-base font-extrabold text-slate-800 mb-4">🏆 Top Kandidat (Nilai Tertinggi)</h2>
            @if($topCandidates->count() > 0)
                <div class="space-y-3">
                    @foreach($topCandidates as $index => $app)
                        @php
                            $avgRating = $app->interviewScores->avg('rating');
                            $medals = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣'];
                        @endphp
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="text-lg">{{ $medals[$index] ?? '#' . ($index + 1) }}</span>
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                {{ mb_strtoupper(mb_substr($app->candidate->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $app->candidate->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $app->job_title }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-sm font-extrabold text-amber-600">★ {{ number_format($avgRating, 1) }}</div>
                                <div class="text-[10px] text-slate-400">/ 5.0</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-slate-400">
                    <p class="text-sm">Belum ada penilaian kandidat.</p>
                </div>
            @endif
        </div>

        <!-- Job Position Analysis -->
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6">
            <h2 class="text-base font-extrabold text-slate-800 mb-4">📊 Analisis Per Posisi</h2>
            @if($positionsData->count() > 0)
                <div class="space-y-3">
                    @foreach($positionsData->take(6) as $pos)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <div>
                                <p class="text-sm font-bold text-slate-800 truncate max-w-[180px]">{{ $pos->job_title }}</p>
                                <p class="text-xs text-slate-500">{{ $pos->total }} pelamar · {{ $pos->hired_count }} diterima</p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold
                                    {{ $pos->success_rate >= 50 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($pos->success_rate > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-500 border border-slate-200') }}">
                                    {{ $pos->success_rate }}%
                                </span>
                                <div class="text-[10px] text-slate-400 mt-0.5">Hired Rate</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-slate-400">
                    <p class="text-sm">Belum ada data posisi.</p>
                </div>
            @endif
        </div>
    </div>
</div>