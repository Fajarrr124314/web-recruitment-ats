<div>
    @if($floatingReminder)
        <!-- Premium Glossy Backdrop Blur Pop-up Modal -->
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="fixed inset-0 z-[1000] flex items-center justify-center p-4">
            <!-- Glassmorphic Backdrop overlay -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-md" @click="show = false"></div>
            
            <!-- Glossy Modal Box -->
            <div class="relative w-full max-w-md bg-white/75 backdrop-blur-2xl border border-white/60 p-6 rounded-3xl shadow-2xl shadow-slate-950/20 overflow-hidden animate-scale-up">
                <!-- Decorative top color bar -->
                <div class="absolute left-[1px] right-[1px] top-[1px] h-[3.5px] bg-gradient-to-r from-red-500 to-orange-500 rounded-t-[22px]"></div>
                <!-- Shimmer background grid -->
                <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,87,87,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,87,87,0.03)_1px,transparent_1px)] bg-[size:16px_16px] pointer-events-none"></div>

                <!-- Modal Content -->
                <div class="relative z-10 flex flex-col items-center text-center">
                    <!-- Premium calendar SVG -->
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500/10 to-orange-500/10 border border-red-200/50 rounded-2xl flex items-center justify-center text-red-600 mb-4 shadow-inner">
                        <svg class="w-8 h-8 animate-[bounce_4s_infinite]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>

                    <span class="text-[10px] font-black text-red-500 uppercase tracking-widest bg-red-50 border border-red-200/40 px-3 py-1 rounded-full mb-2 shadow-sm">
                        Agenda & Jadwal Hari Ini
                    </span>

                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight mb-2">Notifikasi Pengingat</h3>
                    <p class="text-xs font-bold text-slate-600 leading-relaxed mb-6 bg-slate-50/50 border border-slate-100 p-4 rounded-xl shadow-inner w-full">
                        {{ $floatingReminder }}
                    </p>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3 w-full">
                        <button @click="show = false" class="flex-1 px-5 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white rounded-xl text-xs font-black shadow-md shadow-red-500/10 hover:shadow-lg transition-all">
                            Mengerti
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-8 flex flex-col items-center">
        <button type="button" wire:click="$toggle('showCalendar')" 
            class="px-6 py-3 bg-white border border-red-100 hover:border-red-300 rounded-2xl text-xs font-extrabold text-red-650 hover:bg-red-50/50 shadow-sm transition-all duration-300 flex items-center gap-2 select-none">
            <svg class="w-4 h-4 text-red-500 transition-transform duration-300 {{ $showCalendar ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>{{ $showCalendar ? 'Sembunyikan Kalender & Agenda' : 'Tampilkan Kalender & Agenda HRD' }}</span>
        </button>

        @if($showCalendar)
            @php
                $firstDayOfMonth = \Carbon\Carbon::create($calendarYear, $calendarMonth, 1);
                $daysInMonth = $firstDayOfMonth->daysInMonth;
                $startOfWeek = $firstDayOfMonth->dayOfWeek; // 0 for Sunday, 1 for Monday, etc.
                $offset = $startOfWeek === 0 ? 6 : $startOfWeek - 1;
                
                $prevMonthDate = $firstDayOfMonth->copy()->subMonth();
                $daysInPrevMonth = $prevMonthDate->daysInMonth;
                
                $monthName = $firstDayOfMonth->translatedFormat('F Y');
            @endphp
            <div class="w-full mt-6 bg-white border border-red-100 rounded-3xl p-6 sm:p-8 shadow-xl shadow-red-100/5 transition-all duration-300 animate-fade-in">
                <div class="border-b border-red-50 pb-4 mb-6">
                    <span class="px-3 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest border border-red-100">Kalender Interaktif</span>
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight mt-2">Agenda & Catatan HRD</h3>
                    <p class="text-slate-500 text-xs mt-1">Klik pada tanggal untuk melihat atau menambahkan catatan agenda seleksi/interview.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left: Calendar Grid (lg:col-span-2) -->
                    <div class="lg:col-span-2 space-y-4">
                        <!-- Calendar Header Controls -->
                        <div class="flex items-center justify-between bg-slate-50 p-3 rounded-2xl border border-slate-100">
                            <button type="button" wire:click="prevMonth" class="p-2 bg-white border border-slate-200 rounded-xl hover:border-red-300 hover:text-red-600 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                            </button>
                            <span class="font-extrabold text-slate-700 uppercase tracking-wider text-sm">{{ $monthName }}</span>
                            <button type="button" wire:click="nextMonth" class="p-2 bg-white border border-slate-200 rounded-xl hover:border-red-300 hover:text-red-600 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </button>
                        </div>

                        <!-- Days Grid -->
                        <div class="grid grid-cols-7 gap-2">
                            @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $dayName)
                                <div class="text-center text-xs font-extrabold text-slate-400 py-1 uppercase tracking-wider">{{ $dayName }}</div>
                            @endforeach
                            
                            <!-- Prev Month Days -->
                            @for($i = $offset - 1; $i >= 0; $i--)
                                @php $day = $daysInPrevMonth - $i; @endphp
                                <div class="h-14 border border-slate-100 rounded-2xl bg-slate-50/50 flex items-center justify-center text-xs text-slate-300 select-none">
                                    {{ $day }}
                                </div>
                            @endfor
                            
                            <!-- Current Month Days -->
                            @for($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $dateStr = sprintf('%04d-%02d-%02d', $calendarYear, $calendarMonth, $day);
                                    $isToday = $dateStr === now('Asia/Jakarta')->format('Y-m-d');
                                    $isSelected = $dateStr === $selectedDate;
                                    $hasNotes = isset($notes[$dateStr]) && count($notes[$dateStr]) > 0;
                                @endphp
                                <button type="button" wire:click="selectCalendarDate('{{ $dateStr }}')"
                                    class="h-14 border rounded-2xl flex flex-col items-center justify-between p-2 transition-all relative overflow-hidden group
                                        {{ $isSelected ? 'border-red-500 bg-red-50/80 shadow-md ring-1 ring-red-400' : 'border-slate-200 bg-white hover:border-red-300' }}
                                        {{ $isToday ? 'ring-2 ring-indigo-500 border-indigo-500' : '' }}">
                                    
                                    <span class="text-xs font-extrabold {{ $isSelected ? 'text-red-600 font-black' : ($isToday ? 'text-indigo-600' : 'text-slate-700') }}">{{ $day }}</span>
                                    
                                    @if($hasNotes)
                                        <span class="w-2 h-2 rounded-full bg-red-500 shadow-lg shadow-red-500/50 animate-pulse"></span>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-transparent"></span>
                                    @endif
                                </button>
                            @endfor
                            
                            <!-- Next Month Days -->
                            @php
                                $totalCells = $offset + $daysInMonth;
                                $nextMonthDays = 42 - $totalCells;
                                if ($nextMonthDays >= 7) {
                                    $nextMonthDays -= 7;
                                }
                            @endphp
                            @for($day = 1; $day <= $nextMonthDays; $day++)
                                <div class="h-14 border border-slate-100 rounded-2xl bg-slate-50/50 flex items-center justify-center text-xs text-slate-300 select-none">
                                    {{ $day }}
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Right: Notes Panel (lg:col-span-1) -->
                    <div class="lg:col-span-1">
                        @if($selectedDate)
                            @php
                                $dateFormatted = \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y');
                                $dayNotes = $notes[$selectedDate] ?? [];
                            @endphp
                            <div class="space-y-4">
                                <!-- Selected Date Header -->
                                <div class="p-4 bg-gradient-to-r from-red-50 to-orange-50 rounded-2xl border border-red-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-[9px] font-black text-red-500 uppercase tracking-widest block">Agenda Tanggal</span>
                                        <span class="text-sm font-extrabold text-slate-800">{{ $dateFormatted }}</span>
                                    </div>
                                    <button type="button" wire:click="$set('selectedDate', '')" class="text-slate-400 hover:text-slate-600 transition-colors p-1 bg-white border border-slate-200 rounded-lg shadow-sm">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>

                                <!-- List Notes of selected date -->
                                <div class="space-y-2">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Catatan & Agenda Hari Ini</span>
                                    @forelse($dayNotes as $index => $note)
                                        <div class="p-3 bg-white border border-slate-200 rounded-xl flex justify-between items-center shadow-sm">
                                            <p class="text-xs text-slate-700 font-semibold leading-relaxed flex-1 pr-3">{{ $note }}</p>
                                            <button type="button" wire:click="removeCalendarNote('{{ $selectedDate }}', {{ $index }})" class="text-red-500 hover:text-red-700 p-1.5 rounded-lg bg-red-50 hover:bg-red-100 transition-all shrink-0">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400 italic py-2 text-center bg-slate-50 rounded-xl border border-slate-100">Belum ada agenda seleksi.</p>
                                    @endforelse
                                </div>

                                <!-- Add New Note Form -->
                                <div class="pt-4 border-t border-slate-100 space-y-3">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Tambah Catatan Agenda</span>
                                    <div class="flex gap-2">
                                        <input type="text" wire:model="newNoteText" placeholder="Contoh: Interview Operator"
                                            class="flex-1 px-3 py-2 text-xs border border-red-200 rounded-xl focus:ring-red-500 focus:border-red-500 shadow-sm transition-all"
                                            wire:keydown.enter="addCalendarNote">
                                        <button type="button" wire:click="addCalendarNote" class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-600 hover:from-red-700 hover:to-red-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-red-500/10">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- No Date Selected Panel -->
                            <div class="h-full flex flex-col items-center justify-center p-8 border border-slate-200 border-dashed rounded-3xl bg-slate-50/50 text-center">
                                <svg class="w-10 h-10 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-xs font-bold text-slate-600">Pilih Tanggal Kalender</span>
                                <p class="text-[10px] text-slate-400 mt-1 max-w-[200px]">Silakan klik tanggal pada kalender untuk melihat detail agenda atau menambahkan jadwal baru.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
