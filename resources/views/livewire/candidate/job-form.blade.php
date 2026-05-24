<div class="max-w-4xl mx-auto px-3 py-6 sm:px-6 sm:py-10 lg:px-8">
    @if($existingApplication)
        @if($existingApplication->status === 'Ditolak')
            <!-- Rejection State with Cooldown -->
            <div class="bg-white/80 backdrop-blur-xl border border-rose-200 rounded-2xl p-4 sm:p-8 shadow-xl shadow-rose-100/50 mb-8 relative overflow-hidden">
                <!-- Top Gradient bar -->
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-rose-500 to-red-600 rounded-t-2xl"></div>

                <div class="text-center sm:text-left sm:flex sm:items-start gap-6">
                    <div class="mx-auto sm:mx-0 flex items-center justify-center h-16 w-16 rounded-full bg-rose-100 mb-4 sm:mb-0 shrink-0">
                        <svg class="h-8 w-8 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Mohon Maaf, Anda Belum Lolos</h2>
                        <p class="text-slate-600 text-sm mt-2 leading-relaxed">
                            Terima kasih atas ketertarikan Anda untuk bergabung bersama <span class="font-bold">{{ $existingApplication->company_name }}</span> sebagai <span class="font-bold">{{ $existingApplication->job_title }}</span>. 
                            Setelah mengevaluasi profil dan proses seleksi Anda, saat ini kami belum dapat melanjutkan lamaran Anda ke tahap berikutnya.
                        </p>
                        
                        <div class="mt-4 p-4 bg-rose-50 border border-rose-100 rounded-xl">
                            <span class="text-[10px] font-bold text-rose-500 uppercase tracking-wider block mb-1">Catatan dari Tim HRD:</span>
                            <p class="text-sm text-rose-800 font-medium italic">"{{ $existingApplication->rejection_reason }}"</p>
                        </div>

                        @php
                            $cooldownEnd = $existingApplication->rejected_at->addDays(30);
                            $diff = now()->diff($cooldownEnd);
                            $daysLeft = $diff->invert ? 0 : $diff->days;
                        @endphp
                        
                        <div class="mt-6 border-t border-slate-100 pt-6">
                            <h3 class="text-sm font-bold text-slate-700">Kapan Saya Bisa Melamar Lagi?</h3>
                            <p class="text-sm text-slate-500 mt-1">Anda dapat melamar kembali setelah periode evaluasi (1 bulan) selesai. Perkaya terus skill Anda dan kami tunggu profil terbaik Anda!</p>
                            
                            <div class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-slate-100 rounded-lg text-sm font-bold text-slate-700">
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @if($daysLeft > 0)
                                    Sisa Waktu Tunggu: <span class="text-rose-600">{{ $daysLeft }} Hari Lagi</span>
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
            <div class="bg-white/80 backdrop-blur-xl border border-white rounded-2xl p-4 sm:p-8 shadow-xl shadow-red-100/50 mb-8 relative overflow-hidden">
                <!-- Top Gradient bar -->
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-red-500 to-rose-600 rounded-t-2xl"></div>

                <div class="sm:flex sm:items-center sm:justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">Status Lamaran Kerja</h2>
                        <p class="text-slate-500 text-sm mt-1">Anda melamar sebagai <span class="text-red-500 font-semibold">{{ $existingApplication->job_title }}</span> di <span class="text-slate-700 font-semibold">{{ $existingApplication->company_name }}</span></p>
                    </div>
                    <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold tracking-wider uppercase bg-red-500/10 text-red-600 border border-red-500/20">
                            {{ $existingApplication->status }}
                        </span>
                        <a href="https://wa.me/6281234567890?text=Halo HRD {{ $existingApplication->company_name }}, saya {{ auth()->user()->name }} ingin bertanya mengenai status lamaran saya." target="_blank" 
                           class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold tracking-wider uppercase bg-emerald-500 hover:bg-emerald-600 text-white shadow-md transition-colors">
                            Hubungi HRD via WA
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
                                <div class="h-10 w-10 rounded-full flex items-center justify-center border-2 {{ $existingApplication->status === 'Hired' ? 'bg-red-500 border-red-500 text-white shadow-lg shadow-red-500/20' : 'bg-white border-red-100 text-slate-400' }}">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <span class="mt-2 text-xs font-bold {{ $existingApplication->status === 'Hired' ? 'text-red-500' : 'text-slate-400' }}">Hired</span>
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
        <!-- Form Section -->
        <div class="bg-white/80 backdrop-blur-xl border border-red-100 rounded-2xl shadow-xl shadow-red-500/5 overflow-hidden">
            <!-- Header -->
            <div class="relative bg-white">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-red-500 to-rose-600 rounded-t-2xl"></div>
                <div class="p-4 sm:p-8 border-b border-red-100">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight">Formulir Lamaran Kerja</h2>
                    <p class="mt-2 text-sm sm:text-base text-slate-500 max-w-2xl">Lengkapi form di bawah ini untuk melamar. Pastikan data yang Anda berikan valid dan sesuai.</p>
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

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="job_title" class="block text-sm font-bold text-slate-700 mb-2">Posisi yang Dilamar <span class="text-rose-500">*</span></label>
                        <select wire:model.live="job_title" id="job_title" 
                            class="block w-full px-4 py-3 bg-white border border-red-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all sm:text-sm">
                            @foreach($jobPositions as $pos)
                                <option value="{{ $pos->title }}">{{ $pos->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-bold text-slate-700 mb-2">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                        <input wire:model.live.debounce.1000ms="phone" type="text" id="phone" required
                            class="mt-1.5 block w-full px-4 py-3 bg-white border border-red-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all sm:text-sm"
                            placeholder="Contoh: 08123456789">
                        @error('phone') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
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
                                            <span class="ml-1.5 text-rose-500 text-lg leading-none" title="Wajib Diisi">*</span>
                                        @endif
                                    </label>
                                    @if($req->type === 'file')
                                        <p class="text-xs text-slate-500 mt-1">Upload File (PDF/JPG/PNG max 2MB)</p>
                                    @endif
                                </div>
                                
                                <div>
                                    @if($req->type === 'text')
                                        <input type="text" wire:model.live.debounce.1000ms="dynamicAnswers.{{ $req->id }}" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:bg-white transition-all shadow-inner" placeholder="Ketik jawaban Anda disini...">
                                        @error('dynamicAnswers.'.$req->id) <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
                                    @elseif($req->type === 'textarea')
                                        <textarea wire:model.live.debounce.1000ms="dynamicAnswers.{{ $req->id }}" rows="3" class="w-full px-4 py-3 bg-slate-50/50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:bg-white transition-all shadow-inner" placeholder="Ketik detail jawaban Anda disini..."></textarea>
                                        @error('dynamicAnswers.'.$req->id) <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
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
                                            <p class="text-xs text-rose-500 mt-1.5 font-medium">{{ $errorMsg }}</p> 
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
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all shadow-red-500/30 flex items-center justify-center gap-2">
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
</div>
