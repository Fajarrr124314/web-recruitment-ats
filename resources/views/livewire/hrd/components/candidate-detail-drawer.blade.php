<div>
    <!-- Candidate Detail Slide-out Drawer / Modal -->
    <div x-data="{ 
        open: false,
        selectedId: @entangle('selectedApplicationId')
    }"
        x-init="
            $watch('selectedId', value => {
                if (value) {
                    open = true;
                } else {
                    open = false;
                }
            })
        "
        @keydown.escape.window="open = false; selectedId = null; $wire.closeDetails()"
        @show-candidate-details.window="open = true"
        class="relative z-50">

        <!-- Backdrop overlay (Optimized to 0ms instant display to completely eliminate lag/frame drops) -->
        <div x-show="open" 
             @click="open = false; selectedId = null; $wire.closeDetails()" 
             class="fixed inset-0 bg-slate-800/40 backdrop-blur-xs z-50" style="display: none;"></div>

        <!-- Sliding Container (Optimized to 0ms instant display per user request) -->
        <div x-show="open"
             class="fixed inset-y-0 right-0 flex max-w-full pl-10 z-50 w-full max-w-2xl gpu-accelerated" style="display: none;">
                    <div class="pointer-events-auto w-screen max-w-2xl">
                        <div class="flex h-full flex-col overflow-y-scroll bg-white border-l border-red-100 shadow-2xl relative">
                            
                            <!-- Drawer Top Gradient Border Line -->
                            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r {{ $theme['gradient'] }}"></div>

                            <!-- Header -->
                            <div class="px-6 py-5 border-b border-red-100 flex items-center justify-between bg-white sticky top-0 z-10">
                                <div>
                                    <h2 class="text-xl font-bold text-slate-800" id="slide-over-title">
                                        @if($selectedApplication)
                                            Detail Pelamar: <span class="bg-clip-text text-transparent bg-gradient-to-r {{ $theme['gradient'] }}">{{ $selectedApplication->candidate->user->name }}</span>
                                        @else
                                            Memuat Detail Pelamar...
                                        @endif
                                    </h2>
                                    @if($selectedApplication)
                                        <p class="text-xs text-slate-500 mt-0.5">Lamaran masuk pada {{ $selectedApplication->created_at->format('d M Y, H:i') }}</p>
                                    @endif
                                </div>
                                <button type="button" @click="open = false; selectedId = null; $wire.closeDetails()"
                                    class="rounded-lg p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors focus:outline-none">
                                    <span class="sr-only">Tutup</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Content Body -->
                            <div class="flex-1 p-6 space-y-6">
                                <!-- 1. GORGEOUS SHIMMERING SKELETON LOADER (Shown while loading details) -->
                                <div wire:loading.flex wire:target="loadCandidate" class="flex-col gap-6 w-full animate-pulse">
                                    <!-- Profile skeleton -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="h-28 bg-slate-100 rounded-2xl border border-slate-100 p-4 space-y-3">
                                            <div class="h-3.5 bg-slate-200 rounded w-1/3"></div>
                                            <div class="h-4 bg-slate-300 rounded w-2/3"></div>
                                            <div class="space-y-2 pt-2">
                                                <div class="h-3 bg-slate-200 rounded w-full"></div>
                                                <div class="h-3 bg-slate-200 rounded w-4/5"></div>
                                            </div>
                                        </div>
                                        <div class="h-28 bg-slate-100 rounded-2xl border border-slate-100 p-4 space-y-3">
                                            <div class="h-3.5 bg-slate-200 rounded w-1/3"></div>
                                            <div class="flex gap-2">
                                                <div class="h-6 bg-slate-200 rounded w-12"></div>
                                                <div class="h-6 bg-slate-200 rounded w-16"></div>
                                                <div class="h-6 bg-slate-200 rounded w-14"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Form Answers skeleton -->
                                    <div class="space-y-3">
                                        <div class="h-3 bg-slate-200 rounded w-1/4"></div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div class="h-16 bg-slate-100 rounded-xl p-3 space-y-2">
                                                <div class="h-2.5 bg-slate-200 rounded w-1/2"></div>
                                                <div class="h-3.5 bg-slate-300 rounded w-3/4"></div>
                                            </div>
                                            <div class="h-16 bg-slate-100 rounded-xl p-3 space-y-2">
                                                <div class="h-2.5 bg-slate-200 rounded w-2/3"></div>
                                                <div class="h-3.5 bg-slate-300 rounded w-1/2"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Sliders skeleton -->
                                    <div class="bg-slate-50 border border-slate-200 p-5 rounded-2xl space-y-4">
                                        <div class="h-3.5 bg-slate-200 rounded w-1/3"></div>
                                        <div class="space-y-3">
                                            <div class="h-12 bg-white rounded-xl border border-slate-100 p-3 flex flex-col justify-center gap-2">
                                                <div class="h-3 bg-slate-200 rounded w-1/2"></div>
                                                <div class="h-1.5 bg-slate-100 rounded w-full"></div>
                                            </div>
                                            <div class="h-12 bg-white rounded-xl border border-slate-100 p-3 flex flex-col justify-center gap-2">
                                                <div class="h-3 bg-slate-200 rounded w-1/3"></div>
                                                <div class="h-1.5 bg-slate-100 rounded w-full"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($selectedApplication)
                                    @php
                                        $dimensions = $stageDimensions;
                                    @endphp
                                    <div wire:loading.remove wire:target="loadCandidate" class="space-y-6">
                                        <!-- 1. Candidate Profile Info Card (At the top) -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white border border-slate-100 p-4 rounded-xl shadow-sm">
                                        <h3 class="text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2 mb-2">Info Kontak & Posisi</h3>
                                        <p class="text-sm font-semibold text-slate-800">{{ $selectedApplication->job_title }}</p>
                                        <div class="mt-2 text-xs text-slate-650 space-y-1">
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
                                
                                <!-- 2. Dynamic Form Answers -->
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

                                <!-- 3. Collaborative Recruiter Scoring (Sticky Swiper Layout) -->
                                <div class="space-y-4 pt-4 border-t border-slate-200">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Penilaian Internal ({{ count($selectedApplication->interviewScores) }})</h3>
                                    </div>

                                    @if(session()->has('rating_success'))
                                        <div class="p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-xs flex items-center space-x-2 animate-fade-in">
                                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            <span>{{ session('rating_success') }}</span>
                                        </div>
                                    @endif

                                    <!-- Feed of scores -->
                                    <div class="space-y-3">
                                        @forelse($selectedApplication->interviewScores as $score)
                                            <div class="bg-white border border-slate-100 p-3 rounded-xl flex flex-col gap-2 shadow-sm">
                                                <div class="flex gap-3">
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
                                                            <p class="text-xs text-slate-650 mt-1 italic leading-tight">"{{ $score->notes }}"</p>
                                                        @endif
                                                        <span class="text-[9px] text-slate-400 mt-1 block">{{ $score->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                                <!-- Score Breakout details -->
                                                @if($score->technical_rating || $score->communication_rating || $score->problem_solving_rating || $score->culture_fit_rating)
                                                    <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 mt-1 bg-slate-50 p-2.5 rounded-lg border border-slate-100 text-[10px]">
                                                        @php
                                                            $scoreStage = $score->stage ?: $currentStage;
                                                            $scoreDims = \App\Support\StageRubric::getDimensions($scoreStage);
                                                        @endphp
                                                        @foreach($scoreDims as $dim)
                                                            @php
                                                                $col = $dim['key'];
                                                                $val = $score->{$col} ?? 3;
                                                            @endphp
                                                            <div class="flex justify-between items-center">
                                                                <span class="text-slate-500 font-semibold">{{ $dim['label'] }}</span>
                                                                <span class="font-extrabold text-slate-800">{{ $val }}/5</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @empty
                                            <p class="text-xs text-slate-400 italic font-medium">Belum dinilai.</p>
                                        @endforelse
                                    </div>

                                    <!-- Rubric Scoring Form (Vertical Layout with custom red slider tracks) -->
                                    <form wire:submit.prevent="submitAssessment" class="bg-red-50/50 p-5 border border-red-100 rounded-2xl space-y-4 shadow-sm">
                                        <div class="border-b border-red-100/50 pb-2 flex items-center justify-between">
                                            <div>
                                                <span class="text-[9px] font-black text-red-500 uppercase tracking-widest block">Evaluasi Objektif</span>
                                                <h4 class="text-xs font-extrabold text-slate-800 tracking-tight">Rubrik Tahap {{ $currentStage }}</h4>
                                            </div>
                                        </div>

                                        <!-- Vertical list of sliders -->
                                        <div class="space-y-4">
                                            @foreach($sliderDimensions as $index => $dim)
                                                <div class="bg-white p-3.5 rounded-xl border border-red-100/30 shadow-sm flex flex-col gap-2.5" x-data="{ val: @entangle($dim['livewire_key']) }">
                                                     <div class="flex justify-between items-center text-xs">
                                                         <div>
                                                             <span class="font-extrabold text-slate-800 block text-xs">{{ $dim['label'] }}</span>
                                                             <span class="text-[10px] text-slate-450 font-medium block leading-tight mt-0.5">{{ $dim['desc'] }}</span>
                                                         </div>
                                                         <span class="px-2.5 py-1 rounded-xl bg-red-50 text-red-600 font-extrabold border border-red-100 shadow-sm shrink-0 font-mono text-xs" x-text="val"></span>
                                                     </div>
                                                     <div class="flex items-center w-full">
                                                         <input type="range" min="1" max="5" step="1" x-model="val"
                                                             class="w-full h-2 rounded-lg appearance-none cursor-pointer accent-red-500 hover:accent-red-650 transition-all"
                                                             :style="'background: linear-gradient(to right, #ef4444 0%, #ef4444 ' + ((val - 1) * 25) + '%, #e2e8f0 ' + ((val - 1) * 25) + '%, #e2e8f0 100%)'">
                                                     </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="space-y-1.5 pt-2 border-t border-red-100/50">
                                            <label class="block text-xs font-bold text-slate-700">Catatan Evaluasi</label>
                                            <textarea wire:model="ratingNotes" rows="2"
                                                class="block w-full px-3 py-2 text-xs bg-white border border-red-100 rounded-lg focus:ring-1 focus:ring-red-400 focus:border-transparent transition-all shadow-sm"
                                                placeholder="Catatan evaluasi tambahan..."></textarea>
                                            @error('ratingNotes')<p class="mt-1 text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                                        </div>

                                        <div class="flex justify-end pt-1">
                                            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r {{ $theme['gradient'] }} text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-red-500/10">
                                                Simpan Penilaian
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- 4. Kirim WhatsApp & Gmail Per-satu-satu (Di atas Log Aktivitas) -->
                                <div class="bg-red-50/40 border border-red-100 rounded-2xl p-4 sm:p-5 space-y-4 shadow-sm relative overflow-hidden">
                                    <div class="absolute -right-16 -top-16 w-36 h-36 bg-red-100/30 rounded-full blur-2xl"></div>
                                    
                                    <div class="border-b border-red-100/50 pb-2 flex justify-between items-center">
                                        <div>
                                            <span class="px-2.5 py-0.5 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest border border-red-100">Hubungi</span>
                                            <h3 class="text-xs font-extrabold text-slate-800 tracking-tight mt-1">Kirim Pesan & Undangan</h3>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <!-- Template selector -->
                                            <select wire:model.live="selectedTemplateType" class="text-[11px] font-bold border border-red-200 rounded-lg py-1 pl-2 pr-6 bg-white text-slate-700 focus:ring-red-500 shadow-sm">
                                                @foreach($templates as $key => $tpl)
                                                    <option value="{{ $key }}">{{ $tpl['label'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    @if(session()->has('notification_success'))
                                        <div class="p-2.5 rounded-xl bg-green-50 border border-green-200 text-green-700 text-xs flex items-center space-x-1.5">
                                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                            <span class="font-bold">{{ session('notification_success') }}</span>
                                        </div>
                                    @endif
                                    @if(session()->has('notification_error'))
                                        <div class="p-2.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs flex items-center space-x-1.5">
                                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                            <span class="font-bold">{{ session('notification_error') }}</span>
                                        </div>
                                    @endif

                                    <div class="space-y-3">
                                        <!-- Editable Subject (Only for Email) -->
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Subjek Email</label>
                                            <input type="text" wire:model="emailSubject" class="w-full text-xs px-3 py-2 border border-red-100 rounded-xl focus:ring-2 focus:ring-red-500 bg-white/80 shadow-sm" placeholder="Subjek email...">
                                        </div>

                                        <!-- Editable Message Body -->
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Isi Pesan (WA / Email)</label>
                                            <textarea wire:model="messageText" rows="4" class="w-full text-xs p-3 border border-red-100 rounded-xl focus:ring-2 focus:ring-red-500 bg-white/80 shadow-sm" placeholder="Tulis isi pesan..."></textarea>
                                        </div>

                                        <!-- Dispatch buttons (WhatsApp & Gmail Only) -->
                                        <div class="flex flex-wrap gap-3 pt-1.5">
                                            <!-- WhatsApp Link -->
                                            <a href="{{ $this->getWhatsappUrl($selectedApplication) }}" target="_blank"
                                                class="flex-1 min-w-[140px] inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-500/10 transition-all hover:-translate-y-0.5">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.999 0C5.373 0 0 5.373 0 12c0 2.12.554 4.107 1.523 5.832L.053 23.404a.75.75 0 00.918.918l5.572-1.47A11.943 11.943 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 11.999 0zm.001 21.75a9.714 9.714 0 01-4.963-1.362l-.357-.212-3.705.977.977-3.591-.232-.369A9.718 9.718 0 012.25 12C2.25 6.615 6.614 2.25 12 2.25S21.75 6.615 21.75 12 17.386 21.75 12 21.75z"/></svg>
                                                Hubungi via WhatsApp
                                            </a>

                                            <!-- Gmail Tab Link styled premiumly -->
                                            <a href="{{ $this->getGmailUrl($selectedApplication) }}" target="_blank"
                                                class="flex-1 min-w-[140px] inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-600/10 transition-all hover:-translate-y-0.5">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                                Hubungi via Gmail
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- 5. Tolak Kandidat & Bar Pindah Tahap (Sampingan/Bawah WhatsApp/Gmail) -->
                                <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-200 shadow-sm space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-slate-500 font-extrabold uppercase tracking-wider">Ubah Status & Tahap</span>
                                        <button wire:click="openRejectModal" class="px-4 py-2 bg-white border border-red-200 text-red-650 hover:bg-red-50 hover:border-red-300 rounded-xl text-xs font-extrabold shadow-sm transition-all flex items-center gap-1.5">
                                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Tolak Kandidat (Gagal)
                                        </button>
                                    </div>

                                    <!-- Reject Modal Overlay (Inside Drawer) -->
                                    @if($showRejectModal)
                                        <div class="bg-red-950/10 backdrop-blur-md border border-red-500/25 p-4 sm:p-5 rounded-2xl shadow-inner space-y-4 animate-fade-in text-slate-800">
                                            <div class="flex items-center gap-2 text-red-700">
                                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                <h3 class="font-extrabold text-sm">Konfirmasi Penolakan Kandidat</h3>
                                            </div>
                                            <p class="text-xs text-red-650 leading-relaxed font-semibold">Mohon berikan alasan profesional yang konstruktif. Alasan ini akan ditampilkan kepada kandidat.</p>
                                            
                                            <!-- Quick Templates -->
                                            <div class="bg-white/90 p-3 rounded-xl border border-red-200/50 space-y-2">
                                                <p class="text-[9px] font-black text-red-600 uppercase tracking-widest">Pilih Template Alasan Cepat:</p>
                                                <div class="flex flex-wrap gap-1.5">
                                                    <button type="button" wire:click="$set('rejectReason', 'Mohon maaf, profil dan kualifikasi administrasi Anda belum sesuai dengan kriteria yang kami butuhkan saat ini. Tetap semangat dan terus kembangkan potensi Anda!')" 
                                                        class="px-2.5 py-1 bg-white border border-red-200 text-red-700 hover:bg-red-50 hover:border-red-300 rounded-lg text-[9px] font-bold transition-all">
                                                        Gagal Berkas
                                                    </button>
                                                    <button type="button" wire:click="$set('rejectReason', 'Terima kasih atas waktu Anda. Berdasarkan hasil Psikotes yang telah Anda ikuti, kami mohon maaf belum bisa meloloskan Anda ke tahap selanjutnya. Tetap semangat!')" 
                                                        class="px-2.5 py-1 bg-white border border-red-200 text-red-700 hover:bg-red-50 hover:border-red-300 rounded-lg text-[9px] font-bold transition-all">
                                                        Gagal Psikotes
                                                    </button>
                                                    <button type="button" wire:click="$set('rejectReason', 'Terima kasih telah mengikuti sesi interview. Sayangnya, untuk saat ini kami memutuskan untuk melangkah dengan kandidat lain yang lebih sesuai dengan kebutuhan spesifik kami.')" 
                                                        class="px-2.5 py-1 bg-white border border-red-200 text-red-700 hover:bg-red-50 hover:border-red-300 rounded-lg text-[9px] font-bold transition-all">
                                                        Gagal Wawancara
                                                    </button>
                                                    <button type="button" wire:click="$set('rejectReason', 'Terima kasih atas partisipasi Anda. Berdasarkan hasil evaluasi lanjutan dan MCU, dengan berat hati kami belum dapat melanjutkan proses rekrutmen Anda.')" 
                                                        class="px-2.5 py-1 bg-white border border-red-200 text-red-700 hover:bg-red-50 hover:border-red-300 rounded-lg text-[9px] font-bold transition-all">
                                                        Gagal MCU
                                                    </button>
                                                </div>
                                            </div>

                                            <textarea wire:model="rejectReason" rows="3" class="w-full text-xs p-3 border border-red-200 rounded-xl focus:ring-red-500 focus:border-red-500 bg-white shadow-sm" placeholder="Atau ketik alasan kustom Anda sendiri di sini..."></textarea>
                                            @error('rejectReason')<span class="text-xs text-red-650 font-bold block">{{ $message }}</span>@enderror

                                            <div class="flex justify-end gap-2 pt-1">
                                                <button type="button" wire:click="closeRejectModal" class="px-4 py-1.5 bg-white border border-slate-355 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition-colors shadow-sm">Batal</button>
                                                <button type="button" wire:click="rejectCandidate" class="px-5 py-1.5 bg-gradient-to-r from-red-600 to-red-600 hover:from-red-700 hover:to-red-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-red-500/10">Kirim Penolakan</button>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="space-y-2">
                                        <span class="block text-[10px] font-black uppercase tracking-wider text-slate-400">Pindahkan ke Tahap Lain</span>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach(['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired'] as $targetStage)
                                                @php
                                                    $isCurrent = $selectedApplication->status === $targetStage;
                                                    $btnTheme = match($targetStage) {
                                                        'Administrasi' => $isCurrent ? 'bg-blue-600 text-white border-transparent shadow-blue-500/20' : 'bg-white text-blue-600 border-slate-200 hover:border-blue-300',
                                                        'Psikotes' => $isCurrent ? 'bg-red-600 text-white border-transparent shadow-red-500/20' : 'bg-white text-red-600 border-slate-200 hover:border-red-300',
                                                        'Interview' => $isCurrent ? 'bg-amber-600 text-white border-transparent shadow-amber-500/20' : 'bg-white text-amber-600 border-slate-200 hover:border-amber-300',
                                                        'MCU' => $isCurrent ? 'bg-purple-600 text-white border-transparent shadow-purple-500/20' : 'bg-white text-purple-600 border-slate-200 hover:border-purple-300',
                                                        'Hired' => $isCurrent ? 'bg-emerald-600 text-white border-transparent shadow-emerald-500/20' : 'bg-white text-emerald-600 border-slate-200 hover:border-emerald-300',
                                                    };
                                                @endphp
                                                <button wire:click="changeStatus({{ $selectedApplication->id }}, '{{ $targetStage }}')"
                                                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all border shadow-sm hover:shadow {{ $btnTheme }}">
                                                    {{ $targetStage }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- 6. Recruiter Activity Logs (Audit Trail at the absolute bottom) -->
                                @if($selectedApplication->activityLogs->count() > 0)
                                    <div class="space-y-3 pt-4 border-t border-slate-200">
                                        <h3 class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Log Aktivitas Rekruter</h3>
                                        <div class="space-y-2 bg-slate-50 p-3 rounded-xl border border-slate-100 max-h-40 overflow-y-auto">
                                            @foreach($selectedApplication->activityLogs as $log)
                                                <div class="flex items-start justify-between text-[11px] leading-tight py-1 border-b border-slate-100/50 last:border-0">
                                                    <div>
                                                        <span class="font-bold text-slate-700">{{ $log->action }}</span>
                                                        <p class="text-slate-500 mt-0.5">{{ $log->description }}</p>
                                                    </div>
                                                    <span class="text-[9px] text-slate-400 shrink-0 ml-2">{{ $log->created_at->diffForHumans() }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
