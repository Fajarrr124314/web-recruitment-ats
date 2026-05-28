<?php

namespace App\Livewire\Hrd;

use App\Models\Application;
use App\Models\InterviewScore;
use App\Models\JobPosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Dashboard extends Component
{
    public ?int $selectedApplicationId = null;
    public string $activeMobileTab = 'Administrasi';
    public bool $showCalendar = false;
    
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

    public string $selectedTemplateType = 'umum';
    public string $messageText = '';
    public string $emailSubject = '';

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
    
    public int $rating = 5;
    public string $ratingNotes = '';

    // Structured Rubric Evaluation Ratings (Scale 1-5)
    public int $technicalRating = 3;
    public int $communicationRating = 3;
    public int $problemSolvingRating = 3;
    public int $cultureFitRating = 3;

    // Reject Modal State
    public bool $showRejectModal = false;
    public string $rejectReason = '';

    // Bulk Reject Modal State
    public bool $showBulkRejectModal = false;
    public string $bulkRejectReason = '';
    // Bulk Action State
    public array $selectedApplications = [];

    // Optimization State Variables
    public string $viewMode = 'kanban'; // 'kanban', 'table', or 'rejected'
    public string $search = '';
    public string $jobTitleFilter = '';
    public string $sortBy = 'latest'; // 'latest' or 'rating_desc'

    // Auto-Screening & Matching Engine State
    public bool $enableScreening = false;
    public string $filterSkill = '';
    public string $filterEducation = '';
    public string $filterExperience = '';
    public array $limits = [
        'Administrasi' => 10,
        'Psikotes' => 10,
        'Interview' => 10,
        'MCU' => 10,
    ];
    public int $tableLimit = 20;

    // Calendar & Notes Component State
    public int $calendarYear;
    public int $calendarMonth;
    public string $newNoteText = '';
    public string $selectedDate = '';
    public array $notes = [];
    public string $floatingReminder = '';

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !$user->isHrd()) {
            return redirect()->route('login');
        }

        // Initialize Calendar
        $this->calendarYear = (int) now()->year;
        $this->calendarMonth = (int) now()->month;
        $this->loadCalendarNotes();
        $this->checkTodayNotes();
    }

    public function loadCalendarNotes()
    {
        $notesVal = \App\Models\RecruitmentSetting::getValue('calendar_notes', '[]');
        $this->notes = json_decode($notesVal, true) ?: [];
    }

    public function saveCalendarNotes()
    {
        \App\Models\RecruitmentSetting::setValue('calendar_notes', json_encode($this->notes));
    }

    public function selectCalendarDate($date)
    {
        $this->selectedDate = $date;
        $this->newNoteText = '';
    }

    public function addCalendarNote()
    {
        if (!$this->selectedDate) return;
        
        if (empty($this->newNoteText)) return;

        if (!isset($this->notes[$this->selectedDate])) {
            $this->notes[$this->selectedDate] = [];
        }
        $this->notes[$this->selectedDate][] = $this->newNoteText;
        $this->saveCalendarNotes();
        
        $this->newNoteText = '';
        $this->floatingReminder = "Catatan berhasil ditambahkan pada tanggal " . $this->selectedDate;
    }

    public function removeCalendarNote($date, $index)
    {
        if (isset($this->notes[$date][$index])) {
            unset($this->notes[$date][$index]);
            $this->notes[$date] = array_values($this->notes[$date]);
            if (empty($this->notes[$date])) {
                unset($this->notes[$date]);
            }
            $this->saveCalendarNotes();
        }
    }

    public function prevMonth()
    {
        $this->calendarMonth--;
        if ($this->calendarMonth < 1) {
            $this->calendarMonth = 12;
            $this->calendarYear--;
        }
    }

    public function nextMonth()
    {
        $this->calendarMonth++;
        if ($this->calendarMonth > 12) {
            $this->calendarMonth = 1;
            $this->calendarYear++;
        }
    }

    public function checkTodayNotes()
    {
        $today = now()->format('Y-m-d');
        if (isset($this->notes[$today]) && count($this->notes[$today]) > 0) {
            $this->floatingReminder = "Pengingat Hari Ini: " . implode(', ', $this->notes[$today]);
        }
    }

    public function updatedSearch() { $this->resetLimits(); }
    public function updatedJobTitleFilter() { $this->resetLimits(); }
    public function updatedFilterSkill() { $this->resetLimits(); }
    public function updatedFilterEducation() { $this->resetLimits(); }
    public function updatedFilterExperience() { $this->resetLimits(); }
    public function updatedEnableScreening() { $this->resetLimits(); }

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
    
    public function resetLimits()
    {
        $this->limits = [
            'Administrasi' => 10,
            'Psikotes' => 10,
            'Interview' => 10,
            'MCU' => 10,
        ];
        $this->tableLimit = 20;
    }

    public function loadMore(string $status)
    {
        if (isset($this->limits[$status])) {
            $this->limits[$status] += 10;
        }
    }

    public function loadMoreTable()
    {
        $this->tableLimit += 20;
    }

    public function setViewMode(string $mode)
    {
        $this->viewMode = $mode;
    }

    public function selectApplication(int $id)
    {
        $this->selectedApplicationId = $id;
        $this->rating = 5;
        $this->ratingNotes = '';
        $this->technicalRating = 3;
        $this->communicationRating = 3;
        $this->problemSolvingRating = 3;
        $this->cultureFitRating = 3;
        $this->showRejectModal = false;
        $this->rejectReason = '';
        
        $app = Application::find($id);
        if ($app) {
            $stageSlug = strtolower($app->status);
            if (array_key_exists($stageSlug, $this->templates)) {
                $this->selectedTemplateType = $stageSlug;
            } else {
                $this->selectedTemplateType = 'umum';
            }
        } else {
            $this->selectedTemplateType = 'umum';
        }
        
        $this->updateMessageText();
    }

    public function closeDetails()
    {
        $this->selectedApplicationId = null;
        $this->showRejectModal = false;
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
            session()->flash('board_success', "Status {$application->candidate->user->name} dipindahkan ke {$newStatus}");
        }
    }

    public function openRejectModal()
    {
        $this->showRejectModal = true;
        $this->rejectReason = '';
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
    }

    public function rejectCandidate()
    {
        $this->validate([
            'rejectReason' => 'required|string|min:5|max:1000'
        ], [
            'rejectReason.required' => 'Alasan gagal / tidak lolos wajib diisi agar kandidat tahu evaluasinya.'
        ]);

        if ($this->selectedApplicationId) {
            $app = Application::find($this->selectedApplicationId);
            if ($app) {
                $app->update([
                    'status' => 'Ditolak',
                    'rejection_reason' => $this->rejectReason,
                    'rejected_at' => now(),
                ]);
                $this->logActivity($app->id, 'rejected', "Menolak kandidat dengan alasan: \"{$this->rejectReason}\"");
                session()->flash('board_success', "Kandidat {$app->candidate->user->name} ditandai sebagai Tidak Lolos.");
                $this->closeDetails();
            }
        }
    }

    public function deleteApplication(int $id)
    {
        $app = Application::find($id);
        if ($app) {
            $name = $app->candidate->user->name;
            $app->update(['is_archived' => true]);
            $this->logActivity($id, 'deleted', "Mengarsipkan data kandidat {$name} (dihapus dari papan kanban)");
            session()->flash('board_success', "Data kandidat {$name} telah diarsipkan.");
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

        session()->flash('board_success', count($this->selectedApplications) . " kandidat berhasil dipindahkan serentak ke {$newStatus}.");
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
        session()->flash('board_success', "{$count} kandidat berhasil ditolak serentak dengan alasan yang ditentukan.");
        $this->showBulkRejectModal = false;
        $this->selectedApplications = [];
    }

    public function closeBulkRejectModal()
    {
        $this->showBulkRejectModal = false;
    }

    public function toggleSelectStage(string $stage)
    {
        // Get all candidate IDs in this stage (respecting active search/filter)
        $stageIds = Application::where('status', $stage)
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

        // Check if all of these IDs are already in selectedApplications
        $alreadySelected = array_intersect($stageIds, $this->selectedApplications);
        if (count($alreadySelected) === count($stageIds)) {
            // Deselect all in this stage
            $this->selectedApplications = array_diff($this->selectedApplications, $stageIds);
        } else {
            // Select all in this stage
            $this->selectedApplications = array_unique(array_merge($this->selectedApplications, $stageIds));
        }
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

        session()->flash('board_success', "{$count} data kandidat berhasil diarsipkan secara massal.");
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

    public function submitAssessment()
    {
        $this->validate([
            'technicalRating'      => 'required|integer|min:1|max:5',
            'communicationRating'  => 'required|integer|min:1|max:5',
            'problemSolvingRating' => 'required|integer|min:1|max:5',
            'cultureFitRating'     => 'required|integer|min:1|max:5',
            'ratingNotes'          => 'nullable|string|max:1000',
        ]);

        if (!$this->selectedApplicationId) {
            return;
        }

        // Tentukan stage dari status aplikasi saat ini
        $app = Application::find($this->selectedApplicationId);
        $currentStage = $app?->status ?? 'Interview';

        // Ambil label dimensi aktual untuk log yang informatif
        $dims = \App\Support\StageRubric::getDimensions($currentStage);

        // Hitung rata-rata dari 4 dimensi rubrik
        $avgRating = round(
            ($this->technicalRating + $this->communicationRating + $this->problemSolvingRating + $this->cultureFitRating) / 4
        );

        InterviewScore::create([
            'application_id'         => $this->selectedApplicationId,
            'interviewer_id'         => Auth::id(),
            'rating'                 => $avgRating,
            'notes'                  => $this->ratingNotes,
            'stage'                  => $currentStage,
            'technical_rating'       => $this->technicalRating,
            'communication_rating'   => $this->communicationRating,
            'problem_solving_rating' => $this->problemSolvingRating,
            'culture_fit_rating'     => $this->cultureFitRating,
        ]);

        $logDetail = "[{$currentStage}] {$dims[0]['label']}={$this->technicalRating} | {$dims[1]['label']}={$this->communicationRating} | {$dims[2]['label']}={$this->problemSolvingRating} | {$dims[3]['label']}={$this->cultureFitRating} (Avg: ★{$avgRating})";
        if ($this->ratingNotes) {
            $logDetail .= " | Catatan: \"{$this->ratingNotes}\"";
        }
        $this->logActivity($this->selectedApplicationId, 'scoring', $logDetail);

        $this->rating = $avgRating;
        $this->technicalRating = 3;
        $this->communicationRating = 3;
        $this->problemSolvingRating = 3;
        $this->cultureFitRating = 3;
        $this->ratingNotes = '';
        session()->flash('rating_success', "Penilaian tahap {$currentStage} berhasil disimpan!");
    }

    public function updatedSelectedTemplateType()
    {
        $this->updateMessageText();
    }

    public function updateMessageText()
    {
        if ($this->selectedApplicationId) {
            $app = Application::with('candidate.user')->find($this->selectedApplicationId);
            if ($app) {
                $tpl = $this->templates[$this->selectedTemplateType] ?? $this->templates['umum'];
                $this->messageText = $this->parsePlaceholders($tpl['body'], $app);
                $this->emailSubject = $this->parsePlaceholders($tpl['subject'], $app);
            }
        }
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
        $phone = $application->candidate->phone;
        $phoneClean = preg_replace('/[^0-9]/', '', $phone);
        if (strpos($phoneClean, '0') === 0) {
            $phoneClean = '62' . substr($phoneClean, 1);
        }
        
        $msg = $customMessage ?? $this->messageText;
        if (empty($msg)) {
            $tpl = $this->templates[$this->selectedTemplateType] ?? $this->templates['umum'];
            $msg = $this->parsePlaceholders($tpl['body'], $application);
        }
        
        return 'https://wa.me/' . $phoneClean . '?text=' . urlencode($msg);
    }

    public function sendSingleEmail()
    {
        if (!$this->selectedApplicationId) {
            return;
        }

        $app = Application::with(['candidate.user', 'answers.requirement'])->find($this->selectedApplicationId);
        if (!$app) {
            return;
        }

        $this->validate([
            'messageText' => 'required|string',
            'emailSubject' => 'required|string',
        ], [
            'messageText.required' => 'Isi pesan tidak boleh kosong.',
            'emailSubject.required' => 'Subjek email tidak boleh kosong.',
        ]);

        try {
            $email = $this->getSingleCandidateEmail($app);
            $subject = $this->emailSubject;
            $body = $this->messageText;

            Mail::raw($body, function ($message) use ($email, $subject) {
                $message->to($email)
                    ->subject($subject);
            });

            session()->flash('notification_success', "Email berhasil dikirim ke {$email}!");
        } catch (\Exception $e) {
            session()->flash('notification_error', "Gagal mengirim email: " . $e->getMessage());
        }
    }

    public function getSingleCandidateEmail(Application $app): string
    {
        // Cari jawaban yang pertanyaannya mengandung kata 'email'
        $emailAnswer = $app->answers
            ->first(fn($ans) => stripos($ans->requirement->question ?? '', 'email') !== false);

        if ($emailAnswer && filter_var(trim($emailAnswer->answer), FILTER_VALIDATE_EMAIL)) {
            return trim($emailAnswer->answer);
        }

        // Fallback ke email akun user
        return $app->candidate?->user?->email ?? '';
    }

    public function getGmailUrl(Application $application): string
    {
        $email = $this->getSingleCandidateEmail($application);
        
        $msg = $this->messageText;
        if (empty($msg)) {
            $tpl = $this->templates[$this->selectedTemplateType] ?? $this->templates['umum'];
            $msg = $tpl['body'];
        }
        $msgParsed = $this->parsePlaceholders($msg, $application);
        
        $subject = $this->emailSubject;
        if (empty($subject)) {
            $tpl = $this->templates[$this->selectedTemplateType] ?? $this->templates['umum'];
            $subject = $tpl['subject'];
        }
        $subjectParsed = $this->parsePlaceholders($subject, $application);
        
        return 'https://mail.google.com/mail/?view=cm&fs=1&to=' . urlencode($email) . '&su=' . urlencode($subjectParsed) . '&body=' . urlencode($msgParsed);
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
            session()->flash('board_success', "Berhasil mengirim email massal ke {$successCount} kandidat. (Gagal: {$failCount})");
        } else {
            session()->flash('board_success', "Berhasil mengirim email massal ke {$successCount} kandidat secara serentak!");
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
        // Base Query
        $query = Application::with(['candidate.user', 'interviewScores.interviewer'])
            ->withAvg('interviewScores', 'rating')
            ->where('status', '!=', 'Draft')
            ->where('is_archived', false)
            ->where(function($q) {
                $q->whereNot(function($inner) {
                    $inner->whereHas('candidate.user', function($uq) {
                        $uq->where('name', 'topae');
                    })->where('job_title', 'Digital Marketing');
                });
            })
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

        $grouped = [];
        $tableApplications = collect();
        $totalTableCount = 0;
        $hasMoreTable = false;
        $stageSummaries = [];
        
        $hasMore = [
            'Administrasi' => false,
            'Psikotes' => false,
            'Interview' => false,
            'MCU' => false,
            'Hired' => false,
        ];

        if ($this->viewMode === 'kanban') {
            // Count total candidates overall for progress calculation
            $totalActiveCandidates = Application::whereIn('status', ['Administrasi', 'Psikotes', 'Interview', 'MCU'])
                ->where('status', '!=', 'Draft')
                ->where('is_archived', false)
                ->where(function($q) {
                    $q->whereNot(function($inner) {
                        $inner->whereHas('candidate.user', function($uq) {
                            $uq->where('name', 'topae');
                        })->where('job_title', 'Digital Marketing');
                    });
                })
                ->when($this->jobTitleFilter, function($q) {
                    $q->where('job_title', $this->jobTitleFilter);
                })
                ->when($this->search, function($q) {
                    $q->whereHas('candidate.user', function($uq) {
                        $uq->where('name', 'like', '%' . $this->search . '%')
                           ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
                })
                ->count();

            foreach (['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired'] as $status) {
                // Base filter query
                $baseQuery = Application::where('status', $status)
                    ->where('status', '!=', 'Draft')
                    ->where('is_archived', false)
                    ->where(function($q) {
                        $q->whereNot(function($inner) {
                            $inner->whereHas('candidate.user', function($uq) {
                                $uq->where('name', 'topae');
                            })->where('job_title', 'Digital Marketing');
                        });
                    })
                    ->when($this->jobTitleFilter, function($q) {
                        $q->where('job_title', $this->jobTitleFilter);
                    })
                    ->when($this->search, function($q) {
                        $q->whereHas('candidate.user', function($uq) {
                            $uq->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('email', 'like', '%' . $this->search . '%');
                        });
                    });

                $totalCount = $baseQuery->count();
                
                // Get grouped candidates sorted properly according to settings
                $grouped[$status] = (clone $baseQuery)
                    ->with(['candidate.user', 'interviewScores.interviewer'])
                    ->withAvg('interviewScores', 'rating')
                    ->when($this->sortBy === 'rating_desc', function($q) {
                        $q->orderByDesc('interview_scores_avg_rating');
                    }, function($q) {
                        $q->latest();
                    })
                    ->take($this->limits[$status] ?? 10)
                    ->get();
                    
                $hasMore[$status] = $totalCount > ($this->limits[$status] ?? 10);

                // Average Rating computed directly at database level for maximum speed and memory efficiency
                $avgRating = \App\Models\InterviewScore::whereIn('application_id', (clone $baseQuery)->select('id'))->avg('rating');

                // Sebaran per Posisi (Top 3) - Only run if total count is > 0
                $jobDistribution = [];
                if ($totalCount > 0) {
                    $jobDistribution = (clone $baseQuery)
                        ->select('job_title', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                        ->groupBy('job_title')
                        ->orderByDesc('count')
                        ->take(3)
                        ->get()
                        ->pluck('count', 'job_title')
                        ->toArray();
                }

                // Kandidat Terbaru (retrieved directly in-memory from collection to save heavy SQL queries)
                $latestCandidate = null;
                if ($grouped[$status]->isNotEmpty()) {
                    if ($this->sortBy === 'latest') {
                        $latestCandidate = $grouped[$status]->first();
                    } else {
                        $latestCandidate = $grouped[$status]->sortByDesc('created_at')->first();
                    }
                }

                $stageSummaries[$status] = [
                    'totalCount' => $totalCount,
                    'avgRating' => $avgRating ? number_format($avgRating, 1) : null,
                    'jobDistribution' => $jobDistribution,
                    'latestCandidateName' => $latestCandidate ? $latestCandidate->candidate->user->name : null,
                    'percentage' => $totalActiveCandidates > 0 ? round(($totalCount / $totalActiveCandidates) * 100) : 0,
                ];
            }

            // Set active mobile tab to the first non-empty stage if the current active tab has 0 candidates
            $nonEmptyStages = collect(['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired'])
                ->filter(fn($st) => ($stageSummaries[$st]['totalCount'] ?? 0) > 0);
            
            if (!$nonEmptyStages->contains($this->activeMobileTab) && $nonEmptyStages->isNotEmpty()) {
                $this->activeMobileTab = $nonEmptyStages->first();
            }
        } elseif ($this->viewMode === 'table') {
            $activeQuery = (clone $query)->whereNotIn('status', ['Ditolak', 'Hired']);
            $totalTableCount = $activeQuery->count();
            
            $tableApplications = $activeQuery->get()->map(function ($app) {
                $app->match_score = $this->calculateMatchScore($app);
                return $app;
            })
            ->sortByDesc('match_score')
            ->take($this->tableLimit);
            
            $hasMoreTable = $totalTableCount > $this->tableLimit;
        }

        $selectedApplication = $this->selectedApplicationId
            ? Application::with(['candidate.user', 'interviewScores.interviewer', 'answers.requirement', 'activityLogs'])->find($this->selectedApplicationId)
            : null;

        // Stage-specific rubric computation
        $currentStage    = $selectedApplication?->status ?? 'Interview';
        $stageDimensions = \App\Support\StageRubric::getDimensions($currentStage);
        $sliderDimensions = \App\Support\StageRubric::getSliderDimensions($currentStage, $this);
        $stageScores = $selectedApplication
            ? $selectedApplication->interviewScores->filter(fn($s) => !$s->stage || $s->stage === $currentStage)
            : collect();

        $activeJobCount = JobPosition::where('is_active', true)->count();
        $totalCandidatesCount = Application::where('status', '!=', 'Draft')->count();
        $todayApplicationsCount = Application::where('status', '!=', 'Draft')->whereDate('created_at', \Carbon\Carbon::today('Asia/Jakarta'))->count();

        return view('livewire.hrd.dashboard', [
            'groupedApplications' => $grouped,
            'tableApplications'   => $tableApplications,
            'hasMore'             => $hasMore,
            'hasMoreTable'        => $hasMoreTable,
            'stageSummaries'      => $stageSummaries,
            'selectedApplication' => $selectedApplication,
            'availableJobTitles'  => JobPosition::where('is_active', true)->pluck('title'),
            'currentStage'        => $currentStage,
            'stageDimensions'     => $stageDimensions,
            'sliderDimensions'    => $sliderDimensions,
            'stageScores'         => $stageScores,
            'activeJobCount'      => $activeJobCount,
            'totalCandidatesCount'=> $totalCandidatesCount,
            'todayApplicationsCount' => $todayApplicationsCount,
        ])->layout('components.layouts.hrd');
    }
}
