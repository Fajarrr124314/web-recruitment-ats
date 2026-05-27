<div class="max-w-4xl mx-auto px-3 py-6 sm:px-6 sm:py-10 lg:px-8">
    @if($existingApplication)
        @if($existingApplication->status === 'Ditolak')
            <!-- Rejection State with Cooldown -->
            <div class="bg-gradient-to-br from-red-50/5 via-rose-50/5 to-white/60 backdrop-blur-xl border border-red-200/80 rounded-3xl p-6 sm:p-10 shadow-2xl mb-8 relative overflow-hidden">
                <!-- Top Gradient bar -->
                <div class="absolute left-[1px] right-[1px] top-[1px] h-[3px] bg-gradient-to-r from-red-500 to-rose-600 rounded-t-[22px]"></div>

                <div class="text-center sm:text-left sm:flex sm:items-start gap-6">
                    <div class="mx-auto sm:mx-0 flex items-center justify-center h-16 w-16 rounded-2xl bg-red-50 border border-red-100 mb-4 sm:mb-0 shrink-0 shadow-inner">
                        <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Mohon Maaf, Langkah Anda Terhenti</h2>
                        <p class="text-slate-500 text-xs sm:text-sm mt-2 leading-relaxed font-semibold">
                            Terima kasih atas ketertarikan Anda untuk bergabung bersama <span class="font-black text-slate-700">{{ $existingApplication->company_name }}</span> sebagai <span class="font-black text-red-650">{{ $existingApplication->job_title }}</span>. 
                            Setelah mengevaluasi profil dan rangkaian seleksi Anda, dengan berat hati saat ini kami belum dapat melanjutkan lamaran Anda ke tahap berikutnya.
                        </p>
                        
                        @php
                            // Determine failed stage from rejection reason
                            $failedStage = 'Administrasi'; 
                            $reason = strtolower($existingApplication->rejection_reason ?? '');
                            if (str_contains($reason, 'psikotes')) {
                                $failedStage = 'Psikotes';
                            } elseif (str_contains($reason, 'interview') || str_contains($reason, 'wawancara') || str_contains($reason, 'wawancara')) {
                                $failedStage = 'Interview';
                            } elseif (str_contains($reason, 'mcu') || str_contains($reason, 'medical') || str_contains($reason, 'kesehatan')) {
                                $failedStage = 'MCU';
                            }
                            
                            $failedDesc = match($failedStage) {
                                'Administrasi' => 'Tahap Seleksi Administrasi & Berkas',
                                'Psikotes' => 'Tahap Ujian Psikotes Online',
                                'Interview' => 'Tahap Wawancara / Interview Kerja',
                                'MCU' => 'Tahap Medical Check Up (MCU)',
                                default => 'Tahap Seleksi Administrasi'
                            };
                        @endphp

                        <div class="mt-4 p-5 bg-gradient-to-br from-red-50/50 to-rose-50/50 border border-red-200/60 rounded-2xl shadow-inner">
                            <span class="inline-block px-2.5 py-0.5 rounded-full bg-red-100/80 text-red-600 text-[10px] font-black uppercase tracking-widest border border-red-200/50 mb-2">
                                Gagal di: {{ $failedDesc }}
                            </span>
                            <span class="text-[9px] font-black text-slate-450 uppercase tracking-widest block mb-1">Catatan Evaluasi HRD:</span>
                            <p class="text-xs sm:text-sm text-red-800 font-bold italic leading-relaxed">"{{ $existingApplication->rejection_reason ?? 'Mohon maaf, kualifikasi Anda belum sesuai.' }}"</p>
                        </div>

                        @php
                            $cooldownEnd = $existingApplication->rejected_at->addDays(30);
                            $diff = now()->diff($cooldownEnd);
                            $daysLeft = $diff->invert ? 0 : $diff->days;
                        @endphp
                        
                        <div class="mt-6 border-t border-slate-100 pt-6">
                            <h3 class="text-sm font-bold text-slate-700">Kapan Saya Bisa Melamar Lagi?</h3>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">Anda dapat melamar kembali setelah periode evaluasi (1 bulan) selesai. Perkaya terus skill Anda dan kami tunggu profil terbaik Anda!</p>
                            
                            <div class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-slate-100 border border-slate-200/50 rounded-xl text-xs sm:text-sm font-black text-slate-700 shadow-sm">
                                <svg class="w-5 h-5 text-slate-450 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @if($daysLeft > 0)
                                    Sisa Waktu Tunggu: <span class="text-red-600 font-black">{{ $daysLeft }} Hari Lagi</span>
                                @else
                                    Masa tunggu selesai. Silakan refresh halaman ini.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Application Status Stepper (Active) -->
            <div class="bg-white/80 backdrop-blur-xl border border-white rounded-3xl p-4 sm:p-8 shadow-xl mb-8 relative overflow-hidden
                {{ $existingApplication->status === 'Hired' ? 'shadow-green-150/40 ring-1 ring-emerald-500/20' : 'shadow-red-100/50' }}">
                <!-- Top Gradient bar -->
                <div class="absolute left-[1px] right-[1px] top-[1px] h-[3px] bg-gradient-to-r {{ $existingApplication->status === 'Hired' ? 'from-emerald-500 to-green-500' : 'from-red-500 to-red-600' }} rounded-t-[22px]"></div>

                <div class="sm:flex sm:items-center sm:justify-between mb-8">
                    <div>
                        @if($existingApplication->status === 'Hired')
                            <!-- CSS Confetti Shower Animation -->
                            <style>
                                @keyframes confetti-fall {
                                    0% { transform: translateY(-10%) rotate(0deg); opacity: 1; }
                                    100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
                                }
                                .confetti {
                                    position: absolute;
                                    width: 8px;
                                    height: 8px;
                                    border-radius: 2px;
                                    animation: confetti-fall 4s linear infinite;
                                    pointer-events: none;
                                    z-index: 10;
                                }
                            </style>
                            <div class="absolute inset-0 overflow-hidden pointer-events-none rounded-3xl z-[1] opacity-70">
                                <div class="confetti bg-emerald-400" style="left: 10%; animation-delay: 0s; animation-duration: 3.5s;"></div>
                                <div class="confetti bg-amber-400" style="left: 20%; animation-delay: 1.5s; animation-duration: 4.2s;"></div>
                                <div class="confetti bg-rose-400" style="left: 30%; animation-delay: 0.5s; animation-duration: 3.8s;"></div>
                                <div class="confetti bg-blue-400" style="left: 45%; animation-delay: 2.2s; animation-duration: 4.5s;"></div>
                                <div class="confetti bg-purple-400" style="left: 60%; animation-delay: 1.1s; animation-duration: 3.9s;"></div>
                                <div class="confetti bg-emerald-300" style="left: 75%; animation-delay: 0.2s; animation-duration: 4.1s;"></div>
                                <div class="confetti bg-yellow-400" style="left: 90%; animation-delay: 1.8s; animation-duration: 3.6s;"></div>
                                <div class="confetti bg-pink-400" style="left: 15%; animation-delay: 2.5s; animation-duration: 4s;"></div>
                                <div class="confetti bg-teal-400" style="left: 35%; animation-delay: 1.2s; animation-duration: 4.3s;"></div>
                                <div class="confetti bg-indigo-400" style="left: 55%; animation-delay: 0.8s; animation-duration: 3.7s;"></div>
                                <div class="confetti bg-emerald-400" style="left: 70%; animation-delay: 2.9s; animation-duration: 4.4s;"></div>
                                <div class="confetti bg-amber-350" style="left: 85%; animation-delay: 0.4s; animation-duration: 3.5s;"></div>
                            </div>

                            <div class="inline-block mb-3 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest border border-emerald-200 shadow-sm relative z-20">
                                Tahap Akhir Selesai
                            </div>
                            <div class="flex items-center gap-4 relative z-20">
                                <!-- Glowing Trophy -->
                                <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20 animate-[bounce_4s_infinite] shrink-0 border border-emerald-400/20">
                                    <svg class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.503-1.125 1.125-1.125h.872m5.007 0V9.75m-5.007 0V9.75m5.007 0a3 3 0 01-3-3m-3.993 3a3 3 0 003-3m3 3h-3m3 0c.924 0 1.673-.749 1.673-1.672v-.828c0-.414-.336-.75-.75-.75h-7.846c-.414 0-.75.336-.75.75v.828c0 .923.749 1.672 1.673 1.672z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500 mb-1 drop-shadow-sm">
                                        Selamat! Anda Diterima 🎉
                                    </h2>
                                    <p class="text-slate-650 text-sm mt-1 font-semibold max-w-xl leading-relaxed">
                                        Luar biasa! Perjalanan karir Anda sebagai <span class="text-emerald-600 font-extrabold">{{ $existingApplication->job_title }}</span> di <span class="text-slate-800 font-extrabold">{{ $existingApplication->company_name }}</span> segera dimulai. HRD akan menghubungi Anda untuk koordinasi onboard.
                                    </p>
                                </div>
                            </div>
                        @else
                            <h2 class="text-2xl font-bold text-slate-800">Status Lamaran Kerja</h2>
                            <p class="text-slate-500 text-sm mt-1">Anda melamar sebagai <span class="text-red-500 font-semibold">{{ $existingApplication->job_title }}</span> di <span class="text-slate-700 font-semibold">{{ $existingApplication->company_name }}</span></p>
                        @endif
                    </div>
                    <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-extrabold tracking-wider uppercase border
                            {{ $existingApplication->status === 'Hired' ? 'bg-green-50/50 text-green-600 border-green-200' : 'bg-red-50 text-red-600 border-red-200' }}">
                            Status: {{ $existingApplication->status }}
                        </span>
                        <a href="https://wa.me/6281234567890?text=Halo HRD {{ $existingApplication->company_name }}, saya {{ auth()->user()->name }} ingin {{ $existingApplication->status === 'Hired' ? 'bertanya mengenai jadwal onboarding' : 'bertanya mengenai status lamaran' }} saya." target="_blank" 
                           class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-xs font-extrabold tracking-wider uppercase bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white shadow-md shadow-emerald-500/20 transition-all">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.183-.573c.978.582 1.894.882 3.145.882 3.182 0 5.767-2.587 5.768-5.766 0-3.181-2.585-5.77-5.765-5.724zm3.32 8.114c-.156.44-.903.85-1.257.876-.328.024-.541.054-1.684-.393-1.371-.537-2.246-1.92-2.316-2.012-.07-.092-.555-.739-.555-1.411 0-.672.348-1.004.471-1.136.123-.132.268-.165.358-.165s.178.006.257.01c.084.003.197-.032.308.232.116.275.397.971.433 1.044.036.073.06.159.02.241-.041.082-.061.132-.122.203-.061.072-.129.155-.181.21-.056.059-.115.123-.05.234.066.111.293.483.628.784.431.388.794.509.905.565.111.056.176.046.241-.027.065-.073.282-.326.357-.438.075-.112.15-.094.25-.056.1.038.633.298.741.353.108.055.181.082.207.128.026.046.026.264-.13.704z"></path></svg>
                            Tanya HRD
                        </a>
                    </div>
                </div>

                <!-- Stepper Component with MCU -->
                <div class="relative overflow-x-auto pb-4">
                    <div class="min-w-[600px] relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full h-0.5 bg-red-100"></div>
                        </div>
                        
                        <div class="relative flex justify-between">
                            <!-- Step 1: Administrasi -->
                            <div class="flex flex-col items-center">
                                <div class="h-10 w-10 rounded-full flex items-center justify-center border-2 {{ in_array($existingApplication->status, ['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired']) ? 'bg-red-500 border-red-500 text-white shadow-lg shadow-red-500/20' : 'bg-white border-red-100 text-slate-400' }}">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-xs font-bold {{ $existingApplication->status === 'Administrasi' ? 'text-red-500' : 'text-slate-400' }}">Administrasi</span>
                            </div>

                            <!-- Step 2: Psikotes -->
                            <div class="flex flex-col items-center">
                                <div class="h-10 w-10 rounded-full flex items-center justify-center border-2 {{ in_array($existingApplication->status, ['Psikotes', 'Interview', 'MCU', 'Hired']) ? 'bg-red-500 border-red-500 text-white shadow-lg shadow-red-500/20' : 'bg-white border-red-100 text-slate-400' }}">
                                    @if(in_array($existingApplication->status, ['Interview', 'MCU', 'Hired']))
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <span class="mt-2 text-xs font-bold {{ $existingApplication->status === 'Psikotes' ? 'text-red-500' : 'text-slate-400' }}">Psikotes</span>
                            </div>

                            <!-- Step 3: Interview -->
                            <div class="flex flex-col items-center">
                                <div class="h-10 w-10 rounded-full flex items-center justify-center border-2 {{ in_array($existingApplication->status, ['Interview', 'MCU', 'Hired']) ? 'bg-red-500 border-red-500 text-white shadow-lg shadow-red-500/20' : 'bg-white border-red-100 text-slate-400' }}">
                                    @if(in_array($existingApplication->status, ['MCU', 'Hired']))
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    @endif
                                </div>
                                <span class="mt-2 text-xs font-bold {{ $existingApplication->status === 'Interview' ? 'text-red-500' : 'text-slate-400' }}">Interview</span>
                            </div>

                            <!-- Step 4: MCU -->
                            <div class="flex flex-col items-center">
                                <div class="h-10 w-10 rounded-full flex items-center justify-center border-2 {{ in_array($existingApplication->status, ['MCU', 'Hired']) ? 'bg-red-500 border-red-500 text-white shadow-lg shadow-red-500/20' : 'bg-white border-red-100 text-slate-400' }}">
                                    @if(in_array($existingApplication->status, ['Hired']))
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    @endif
                                </div>
                                <span class="mt-2 text-xs font-bold {{ $existingApplication->status === 'MCU' ? 'text-red-500' : 'text-slate-400' }}">MCU</span>
                            </div>

                            <!-- Step 5: Hired -->
                            <div class="flex flex-col items-center">
                                <div class="h-10 w-10 rounded-full flex items-center justify-center border-2 {{ $existingApplication->status === 'Hired' ? 'bg-gradient-to-r from-green-500 to-emerald-500 border-transparent text-white shadow-lg shadow-green-500/30' : 'bg-white border-red-100 text-slate-400' }}">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-xs font-black {{ $existingApplication->status === 'Hired' ? 'text-green-600' : 'text-slate-400' }}">Hired</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Answers Overview -->
                <div class="mt-8 border-t border-red-100 pt-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Berkas yang Disubmit</h3>
                    <div class="space-y-3">
                        @foreach($existingApplication->answers as $ans)
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex justify-between items-center">
                                <span class="font-semibold text-slate-700">{{ $ans->requirement->question }}</span>
                                @if($ans->requirement->type === 'file')
                                    <a href="{{ Storage::url($ans->answer) }}" target="_blank" class="text-red-600 hover:underline font-medium text-sm">Lihat File</a>
                                @else
                                    <span class="text-slate-600">{{ $ans->answer }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @else
        @if($isGlobalRegistrationDisabled)
            <!-- Inactive Portal / Scheduled Mode -->
            <div class="space-y-6">
                <!-- Main Message Card -->
                <div class="bg-white/80 backdrop-blur-xl border border-rose-200 rounded-3xl p-8 sm:p-12 shadow-2xl shadow-rose-500/10 text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-rose-50/50 to-orange-50/50 mix-blend-overlay"></div>
                    
                    <div class="relative z-10 flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-rose-100 to-red-100 rounded-full flex items-center justify-center mb-6 shadow-inner border border-rose-200">
                            @if($globalScheduledOpenAtIso)
                                <svg class="w-12 h-12 text-rose-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @else
                                <svg class="w-12 h-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            @endif
                        </div>

                        <h2 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight mb-4">
                            @if($globalScheduledOpenAtIso)
                                Bersiaplah! Pendaftaran Segera Dibuka
                            @else
                                Lowongan Belum Dibuka
                            @endif
                        </h2>

                        <p class="text-slate-600 max-w-lg mx-auto text-sm sm:text-base font-medium leading-relaxed mb-8">
                            @if($globalScheduledOpenAtIso)
                                Portal pendaftaran akan terbuka secara otomatis. Siapkan berkas lamaran Anda dengan baik dan jadilah yang pertama melamar!
                            @else
                                Saat ini portal rekrutmen PT Indonesia sedang ditutup. Silakan pantau terus halaman ini atau media sosial kami untuk informasi pembukaan gelombang berikutnya.
                            @endif
                        </p>

                        @if($globalScheduledOpenAtIso)
                            <!-- Client-side Countdown Timer -->
                            <div class="bg-white border border-rose-200 rounded-2xl p-6 shadow-lg shadow-rose-500/10 inline-block"
                                 x-data="{
                                    deadline: new Date('{{ $globalScheduledOpenAtIso }}').getTime(),
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
                                            window.location.reload(); // Refresh when time is up
                                        }
                                    }
                                 }">
                                <div class="flex gap-4 sm:gap-6 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-3xl sm:text-5xl font-black text-rose-600 font-mono tracking-tighter" x-text="days"></span>
                                        <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Hari</span>
                                    </div>
                                    <span class="text-3xl sm:text-5xl font-black text-rose-200">:</span>
                                    <div class="flex flex-col items-center">
                                        <span class="text-3xl sm:text-5xl font-black text-rose-600 font-mono tracking-tighter" x-text="hours"></span>
                                        <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Jam</span>
                                    </div>
                                    <span class="text-3xl sm:text-5xl font-black text-rose-200">:</span>
                                    <div class="flex flex-col items-center">
                                        <span class="text-3xl sm:text-5xl font-black text-rose-600 font-mono tracking-tighter" x-text="minutes"></span>
                                        <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Menit</span>
                                    </div>
                                    <span class="text-3xl sm:text-5xl font-black text-rose-200">:</span>
                                    <div class="flex flex-col items-center">
                                        <span class="text-3xl sm:text-5xl font-black text-rose-600 font-mono tracking-tighter" x-text="seconds"></span>
                                        <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Detik</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tips & Tricks Section -->
                <div class="bg-gradient-to-br from-red-500/10 via-amber-500/5 to-white/40 border border-white/60 rounded-3xl p-1 shadow-2xl relative overflow-hidden backdrop-blur-md">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIgZmlsbD0icmdiYSgyNTUsIDg3LCA4NywgMC4wNCkiLz48Lz9zdmc+')] opacity-60"></div>
                    
                    <div class="bg-white/70 backdrop-blur-2xl rounded-[22px] p-8 sm:p-12 relative z-10">
                        <div class="flex flex-col lg:flex-row items-center gap-10">
                            <!-- Premium Glowing Icon Side -->
                            <div class="w-full lg:w-1/3 flex justify-center">
                                <div class="relative w-44 h-44 flex items-center justify-center group">
                                    <!-- Glowing backdrops -->
                                    <div class="absolute w-32 h-32 bg-red-400/20 rounded-full blur-2xl group-hover:bg-amber-400/25 transition-all duration-700"></div>
                                    <div class="absolute w-28 h-28 bg-gradient-to-tr from-red-50/80 to-amber-50/80 border border-white rounded-3xl shadow-xl flex items-center justify-center transform rotate-6 group-hover:rotate-12 transition-transform duration-500">
                                        <!-- Premium multi-layered SVG lightbulb/rocket -->
                                        <svg class="w-16 h-16 text-red-500 animate-[bounce_4s_ease-in-out_infinite]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.808 13.064a9 9 0 1111.218 0M12 9v2m0 4h.01" />
                                        </svg>
                                    </div>
                                    <!-- Subtle spinning decoration -->
                                    <div class="absolute inset-0 border border-dashed border-red-300/30 rounded-full animate-[spin_20s_linear_infinite]"></div>
                                </div>
                            </div>

                            <!-- Tips Content -->
                            <div class="w-full lg:w-2/3">
                                <span class="inline-block px-3 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest border border-red-200/50 mb-4 shadow-sm">
                                    Tips & Trick AI Screening
                                </span>
                                <h3 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight mb-6">
                                    Rahasia Lolos <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">Smart Screening</span>
                                </h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="bg-white/60 border border-red-100/50 p-5 rounded-2xl shadow-sm hover:border-red-350 hover:bg-white/90 transition-all duration-300">
                                        <div class="w-8 h-8 rounded-full bg-red-50 border border-red-200/60 flex items-center justify-center text-red-600 font-black mb-3">1</div>
                                        <h4 class="text-sm font-bold text-slate-800 mb-1">Update Curriculum Vitae</h4>
                                        <p class="text-xs text-slate-500 leading-relaxed font-medium">Pastikan CV mencantumkan pengalaman dan skill terbaru secara spesifik.</p>
                                    </div>
                                    <div class="bg-white/60 border border-red-100/50 p-5 rounded-2xl shadow-sm hover:border-red-350 hover:bg-white/90 transition-all duration-300">
                                        <div class="w-8 h-8 rounded-full bg-orange-50 border border-orange-200/60 flex items-center justify-center text-orange-600 font-black mb-3">2</div>
                                        <h4 class="text-sm font-bold text-slate-800 mb-1">Baca Syarat Seksama</h4>
                                        <p class="text-xs text-slate-500 leading-relaxed font-medium">Pahami setiap persyaratan posisi. Ketidaksesuaian akan otomatis ditolak AI.</p>
                                    </div>
                                    <div class="bg-white/60 border border-red-100/50 p-5 rounded-2xl shadow-sm hover:border-red-350 hover:bg-white/90 transition-all duration-300">
                                        <div class="w-8 h-8 rounded-full bg-amber-50 border border-amber-200/60 flex items-center justify-center text-amber-600 font-black mb-3">3</div>
                                        <h4 class="text-sm font-bold text-slate-800 mb-1">Siapkan Dokumen PDF</h4>
                                        <p class="text-xs text-slate-500 leading-relaxed font-medium">Ubah dokumen menjadi PDF berkualitas baik dengan ukuran maksimal 2MB.</p>
                                    </div>
                                    <div class="bg-white/60 border border-red-100/50 p-5 rounded-2xl shadow-sm hover:border-red-350 hover:bg-white/90 transition-all duration-300">
                                        <div class="w-8 h-8 rounded-full bg-emerald-50 border border-emerald-200/60 flex items-center justify-center text-emerald-600 font-black mb-3">4</div>
                                        <h4 class="text-sm font-bold text-slate-800 mb-1">Jawaban Jujur & Jelas</h4>
                                        <p class="text-xs text-slate-500 leading-relaxed font-medium">Jawab pertanyaan rekrutmen secara rinci. HRD menilai komunikasi tertulis Anda.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
        <!-- Form Section -->
        <div class="bg-white/80 backdrop-blur-xl border border-red-100 rounded-3xl shadow-2xl shadow-red-500/5 overflow-hidden">
            <!-- Header -->
            <div class="relative bg-white">
                <div class="absolute left-[1px] right-[1px] top-[1px] h-[3px] bg-gradient-to-r from-red-500 to-red-600 rounded-t-[22px]"></div>
                <div class="p-6 sm:p-10 border-b border-red-50">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest border border-red-100">Registrasi Terbuka</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight">Formulir Lamaran Kerja</h2>
                    <p class="mt-2 text-sm sm:text-base text-slate-500 max-w-2xl font-medium">Lengkapi form di bawah ini untuk melamar. Pastikan data yang Anda berikan valid dan sesuai.</p>
                </div>
            </div>

            <!-- Alert Success -->
            @if (session()->has('success'))
                <div class="m-4 sm:m-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-600 text-sm flex items-center space-x-2">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form wire:submit.prevent="submitApplication" class="p-4 sm:p-8 space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-500">Nama Lengkap</label>
                        <div class="mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 sm:text-sm select-none">
                            {{ auth()->user()->name }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-500">Alamat Email</label>
                        <div class="mt-1.5 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 sm:text-sm select-none">
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                </div>

                <!-- Job Position Selection Cards (Replaces Dropdown) -->
                <div class="space-y-4 pt-2">
                    <label class="block text-sm font-bold text-slate-750">Pilih Posisi yang Dilamar <span class="text-red-500">*</span></label>
                    
                    @if($isTalentPoolMode && count($jobPositions) === 0)
                        <!-- Fallback: Talent Pool Mode Only -->
                        <div class="p-5 rounded-2xl border-2 border-red-500 bg-red-50/10 shadow-md shadow-red-500/5 flex flex-col gap-3 select-none">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="font-extrabold text-slate-800 text-base leading-tight">Talent Pool Umum</h3>
                                <span class="w-5.5 h-5.5 rounded-full bg-red-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-red-500/20">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                </span>
                            </div>
                            <div class="text-xs text-slate-650 leading-relaxed font-medium">
                                <span class="text-[9px] font-black text-slate-450 uppercase tracking-widest block mb-1">Keterangan:</span>
                                <p class="whitespace-pre-line bg-slate-50 p-2.5 rounded-xl border border-slate-100 italic text-slate-500">
                                    Saat ini belum ada posisi lowongan spesifik yang dibuka. Anda akan didaftarkan ke Talent Pool Umum kami.
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- List of Job Position Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($jobPositions as $pos)
                                @php
                                    $isSelected = $job_title === $pos->title;
                                @endphp
                                <div wire:click="selectJob('{{ $pos->title }}')" 
                                     class="relative p-4 rounded-xl border cursor-pointer transition-all duration-300 flex flex-col justify-between gap-3 select-none
                                     {{ $isSelected 
                                         ? 'border-red-500 bg-red-50/5 shadow-sm ring-1 ring-red-500/20' 
                                         : 'border-slate-200 hover:border-red-300 hover:bg-slate-50/30' }}">
                                     
                                     <!-- Decorative Top Highlight when Selected (mathematically adjusted inside the border to avoid spills) -->
                                     @if($isSelected)
                                         <div class="absolute left-[1px] right-[1px] top-[1px] h-[3px] bg-gradient-to-r from-red-500 to-red-600 rounded-t-[10px]"></div>
                                     @endif

                                     <div>
                                         <div class="flex items-start justify-between gap-3">
                                             <h4 class="font-extrabold text-slate-800 text-xs sm:text-sm leading-tight group-hover:text-red-650 transition-colors">{{ $pos->title }}</h4>
                                             @if($isSelected)
                                                 <span class="w-4.5 h-4.5 rounded-full bg-red-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-red-500/10">
                                                     <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path></svg>
                                                 </span>
                                             @else
                                                 <span class="w-4.5 h-4.5 rounded-full border border-slate-200 bg-white shrink-0"></span>
                                             @endif
                                         </div>
                                         
                                         <!-- Collapsible Requirements -->
                                         <div x-data="{ open: @json($isSelected) }" class="mt-2.5">
                                             <!-- Button to toggle requirements detail -->
                                             <button type="button" @click.stop="open = !open" class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50/80 hover:bg-red-100 text-red-650 hover:text-red-700 rounded-lg text-[9px] font-black uppercase tracking-wider transition-all border border-red-200/40 shadow-sm">
                                                 <svg class="w-2.5 h-2.5 transition-transform duration-300" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                     <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                 </svg>
                                                 <span x-text="open ? 'Tutup Syarat' : 'Lihat Syarat Detail'"></span>
                                             </button>

                                             <!-- Requirements Content -->
                                             <div x-show="open" 
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 transform -translate-y-1"
                                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                                 x-transition:leave="transition ease-in duration-100"
                                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                                 x-transition:leave-end="opacity-0 transform -translate-y-1"
                                                 class="mt-2.5 text-[11px] font-medium text-slate-650 space-y-1">
                                                 <span class="text-[8px] font-black text-slate-450 uppercase tracking-widest block mb-1">Syarat & Kualifikasi:</span>
                                                 @if($pos->requirements)
                                                     <div class="whitespace-pre-line bg-slate-50/70 p-3 rounded-lg border border-slate-150 leading-relaxed text-slate-600 shadow-inner">
                                                         {{ $pos->requirements }}
                                                     </div>
                                                 @else
                                                     <div class="flex items-center gap-1 py-0.5 text-slate-400 italic text-[10px]">
                                                         <svg class="w-3 h-3 text-slate-350" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6"></path></svg>
                                                         <span>Tidak ada persyaratan khusus.</span>
                                                     </div>
                                                 @endif
                                             </div>
                                         </div>
                                     </div>

                                     <!-- Closing Date badge if exists -->
                                     @if($pos->expires_at)
                                         <div class="flex items-center gap-1.5 text-[10px] font-semibold {{ \Carbon\Carbon::parse($pos->expires_at)->isPast() ? 'text-red-500' : 'text-amber-600' }}">
                                             <svg class="w-3.5 h-3.5 text-current" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                             <span>Tutup: {{ \Carbon\Carbon::parse($pos->expires_at)->translatedFormat('d M Y, H:i') }}</span>
                                         </div>
                                     @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Personal Info: WhatsApp -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 pt-4">
                    <div>
                        <label for="phone" class="block text-sm font-bold text-slate-750">Nomor WhatsApp <span class="text-red-500">*</span></label>
                        <p class="text-[10px] text-slate-400 mt-0.5 mb-1.5">Digunakan oleh HRD untuk menghubungi Anda mengenai kelanjutan seleksi.</p>
                        <input wire:model.live.debounce.1000ms="phone" type="text" id="phone" required
                            class="block w-full px-4 py-3 bg-white border border-red-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all sm:text-sm"
                            placeholder="Contoh: 08123456789">
                        @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Dynamic Requirements Table -->
                <div class="pt-6 border-t border-red-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Daftar Persyaratan Berkas / Jawaban</h3>
                    
                    <div class="space-y-5">
                        @forelse($requirements as $req)
                            <div class="bg-white p-5 rounded-xl border border-red-100 shadow-sm hover:border-red-300 transition-all">
                                <div class="mb-3">
                                    <label class="flex items-center text-sm font-bold text-slate-800">
                                        {{ $req->question }}
                                        @if($req->is_required)
                                            <span class="ml-1.5 text-red-500 text-lg leading-none" title="Wajib Diisi">*</span>
                                        @endif
                                    </label>
                                    @if($req->type === 'file')
                                        <p class="text-xs text-slate-500 mt-1">Upload File (PDF/JPG/PNG max 2MB)</p>
                                    @endif
                                </div>
                                
                                <div>
                                    @if($req->type === 'text')
                                        <input type="text" wire:model.live.debounce.1000ms="dynamicAnswers.{{ $req->id }}" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:bg-white transition-all shadow-inner" placeholder="Ketik jawaban Anda disini...">
                                        @error('dynamicAnswers.'.$req->id) <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                                    @elseif($req->type === 'textarea')
                                        <textarea wire:model.live.debounce.1000ms="dynamicAnswers.{{ $req->id }}" rows="3" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:bg-white transition-all shadow-inner" placeholder="Ketik detail jawaban Anda disini..."></textarea>
                                        @error('dynamicAnswers.'.$req->id) <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                                    @elseif($req->type === 'select')
                                        @php
                                            $options = explode(';', $req->options ?? '');
                                        @endphp
                                        <select wire:model.live.debounce.1000ms="dynamicAnswers.{{ $req->id }}" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:bg-white transition-all shadow-inner">
                                            <option value="">Pilih Jawaban</option>
                                            @foreach($options as $opt)
                                                @if(trim($opt) !== '')
                                                    <option value="{{ trim($opt) }}">{{ trim($opt) }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('dynamicAnswers.'.$req->id) <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                                    @elseif($req->type === 'file')
                                        <div class="relative w-full">
                                            @if(isset($existingFiles[$req->id]))
                                                <div class="mb-2 p-3 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span class="text-sm text-emerald-800 font-medium">File sudah diunggah di Draft</span>
                                                    </div>
                                                    <a href="{{ Storage::url($existingFiles[$req->id]) }}" target="_blank" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 underline">Lihat File</a>
                                                </div>
                                                <p class="text-xs text-slate-500 mb-2 italic">Unggah file baru jika ingin mengganti file yang sudah ada.</p>
                                            @endif
                                            <input type="file" wire:model.live="dynamicFiles.{{ $req->id }}" class="w-full text-sm text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition-colors border border-slate-200 bg-slate-50/50 rounded-lg p-1.5 focus:ring-2 focus:ring-red-500 focus:outline-none cursor-pointer">
                                            <div wire:loading wire:target="dynamicFiles.{{ $req->id }}" class="mt-2 text-sm text-red-600 font-bold flex items-center">
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Mengunggah...
                                            </div>
                                        </div>
                                        @error('dynamicFiles.'.$req->id) 
                                            @php
                                                $errorMsg = $message;
                                                if (str_contains($message, 'greater than') || str_contains($message, 'kilobytes')) {
                                                    $errorMsg = 'Ukuran file tidak boleh lebih dari 2MB.';
                                                }
                                            @endphp
                                            <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $errorMsg }}</p> 
                                        @enderror
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-sm text-slate-500 italic bg-slate-50 rounded-xl border border-slate-200 shadow-inner">
                                Tidak ada persyaratan khusus dari HRD.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-red-100 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-red-600 to-red-600 hover:from-red-700 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all shadow-red-500/30 flex items-center justify-center gap-2">
                        <span>Kirim Lamaran Sekarang</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                    <div wire:loading.delay class="text-sm font-bold text-slate-500 flex items-center ml-4">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan otomatis...
                    </div>
                </div>
            </form>
        </div>
    @endif
    @endif
</div>
