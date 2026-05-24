<?php

namespace App\Livewire\Hrd;

use App\Models\Application;
use App\Models\InterviewScore;
use App\Models\JobPosition;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public ?int $selectedApplicationId = null;
    public string $activeMobileTab = 'Administrasi';
    public string $whatsappTemplate = "Halo [Nama Kandidat],\n\nKami dari [Nama Perusahaan] ingin mengundang Anda untuk mengikuti tahap wawancara (interview) posisi [Posisi Dilamar].\n\nApakah Anda bersedia hadir? Mohon konfirmasi kehadirannya. Terima kasih.";
    
    public int $rating = 5;
    public string $ratingNotes = '';

    // Reject Modal State
    public bool $showRejectModal = false;
    public string $rejectReason = '';

    // Bulk Action State
    public array $selectedApplications = [];

    // Optimization State Variables
    public string $viewMode = 'kanban'; // 'kanban', 'table', or 'rejected'
    public string $search = '';
    public string $jobTitleFilter = '';
    
    public array $limits = [
        'Administrasi' => 10,
        'Psikotes' => 10,
        'Interview' => 10,
        'MCU' => 10,
        'Hired' => 10,
    ];
    public int $tableLimit = 20;

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !$user->isHrd()) {
            return redirect()->route('login');
        }
    }

    public function updatedSearch() { $this->resetLimits(); }
    public function updatedJobTitleFilter() { $this->resetLimits(); }
    
    public function resetLimits()
    {
        $this->limits = [
            'Administrasi' => 10,
            'Psikotes' => 10,
            'Interview' => 10,
            'MCU' => 10,
            'Hired' => 10,
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
        $this->showRejectModal = false;
        $this->rejectReason = '';
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
            $app->delete();
            session()->flash('board_success', "Data kandidat {$name} telah dihapus permanen.");
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

        Application::whereIn('id', $this->selectedApplications)->update([
            'status' => $newStatus,
            'rejection_reason' => $newStatus === 'Ditolak' ? 'Tidak lolos evaluasi administrasi/tahap saat ini.' : null,
            'rejected_at' => $newStatus === 'Ditolak' ? now() : null,
        ]);

        session()->flash('board_success', count($this->selectedApplications) . " kandidat berhasil dipindahkan serentak ke {$newStatus}.");
        $this->selectedApplications = [];
    }

    public function submitAssessment()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ratingNotes' => 'nullable|string|max:1000',
        ]);

        if (!$this->selectedApplicationId) {
            return;
        }

        InterviewScore::create([
            'application_id' => $this->selectedApplicationId,
            'interviewer_id' => Auth::id(),
            'rating' => $this->rating,
            'notes' => $this->ratingNotes,
        ]);

        $this->rating = 5;
        $this->ratingNotes = '';
        session()->flash('rating_success', 'Penilaian berhasil ditambahkan!');
    }

    public function getWhatsappUrl(Application $application): string
    {
        $candidateName = $application->candidate->user->name;
        $jobTitle = $application->job_title;
        $companyName = $application->company_name;
        $phone = $application->candidate->phone;
        $phoneClean = preg_replace('/[^0-9]/', '', $phone);
        if (strpos($phoneClean, '0') === 0) {
            $phoneClean = '62' . substr($phoneClean, 1);
        }
        $search = ['[Nama Kandidat]', '[Posisi Dilamar]', '[Nama Perusahaan]'];
        $replace = [$candidateName, $jobTitle, $companyName];
        $message = str_replace($search, $replace, $this->whatsappTemplate);
        return 'https://wa.me/' . $phoneClean . '?text=' . urlencode($message);
    }

    public function render()
    {
        // Base Query
        $query = Application::with(['candidate.user', 'interviewScores.interviewer'])
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
            ->latest();

        $grouped = [];
        $tableApplications = collect();
        $totalTableCount = 0;
        $hasMoreTable = false;
        
        $hasMore = [
            'Administrasi' => false,
            'Psikotes' => false,
            'Interview' => false,
            'MCU' => false,
            'Hired' => false,
        ];

        if ($this->viewMode === 'kanban') {
            foreach (['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired'] as $status) {
                $statusQuery = (clone $query)->where('status', $status);
                $totalCount = $statusQuery->count();
                $grouped[$status] = $statusQuery->take($this->limits[$status])->get();
                $hasMore[$status] = $totalCount > $this->limits[$status];
            }
        } elseif ($this->viewMode === 'table') {
            $activeQuery = (clone $query)->where('status', '!=', 'Ditolak');
            $totalTableCount = $activeQuery->count();
            $tableApplications = $activeQuery->take($this->tableLimit)->get();
            $hasMoreTable = $totalTableCount > $this->tableLimit;
        }

        $selectedApplication = $this->selectedApplicationId 
            ? Application::with(['candidate.user', 'interviewScores.interviewer', 'answers.requirement'])->find($this->selectedApplicationId)
            : null;

        return view('livewire.hrd.dashboard', [
            'groupedApplications' => $grouped,
            'tableApplications' => $tableApplications,
            'hasMore' => $hasMore,
            'hasMoreTable' => $hasMoreTable,
            'selectedApplication' => $selectedApplication,
            'availableJobTitles' => JobPosition::where('is_active', true)->pluck('title'),
        ])->layout('components.layouts.hrd');
    }
}
