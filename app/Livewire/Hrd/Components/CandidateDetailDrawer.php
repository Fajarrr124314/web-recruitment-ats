<?php

namespace App\Livewire\Hrd\Components;

use App\Models\Application;
use App\Models\InterviewScore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attributes\On;

class CandidateDetailDrawer extends Component
{
    public ?int $selectedApplicationId = null;
    public string $stage = ''; // Active stage name (administrasi, psikotes, interview, mcu)
    
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

    private function logActivity(int $appId, string $action, string $description)
    {
        \App\Models\RecruiterActivityLog::create([
            'user_id' => Auth::id(),
            'application_id' => $appId,
            'action' => $action,
            'description' => $description,
        ]);
    }

    #[On('show-candidate-details')]
    public function loadCandidate(int $applicationId, string $stage = null)
    {
        $this->selectedApplicationId = $applicationId;
        $this->stage = $stage ?: '';
        $this->rating = 5;
        $this->ratingNotes = '';
        $this->technicalRating = 3;
        $this->communicationRating = 3;
        $this->problemSolvingRating = 3;
        $this->cultureFitRating = 3;
        $this->showRejectModal = false;
        $this->rejectReason = '';
        
        $app = Application::with('candidate.user')->find($applicationId);
        if ($app) {
            if (!$this->stage) {
                $this->stage = $app->status;
            }
            $stageSlug = strtolower($this->stage);
            if (array_key_exists($stageSlug, $this->templates)) {
                $this->selectedTemplateType = $stageSlug;
            } else {
                $this->selectedTemplateType = 'umum';
            }
            $this->updateMessageText();
        }
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
            
            // Dispatch event to parent to refresh the kanban board/table
            $this->dispatch('refresh-board', stageSuccess: "Status {$application->candidate->user->name} dipindahkan ke {$newStatus}");
            $this->closeDetails();
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
                
                $this->dispatch('refresh-board', stageSuccess: "Kandidat {$app->candidate->user->name} ditandai sebagai Tidak Lolos.");
                $this->closeDetails();
            }
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

        $currentStage = $this->stage;
        $dims = \App\Support\StageRubric::getDimensions($currentStage);
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
        $this->dispatch('refresh-board');
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

    public function getSingleCandidateEmail(Application $app): string
    {
        $emailAnswer = $app->answers
            ->first(fn($ans) => stripos($ans->requirement->question ?? '', 'email') !== false);

        if ($emailAnswer && filter_var(trim($emailAnswer->answer), FILTER_VALIDATE_EMAIL)) {
            return trim($emailAnswer->answer);
        }

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

    public function render()
    {
        $selectedApplication = $this->selectedApplicationId
            ? Application::with(['candidate.user', 'interviewScores.interviewer', 'answers.requirement', 'activityLogs'])->find($this->selectedApplicationId)
            : null;

        $currentStage     = $this->stage ?: ($selectedApplication?->status ?? 'Interview');
        $stageDimensions  = \App\Support\StageRubric::getDimensions($currentStage);
        $sliderDimensions = \App\Support\StageRubric::getSliderDimensions($currentStage, $this);
        $stageScores = $selectedApplication
            ? $selectedApplication->interviewScores->filter(fn($s) => !$s->stage || $s->stage === $currentStage)
            : collect();

        $theme = match($currentStage) {
            'Administrasi' => [
                'color' => 'blue',
                'gradient' => 'from-blue-500 to-indigo-600 shadow-blue-500/10',
                'badge' => 'bg-blue-50 text-blue-600 border-blue-200',
                'ring' => 'focus:ring-blue-500',
                'text' => 'text-blue-600',
                'bgLight' => 'bg-blue-50/50',
                'border' => 'border-blue-100',
                'iconBg' => 'bg-blue-100',
            ],
            'Psikotes' => [
                'color' => 'red',
                'gradient' => 'from-red-500 to-rose-600 shadow-red-500/10',
                'badge' => 'bg-red-50 text-red-600 border-red-200',
                'ring' => 'focus:ring-red-500',
                'text' => 'text-red-600',
                'bgLight' => 'bg-red-50/50',
                'border' => 'border-red-100',
                'iconBg' => 'bg-red-100',
            ],
            'Interview' => [
                'color' => 'amber',
                'gradient' => 'from-amber-500 to-orange-600 shadow-amber-500/10',
                'badge' => 'bg-amber-50 text-amber-600 border-amber-200',
                'ring' => 'focus:ring-amber-500',
                'text' => 'text-amber-600',
                'bgLight' => 'bg-amber-50/50',
                'border' => 'border-amber-100',
                'iconBg' => 'bg-amber-100',
            ],
            'MCU' => [
                'color' => 'purple',
                'gradient' => 'from-purple-500 to-indigo-600 shadow-purple-500/10',
                'badge' => 'bg-purple-50 text-purple-600 border-purple-200',
                'ring' => 'focus:ring-purple-500',
                'text' => 'text-purple-600',
                'bgLight' => 'bg-purple-50/50',
                'border' => 'border-purple-100',
                'iconBg' => 'bg-purple-100',
            ],
            default => [
                'color' => 'red',
                'gradient' => 'from-red-500 to-rose-600 shadow-red-500/10',
                'badge' => 'bg-red-50 text-red-600 border-red-200',
                'ring' => 'focus:ring-red-500',
                'text' => 'text-red-600',
                'bgLight' => 'bg-red-50/50',
                'border' => 'border-red-100',
                'iconBg' => 'bg-red-100',
            ]
        };

        return view('livewire.hrd.components.candidate-detail-drawer', [
            'selectedApplication' => $selectedApplication,
            'currentStage'        => $currentStage,
            'stageDimensions'     => $stageDimensions,
            'sliderDimensions'    => $sliderDimensions,
            'stageScores'         => $stageScores,
            'theme'               => $theme,
        ]);
    }
}
