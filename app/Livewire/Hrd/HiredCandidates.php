<?php

namespace App\Livewire\Hrd;

use Livewire\Component;
use App\Models\Application;
use Livewire\WithPagination;

class HiredCandidates extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $selectedApplicationId = null;
    public string $whatsappTemplate = "Halo [Nama Kandidat],\n\nKami dari [Nama Perusahaan] ingin memberikan informasi lanjutan terkait dengan proses setelah diterimanya Anda di posisi [Posisi Dilamar].\n\nSelamat atas kelulusan Anda! Mohon konfirmasinya. Terima kasih.";
    public array $selectedApplications = [];

    // Custom Confirm Modal Properties
    public bool $showConfirmModal = false;
    public string $confirmTitle = '';
    public string $confirmMessage = '';
    public string $confirmAction = '';
    public ?int $confirmTargetId = null;

    public function updatingSearch()
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function selectApplication(int $id)
    {
        $this->selectedApplicationId = $id;
    }

    public function closeDetails()
    {
        $this->selectedApplicationId = null;
    }

    public function changeStatus(int $id, string $newStatus)
    {
        $allowedStatuses = ['Administrasi', 'Psikotes', 'Interview', 'MCU', 'Hired', 'Ditolak'];
        if (!in_array($newStatus, $allowedStatuses)) {
            return;
        }
        
        $application = Application::find($id);
        if ($application) {
            $application->update([
                'status' => $newStatus,
                'rejection_reason' => $newStatus === 'Ditolak' ? 'Kandidat dipindahkan ke Ditolak.' : null,
                'rejected_at' => $newStatus === 'Ditolak' ? now() : null,
            ]);
            
            session()->flash('board_success', "Status {$application->candidate->user->name} berhasil dipindahkan ke {$newStatus}");
            
            if ($this->selectedApplicationId === $id && $newStatus !== 'Hired') {
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
            \App\Models\RecruiterActivityLog::create([
                'user_id' => auth()->id(),
                'application_id' => $id,
                'action' => 'deleted',
                'description' => "Mengarsipkan data karyawan {$name} (dihapus dari karyawan diterima)",
            ]);
            session()->flash('board_success', "Data karyawan {$name} telah diarsipkan.");
            if ($this->selectedApplicationId === $id) {
                $this->closeDetails();
            }
            // Remove from selected list if deleted
            $this->selectedApplications = array_diff($this->selectedApplications, [$id]);
        }
    }

    public function toggleSelectAll(array $pageIds)
    {
        $intersect = array_intersect($pageIds, $this->selectedApplications);
        if (count($intersect) === count($pageIds)) {
            // Deselect all on current page
            $this->selectedApplications = array_diff($this->selectedApplications, $pageIds);
        } else {
            // Select all on current page
            $this->selectedApplications = array_unique(array_merge($this->selectedApplications, $pageIds));
        }
    }

    public function clearSelection()
    {
        $this->selectedApplications = [];
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
                \App\Models\RecruiterActivityLog::create([
                    'user_id' => auth()->id(),
                    'application_id' => $appId,
                    'action' => 'deleted',
                    'description' => "Mengarsipkan data karyawan {$app->candidate->user->name} secara massal",
                ]);
            }
        }

        Application::whereIn('id', $this->selectedApplications)->update(['is_archived' => true]);

        session()->flash('board_success', "{$count} data karyawan Hired berhasil diarsipkan secara massal.");
        $this->selectedApplications = [];

        if ($this->selectedApplicationId && !Application::find($this->selectedApplicationId)) {
            $this->closeDetails();
        }
    }

    public function triggerConfirm(string $action, string $title, string $message, ?int $targetId = null)
    {
        $this->confirmAction = $action;
        $this->confirmTitle = $title;
        $this->confirmMessage = $message;
        $this->confirmTargetId = $targetId;
        $this->showConfirmModal = true;
    }

    public function executeConfirmedAction()
    {
        if (!$this->showConfirmModal) {
            return;
        }

        if ($this->confirmAction === 'bulkDelete') {
            $this->bulkDelete();
        } elseif ($this->confirmAction === 'deleteApplication' && $this->confirmTargetId) {
            $this->deleteApplication($this->confirmTargetId);
        }

        $this->cancelConfirm();
    }

    public function cancelConfirm()
    {
        $this->showConfirmModal = false;
        $this->confirmAction = '';
        $this->confirmTitle = '';
        $this->confirmMessage = '';
        $this->confirmTargetId = null;
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

    public function exportExcel()
    {
        $hired = Application::with(['candidate.user', 'interviewScores'])
            ->where('status', 'Hired')
            ->when($this->search, function($q) {
                $q->whereHas('candidate.user', function($uq) {
                    $uq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();

        $filename = 'data_karyawan_diterima_' . date('Ymd_His') . '.xls';

        return response()->streamDownload(function() use ($hired) {
            echo "
            <html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">
            <head>
                <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
                <style>
                    table { border-collapse: collapse; }
                    th { background-color: #10b981; color: white; font-weight: bold; border: 1px solid #d1d5db; padding: 8px; text-align: left; }
                    td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
                    .header-title { font-size: 16pt; font-weight: bold; margin-bottom: 10px; color: #1e293b; }
                </style>
            </head>
            <body>
                <div class=\"header-title\">Data Karyawan Diterima (Hired)</div>
                <div style=\"margin-bottom: 15px; color: #64748b;\">Ekspor data pada: " . date('d M Y H:i:s') . "</div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kandidat</th>
                            <th>Email</th>
                            <th>Telepon / WhatsApp</th>
                            <th>Posisi yang Dilamar</th>
                            <th>Tanggal Diterima</th>
                            <th>Rating Interview</th>
                        </tr>
                    </thead>
                    <tbody>";
            
            foreach ($hired as $index => $app) {
                $avgRating = $app->interviewScores->avg('rating');
                $ratingText = $avgRating ? number_format($avgRating, 1) . ' / 5.0' : 'Belum Dinilai';
                $name = $app->candidate->user->name;
                $email = $app->candidate->user->email;
                $phone = $app->candidate->phone;
                $position = $app->job_title;
                $hiredDate = $app->updated_at ? $app->updated_at->format('d M Y') : '-';
                $no = $index + 1;

                echo "
                        <tr>
                            <td>{$no}</td>
                            <td>{$name}</td>
                            <td>{$email}</td>
                            <td>'{$phone}</td>
                            <td>{$position}</td>
                            <td>{$hiredDate}</td>
                            <td>{$ratingText}</td>
                        </tr>";
            }
            
            echo "
                    </tbody>
                </table>
            </body>
            </html>";
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function render()
    {
        $query = Application::with(['candidate.user', 'interviewScores'])
            ->where('status', 'Hired')
            ->where('is_archived', false)
            ->when($this->search, function($q) {
                $q->whereHas('candidate.user', function($uq) {
                    $uq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest();

        $selectedApplication = $this->selectedApplicationId 
            ? Application::with(['candidate.user', 'interviewScores.interviewer', 'answers.requirement'])->find($this->selectedApplicationId)
            : null;

        return view('livewire.hrd.hired-candidates', [
            'hiredApplications' => $query->paginate(20),
            'selectedApplication' => $selectedApplication,
        ])->layout('components.layouts.hrd');
    }
}
