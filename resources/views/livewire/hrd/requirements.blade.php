<div class="space-y-10">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Persiapan Lamaran</h1>
        <p class="mt-1 text-sm text-slate-500">Atur posisi lowongan yang dibuka dan persyaratan dokumen/jawaban untuk kandidat.</p>
    </div>

    <!-- Job Positions Section -->
    <div>
        <h2 class="text-xl font-bold text-slate-800 mb-4 border-b border-red-100 pb-2">1. Pengaturan Posisi Lowongan</h2>
        
        @if (session()->has('success_job'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                {{ session('success_job') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Add Job -->
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6 h-fit">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Tambah Posisi</h3>
                <form wire:submit.prevent="addJobPosition" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nama Posisi</label>
                        <input type="text" wire:model="job_title" class="mt-1 w-full px-3 py-2 border border-red-200 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Contoh: Laravel Developer">
                        @error('job_title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-xl text-white bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-md font-medium text-sm transition-all">
                        Tambah Posisi
                    </button>
                </form>
            </div>

            <!-- List Jobs -->
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($jobPositions as $job)
                    <div class="bg-white p-4 rounded-2xl shadow-sm border border-red-100 flex items-center justify-between transition-all hover:shadow-md h-fit">
                        <div>
                            <span class="font-bold text-slate-800 text-sm">{{ $job->title }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="toggleJobActive({{ $job->id }})" class="text-xs px-2 py-1 rounded-lg border {{ $job->is_active ? 'border-green-200 text-green-600 bg-green-50' : 'border-slate-200 text-slate-500 bg-slate-50' }}">
                                {{ $job->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                            <button wire:click="deleteJobPosition({{ $job->id }})" class="text-rose-500 hover:text-rose-700 p-1 hover:bg-rose-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-6 text-sm text-slate-500 italic bg-white/50 rounded-2xl border border-red-100 border-dashed">
                        Belum ada posisi lowongan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Requirements Section -->
    <div>
        <h2 class="text-xl font-bold text-slate-800 mb-4 border-b border-red-100 pb-2">2. Pengaturan Persyaratan / Pertanyaan</h2>
        
        @if (session()->has('success_req'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                {{ session('success_req') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Add Req -->
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6 h-fit">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Tambah Persyaratan</h3>
                
                <form wire:submit.prevent="addRequirement" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Pertanyaan / Label</label>
                        <input type="text" wire:model="question" class="mt-1 w-full px-3 py-2 border border-red-200 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm" placeholder="Contoh: Upload KTP">
                        @error('question') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tipe Input</label>
                        <select wire:model="type" class="mt-1 w-full px-3 py-2 border border-red-200 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm">
                            <option value="text">Teks Singkat</option>
                            <option value="textarea">Teks Panjang (Paragraf)</option>
                            <option value="file">Upload File (PDF/Image)</option>
                        </select>
                        @error('type') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_required" id="is_required" class="rounded border-red-300 text-red-600 focus:ring-red-500 h-4 w-4">
                        <label for="is_required" class="ml-2 text-sm text-slate-700">Wajib Diisi</label>
                    </div>

                    <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-xl text-white bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-md font-medium text-sm transition-all">
                        Tambah Persyaratan
                    </button>
                </form>
            </div>

            <!-- List Reqs -->
            <div class="lg:col-span-2 space-y-4">
                @forelse($requirements as $req)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-red-100 flex items-center justify-between transition-all hover:shadow-md">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-slate-800">{{ $req->question }}</span>
                                @if($req->is_required)
                                    <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Wajib</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 capitalize flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    @if($req->type === 'file')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    @endif
                                </svg>
                                Tipe: {{ $req->type }}
                            </p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <button wire:click="toggleActive({{ $req->id }})" class="text-sm px-3 py-1 rounded-lg border {{ $req->is_active ? 'border-green-200 text-green-600 bg-green-50' : 'border-slate-200 text-slate-500 bg-slate-50' }}">
                                {{ $req->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                            <button wire:click="deleteRequirement({{ $req->id }})" class="text-rose-500 hover:text-rose-700 p-2 hover:bg-rose-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white/50 rounded-2xl border border-red-100 border-dashed">
                        <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">Belum ada persyaratan</h3>
                        <p class="mt-1 text-sm text-slate-500">Silakan tambahkan persyaratan baru di form sebelah kiri.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
