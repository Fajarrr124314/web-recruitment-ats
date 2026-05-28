<?php

namespace App\Livewire\Hrd;

use App\Models\Application;
use App\Models\InterviewScore;
use App\Models\JobPosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class CandidateStage extends Component
{
    use WithPagination;

    public string $stage = ''; // Active stage name (mapped from route)
    
    public array $templates = [
        'psikotes' => [
            'label' => 'Panggilan Psikotes',
            'subject' => 'Undangan Tahap Psikotes - [Nama Perusahaan]',
            'body' => "Halo [Nama Kandidat],\n\nBerdasarkan hasil seleksi berkas Anda untuk posisi [Posisi Dilamar] di [Nama Perusahaan], kami ingin mengundang Anda untuk mengikuti tahap seleksi Psikotes secara online.\n\nDetail pengerjaan Psikotes dan jadwal akan kami informasikan lebih lanjut. Mohon konfirmasi kesediaan Anda dengan membalas pesan ini.\n\nSalam hangat,\nTim Rekrutmen [Nama Perusahaan]"
        ],
        'interview' => [
            'label' => 'Panggilan Wawancara',
            'subject' => 'Undangan Sesi Wawancara (Interview) - [Nama Perusahaan]',
            'body' => "Halo [Nama Kandidat],\n\nKami dari [Nama Perusahaan] mengundang Anda untuk mengikuti tahap Wawancara (Interview) terkait lamaran Anda untuk posisi [Posisi Dilamar].\n\nSesi interview direncanakan akan dilakukan secara daring/luring. Mohon konfirmasi kehadiran Anda untuk pengaturan jadwal lebih lanjut.\n\nSalam hangat,\nTim Rekrutmen [Nama Perusahaan]"
        ],
        'mcu' => [
            'label' => 'Panggilan MCU',
            'subject' => 'Undangan Medical Check Up (MCU) - [Nama Perusahaan]',
            'body' => "Halo [Nama Kandidat],\n\nSelamat! Anda telah dinyatakan lolos tahap wawancara untuk posisi [Posisi Dilamar] di [Nama Perusahaan]. Kami mengundang Anda untuk mengikuti tahap akhir seleksi yaitu Medical Check Up (MCU).\n\nInstruksi dan rujukan laboratorium akan kami kirimkan sesegera mungkin. Mohon konfirmasi kesediaan Anda.\n\nSalam hangat,\nTim Rekrutmen [Nama Perusahaan]"
        ],
        'umum' => [
            'label' => 'Informasi Umum',
            'subject' => 'Pembaruan Lamaran Kerja - [Nama Perusahaan]',
            'body' => "Halo [Nama Kandidat],\n\nKami ingin menginformasikan kelanjutan proses rekrutmen Anda untuk posisi [Posisi Dilamar] di [Nama Perusahaan].\n\nUntuk koordinasi lebih lanjut, silakan hubungi tim kami atau balas pesan ini.\n\nSalam hangat,\nTim Rekrutmen [Nama Perusahaan]"
        ],
    ];

    // Bulk Notifications State
    public bool $showBulkModal = false;
    public string $bulkTemplateType = 'umum';
    public string $bulkMessageText = '';
    public string $bulkEmailSubject = '';
    public array $sentWhatsappIds = [];

    // Gmail Compose State
    public bool $showGmailCompose = false;
    public string $gmailBcc = '';
    public string $gmailSubject = '';
    public string $gmailBody = '';
    public string $gmailTemplateType = 'umum';

    // Bulk Reject Modal State
    public bool $showBulkRejectModal = false;
    public string $bulkRejectReason = '';

    // Bulk Action State
    public array $selectedApplications = [];

    // Filter & Pagination States
    public string $search = '';
    public string $jobTitleFilter = '';
    public string $sortBy = 'latest'; // 'latest' or 'rating_desc'
    public int $limit = 20;

    // Auto-Screening & Matching Engine State
    public bool $enableScreening = false;
    public string $filterSkill = '';
    public string $filterEducation = '';
    public string $filterExperience = '';

    public function mount(string $stage)
    {
        $user = Auth::user();
        if (!$user || !$user->isHrd()) {
            return redirect()->route('login');
        }

        $allowedStages = [
            'administrasi' => 'Administrasi',
            'psikotes' => 'Psikotes',
            'interview' => 'Interview',
            'mcu' => 'MCU',
        ];

        $stageSlug = strtolower($stage);
        if (!array_key_exists($stageSlug, $allowedStages)) {
            abort(404);
        }

        $this->stage = $allowedStages[$stageSlug];
    }

    public function updatedSearch() { $this->resetLimit(); }
    public function updatedJobTitleFilter() { $this->resetLimit(); }
    public function updatedFilterSkill() { $this->resetLimit(); }
    public function updatedFilterEducation() { $this->resetLimit(); }
    public function updatedFilterExperience() { $this->resetLimit(); }
    public function updatedEnableScreening() { $this->resetLimit(); }

    private function logActivity(int $appId, string $action, string $description)
    {
        \App\Models\RecruiterActivityLog::create([
            'user_id' => Auth::id(),
            'application_id' => $appId,
            'action' => $action,
            'description' => $description,
        ]);
    }

    public function calculateMatchScore($app): int
    {
        $score = 0;
        $maxScore = 0;

        // 1. Skill Match (40% Weight)
        if ($this->filterSkill) {
            $maxScore += 40;
            $skillLower = strtolower($this->filterSkill);
            $candidateSkills = array_map('strtolower', $app->candidate->skills ?? []);
            
            $matched = false;
            foreach ($candidateSkills as $cSkill) {
                if (str_contains($cSkill, $skillLower)) {
                    $matched = true;
                    break;
                }
            }
            
            if (!$matched && $app->candidate->work_history) {
                if (str_contains(strtolower($app->candidate->work_history), $skillLower)) {
                    $matched = true;
                }
            }

            if ($matched) {
                $score += 40;
            }
        }

        // 2. Education Match (30% Weight)
        if ($this->filterEducation) {
            $maxScore += 30;
            $eduLower = strtolower($this->filterEducation);
            $eduAnswer = $app->answers->first(fn($ans) => str_contains(strtolower($ans->requirement->question ?? ''), 'pendidikan'));
            
            if ($eduAnswer && str_contains(strtolower($eduAnswer->answer), $eduLower)) {
                $score += 30;
            }
        }

        // 3. Experience Match (30% Weight)
        if ($this->filterExperience) {
            $maxScore += 30;
            $minExp = (int)$this->filterExperience;
            $expAnswer = $app->answers->first(fn($ans) => str_contains(strtolower($ans->requirement->question ?? ''), 'pengalaman'));
            
            $years = 0;
            if ($expAnswer) {
                preg_match('/\d+/', $expAnswer->answer, $matches);
                if (!empty($matches)) {
                    $years = (int)$matches[0];
                }
            }
            if ($years === 0 && $app->candidate->work_history) {
                preg_match('/(\d+)\s*tahun/i', $app->candidate->work_history, $matches);
                if (!empty($matches)) {
                    $years = (int)$matches[1];
                }
            }

            if ($years >= $minExp) {
                $score += 30;
            }
        }

        return $maxScore > 0 ? (int)round(($score / $maxScore) * 100) : 100;
    }
    
    public function resetLimit()
    {
        $this->limit = 20;
    }

    public function loadMore()
    {
        $this->limit += 20;
    }

    public function selectApplication(int $id)
    {
        $this->dispatch('show-candidate-details', applicationId: $id, stage: $this->stage);
    }

    #[On('refresh-board')]
    public function refreshBoard(?string $stageSuccess = null)
    {
        if ($stageSuccess) {
            session()->flash('stage_success', $stageSuccess);
        }
    }

    public function changeStatus(int $id, string $newStatus)
    {
        $allowedStatuses = ['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired'];
        if (!in_array($newStatus, $allowedStatuses)) {
            return;
        }
        $application = Application::find($id);
        if ($application) {
            $application->update([
                'status' => $newStatus,
                'rejection_reason' => null,
                'rejected_at' => null,
            ]);
            $this->logActivity($id, 'status_change', "Memindahkan status kandidat ke: {$newStatus}");
            session()->flash('stage_success', "Status {$application->candidate->user->name} dipindahkan ke {$newStatus}");
            
            // If moved, deselect from current page
            $this->selectedApplications = array_diff($this->selectedApplications, [$id]);
        }
    }

    public function deleteApplication(int $id)
    {
        $app = Application::find($id);
        if ($app) {
            $name = $app->candidate->user->name;
            $app->update(['is_archived' => true]);
            $this->logActivity($id, 'deleted', "Mengarsipkan data kandidat {$name} (dihapus dari papan)");
            session()->flash('stage_success', "Data kandidat {$name} telah diarsipkan.");
            if ($this->selectedApplicationId === $id) {
                $this->closeDetails();
            }
            // Remove from selection if deleted
            $this->selectedApplications = array_diff($this->selectedApplications, [$id]);
        }
    }

    public function clearSelection()
    {
        $this->selectedApplications = [];
    }

    public function toggleSelectAll()
    {
        // Get all candidate IDs currently matching active filters in this stage
        $stageIds = Application::where('status', $this->stage)
            ->where('status', '!=', 'Draft')
            ->when($this->jobTitleFilter, function($q) {
                $q->where('job_title', $this->jobTitleFilter);
            })
            ->when($this->search, function($q) {
                $q->whereHas('candidate.user', function($uq) {
                    $uq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->pluck('id')
            ->toArray();

        if (empty($stageIds)) {
            return;
        }

        // Check if all are selected
        $alreadySelected = array_intersect($stageIds, $this->selectedApplications);
        if (count($alreadySelected) === count($stageIds)) {
            // Deselect all
            $this->selectedApplications = array_diff($this->selectedApplications, $stageIds);
        } else {
            // Select all
            $this->selectedApplications = array_unique(array_merge($this->selectedApplications, $stageIds));
        }
    }

    public function bulkChangeStatus(string $newStatus)
    {
        $allowedStatuses = ['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired', 'Ditolak'];
        if (!in_array($newStatus, $allowedStatuses) || empty($this->selectedApplications)) {
            return;
        }

        if ($newStatus === 'Ditolak') {
            $this->showBulkRejectModal = true;
            $this->bulkRejectReason = 'Mohon maaf, profil dan kualifikasi administrasi Anda belum sesuai dengan kriteria yang kami butuhkan saat ini. Tetap semangat!';
            return;
        }

        foreach ($this->selectedApplications as $appId) {
            $this->logActivity($appId, 'status_change', "Memindahkan status kandidat secara massal ke: {$newStatus}");
        }

        Application::whereIn('id', $this->selectedApplications)->update([
            'status' => $newStatus,
            'rejection_reason' => null,
            'rejected_at' => null,
        ]);

        session()->flash('stage_success', count($this->selectedApplications) . " kandidat berhasil dipindahkan serentak ke {$newStatus}.");
        $this->selectedApplications = [];
    }

    public function confirmBulkReject()
    {
        $this->validate([
            'bulkRejectReason' => 'required|string|min:5'
        ]);

        foreach ($this->selectedApplications as $appId) {
            $this->logActivity($appId, 'rejected', "Menolak kandidat secara massal dengan alasan: \"{$this->bulkRejectReason}\"");
        }

        Application::whereIn('id', $this->selectedApplications)->update([
            'status' => 'Ditolak',
            'rejection_reason' => $this->bulkRejectReason,
            'rejected_at' => now(),
        ]);

        $count = count($this->selectedApplications);
        session()->flash('stage_success', "{$count} kandidat berhasil ditolak serentak dengan alasan yang ditentukan.");
        $this->showBulkRejectModal = false;
        $this->selectedApplications = [];
    }

    public function closeBulkRejectModal()
    {
        $this->showBulkRejectModal = false;
    }

    public function bulkDelete()
    {
        if (empty($this->selectedApplications)) {
            return;
        }

        $count = count($this->selectedApplications);
        
        foreach ($this->selectedApplications as $appId) {
            $app = Application::find($appId);
            if ($app) {
                $this->logActivity($appId, 'deleted', "Mengarsipkan data kandidat {$app->candidate->user->name} secara massal");
            }
        }

        Application::whereIn('id', $this->selectedApplications)->update(['is_archived' => true]);

        session()->flash('stage_success', "{$count} data kandidat berhasil diarsipkan secara massal.");
        $this->selectedApplications = [];
    }

    public function openBulkReject()
    {
        if (empty($this->selectedApplications)) {
            return;
        }
        $this->showBulkRejectModal = true;
        if (empty($this->bulkRejectReason)) {
            $this->bulkRejectReason = 'Mohon maaf, profil dan kualifikasi administrasi Anda belum sesuai dengan kriteria yang kami butuhkan saat ini. Tetap semangat!';
        }
    }

    public function getSingleCandidateEmail(Application $app): string
    {
        $emailAnswer = $app->answers
            ->first(fn($ans) => stripos($ans->requirement->question ?? '', 'email') !== false);

        if ($emailAnswer && filter_var(trim($emailAnswer->answer), FILTER_VALIDATE_EMAIL)) {
            return trim($emailAnswer->answer);
        }

        return $app->candidate?->user?->email ?? '';
    }

    public function parsePlaceholders(string $text, Application $app): string
    {
        $candidateName = $app->candidate->user->name;
        $jobTitle = $app->job_title;
        $companyName = $app->company_name ?? config('app.name', 'Perusahaan');
        
        $search = ['[Nama Kandidat]', '[Posisi Dilamar]', '[Nama Perusahaan]'];
        $replace = [$candidateName, $jobTitle, $companyName];
        
        return str_replace($search, $replace, $text);
    }

    public function getWhatsappUrl(Application $application, ?string $customMessage = null): string
    {
        $phone = $application->candidate->phone ?? '';
        $phoneClean = preg_replace('/[^0-9]/', '', $phone);
        if (strpos($phoneClean, '0') === 0) {
            $phoneClean = '62' . substr($phoneClean, 1);
        }
        
        $msg = $customMessage ?? $this->bulkMessageText;
        if (empty($msg)) {
            $tpl = $this->templates[$this->bulkTemplateType] ?? $this->templates['umum'];
            $msg = $this->parsePlaceholders($tpl['body'], $application);
        } else {
            $msg = $this->parsePlaceholders($msg, $application);
        }
        
        return 'https://wa.me/' . $phoneClean . '?text=' . urlencode($msg);
    }


    public function openBulkModal()
    {
        if (empty($this->selectedApplications)) {
            return;
        }
        
        $this->showBulkModal = true;
        $this->bulkTemplateType = 'umum';
        $this->sentWhatsappIds = [];
        
        $tpl = $this->templates['umum'];
        $this->bulkMessageText = $tpl['body'];
        $this->bulkEmailSubject = $tpl['subject'];
    }

    public function closeBulkModal()
    {
        $this->showBulkModal = false;
    }

    public function updatedBulkTemplateType()
    {
        $tpl = $this->templates[$this->bulkTemplateType] ?? $this->templates['umum'];
        $this->bulkMessageText = $tpl['body'];
        $this->bulkEmailSubject = $tpl['subject'];
    }

    public function markWhatsappAsSent(int $appId)
    {
        if (!in_array($appId, $this->sentWhatsappIds)) {
            $this->sentWhatsappIds[] = $appId;
        }
    }

    public function sendBulkEmails()
    {
        if (empty($this->selectedApplications)) {
            return;
        }

        $this->validate([
            'bulkMessageText' => 'required|string',
            'bulkEmailSubject' => 'required|string',
        ]);

        $successCount = 0;
        $failCount = 0;

        foreach ($this->selectedApplications as $appId) {
            $app = Application::with(['candidate.user', 'answers.requirement'])->find($appId);
            if ($app) {
                try {
                    $email = $this->getSingleCandidateEmail($app);
                    $parsedSubject = $this->parsePlaceholders($this->bulkEmailSubject, $app);
                    $parsedBody = $this->parsePlaceholders($this->bulkMessageText, $app);

                    Mail::raw($parsedBody, function ($message) use ($email, $parsedSubject) {
                        $message->to($email)
                            ->subject($parsedSubject);
                    });
                    $successCount++;
                } catch (\Exception $e) {
                    $failCount++;
                }
            }
        }

        $this->showBulkModal = false;
        $this->selectedApplications = [];
        
        if ($failCount > 0) {
            session()->flash('stage_success', "Berhasil mengirim email massal ke {$successCount} kandidat. (Gagal: {$failCount})");
        } else {
            session()->flash('stage_success', "Berhasil mengirim email massal ke {$successCount} kandidat secara serentak!");
        }
    }

    public function openGmailCompose()
    {
        if (empty($this->selectedApplications)) {
            return;
        }

        $this->showGmailCompose = true;
        $this->gmailTemplateType = 'umum';
        
        // Ambil email dari jawaban "email aktif" di form lamaran, fallback ke email akun
        $selectedEmails = Application::whereIn('id', $this->selectedApplications)
            ->with(['candidate.user', 'answers.requirement'])
            ->get()
            ->map(function ($app) {
                // Cari jawaban yang pertanyaannya mengandung kata 'email'
                $emailAnswer = $app->answers
                    ->first(fn($ans) => stripos($ans->requirement->question ?? '', 'email') !== false);

                if ($emailAnswer && filter_var(trim($emailAnswer->answer), FILTER_VALIDATE_EMAIL)) {
                    return trim($emailAnswer->answer);
                }

                // Fallback ke email akun user
                return $app->candidate?->user?->email;
            })
            ->filter()
            ->unique()
            ->toArray();
            
        $this->gmailBcc = implode(', ', $selectedEmails);
        
        $tpl = $this->templates['umum'];
        $this->gmailSubject = $tpl['subject'];
        $this->gmailBody = $tpl['body'];
    }

    public function closeGmailCompose()
    {
        $this->showGmailCompose = false;
    }

    public function updatedGmailTemplateType()
    {
        $tpl = $this->templates[$this->gmailTemplateType] ?? $this->templates['umum'];
        $this->gmailSubject = $tpl['subject'];
        $this->gmailBody = $tpl['body'];
    }

    public function sendGmailBulk()
    {
        if (empty($this->selectedApplications)) {
            return;
        }

        $this->validate([
            'gmailBcc' => 'required|string',
            'gmailSubject' => 'required|string',
            'gmailBody' => 'required|string',
        ], [
            'gmailBcc.required' => 'Daftar email penerima tidak boleh kosong.',
            'gmailSubject.required' => 'Subjek email tidak boleh kosong.',
            'gmailBody.required' => 'Isi pesan email tidak boleh kosong.',
        ]);

        // Construct Google Gmail Compose url with pre-filled BCC, Subject and Body
        $url = 'https://mail.google.com/mail/?view=cm&fs=1'
            . '&bcc=' . urlencode($this->gmailBcc)
            . '&su=' . urlencode($this->gmailSubject)
            . '&body=' . urlencode($this->gmailBody);

        // Close compose modal and reset selected applications
        $this->showGmailCompose = false;
        $this->selectedApplications = [];

        // Redirect directly in the SAME page!
        return redirect()->away($url);
    }

    public function setSortBy(string $sort)
    {
        $this->sortBy = $sort;
    }

    public function render()
    {
        // Query
        $query = Application::with(['candidate.user', 'interviewScores.interviewer'])
            ->withAvg('interviewScores', 'rating')
            ->where('status', $this->stage)
            ->where('status', '!=', 'Draft')
            ->where('is_archived', false)
            ->when($this->jobTitleFilter, function($q) {
                $q->where('job_title', $this->jobTitleFilter);
            })
            ->when($this->search, function($q) {
                $q->whereHas('candidate.user', function($uq) {
                    $uq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->sortBy === 'rating_desc', function($q) {
                $q->orderByDesc('interview_scores_avg_rating');
            }, function($q) {
                $q->latest();
            });

        $totalCount = $query->count();
        
        if ($this->enableScreening) {
            $applications = $query->get()->map(function ($app) {
                $app->match_score = $this->calculateMatchScore($app);
                return $app;
            })
            ->sortByDesc('match_score')
            ->take($this->limit);
        } else {
            $applications = $query->take($this->limit)->get();
        }
        
        $hasMore = $totalCount > $this->limit;

        return view('livewire.hrd.candidate-stage', [
            'applications'        => $applications,
            'totalCount'          => $totalCount,
            'hasMore'             => $hasMore,
            'availableJobTitles'  => JobPosition::where('is_active', true)->pluck('title'),
            'currentStage'        => $this->stage,
        ])->layout('components.layouts.hrd');
    }
}
