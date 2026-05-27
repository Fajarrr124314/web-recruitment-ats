<?php

namespace App\Livewire\Hrd;

use App\Models\RecruitmentRequirement;
use App\Models\JobPosition;
use Livewire\Component;

class Requirements extends Component
{
    public $requirements;
    public $jobPositions;

    // Requirement Form fields
    public $question = '';
    public $type = 'text';
    public $optionsList = ['', ''];
    public $is_required = true;

    // Job Position Form fields
    public $job_title = '';
    public $job_requirements = '';
    public $expires_at = ''; // holds the closing date/time

    // Global Recruitment Settings fields
    public $globalIsActive = true;
    public $globalScheduledOpenAt = '';

    // Editing Job Position fields
    public $editingJobId = null;
    public $editingJobTitle = '';
    public $editingJobRequirements = '';
    public $editingJobExpiresAt = '';

    // Editing Requirement fields
    public $editingRequirementId = null;
    public $editingRequirementQuestion = '';
    public $editingRequirementType = 'text';
    public $editingRequirementOptionsList = [];
    public $editingRequirementIsRequired = true;

    public function mount()
    {
        \App\Models\RecruitmentSetting::checkAndAutoActivate();
        $this->loadGlobalSettings();
        $this->loadData();
    }

    public function loadGlobalSettings()
    {
        $this->globalIsActive = \App\Models\RecruitmentSetting::getValue('is_active', '1') === '1';
        $this->globalScheduledOpenAt = \App\Models\RecruitmentSetting::getValue('scheduled_open_at', '');
        if ($this->globalScheduledOpenAt) {
            $this->globalScheduledOpenAt = \Illuminate\Support\Carbon::parse($this->globalScheduledOpenAt, 'Asia/Jakarta')->format('Y-m-d\TH:i');
        } else {
            $this->globalScheduledOpenAt = '';
        }
    }

    public function loadData()
    {
        \App\Models\RecruitmentSetting::checkAndAutoActivate();
        $this->loadGlobalSettings();
        $this->requirements = RecruitmentRequirement::orderBy('order')->get();
        $this->jobPositions = JobPosition::orderBy('id', 'desc')->get();
    }

    // --- Job Position Methods ---
    public function setPresetTime(string $preset)
    {
        switch ($preset) {
            case '3h':
                $this->expires_at = now()->addHours(3)->format('Y-m-d\TH:i');
                break;
            case '1d':
                $this->expires_at = now()->addDay()->format('Y-m-d\TH:i');
                break;
            case '3d':
                $this->expires_at = now()->addDays(3)->format('Y-m-d\TH:i');
                break;
            case '1w':
                $this->expires_at = now()->addWeek()->format('Y-m-d\TH:i');
                break;
            case '2w':
                $this->expires_at = now()->addWeeks(2)->format('Y-m-d\TH:i');
                break;
            case '1m':
                $this->expires_at = now()->addMonth()->format('Y-m-d\TH:i');
                break;
            case 'clear':
                $this->expires_at = '';
                break;
        }
    }

    public function addJobPosition()
    {
        $this->validate([
            'job_title' => 'required|string|max:255',
            'job_requirements' => 'nullable|string|max:2000',
            'expires_at' => 'nullable|date',
        ]);

        $expiresVal = $this->expires_at ? \Illuminate\Support\Carbon::parse($this->expires_at, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null;

        JobPosition::create([
            'title' => $this->job_title,
            'requirements' => $this->job_requirements,
            'is_active' => true,
            'expires_at' => $expiresVal,
        ]);

        $this->reset(['job_title', 'job_requirements', 'expires_at']);
        $this->loadData();
        
        session()->flash('success_job', 'Posisi lamaran baru berhasil ditambahkan.');
    }

    public function editJobPosition($id)
    {
        $job = JobPosition::find($id);
        if ($job) {
            $this->editingJobId = $job->id;
            $this->editingJobTitle = $job->title;
            $this->editingJobRequirements = $job->requirements;
            $this->editingJobExpiresAt = $job->expires_at ? \Illuminate\Support\Carbon::parse($job->expires_at, 'Asia/Jakarta')->format('Y-m-d\TH:i') : '';
        }
    }

    public function closeEditJob()
    {
        $this->editingJobId = null;
        $this->reset(['editingJobTitle', 'editingJobRequirements', 'editingJobExpiresAt']);
    }

    public function updateJobPosition()
    {
        $this->validate([
            'editingJobTitle' => 'required|string|max:255',
            'editingJobRequirements' => 'nullable|string|max:2000',
            'editingJobExpiresAt' => 'nullable|date',
        ]);

        $job = JobPosition::find($this->editingJobId);
        if ($job) {
            $expiresVal = $this->editingJobExpiresAt ? \Illuminate\Support\Carbon::parse($this->editingJobExpiresAt, 'Asia/Jakarta')->format('Y-m-d H:i:s') : null;
            $job->update([
                'title' => $this->editingJobTitle,
                'requirements' => $this->editingJobRequirements,
                'expires_at' => $expiresVal,
            ]);
            session()->flash('success_job', 'Posisi lamaran berhasil diperbarui.');
        }

        $this->closeEditJob();
        $this->loadData();
    }

    public function deleteJobPosition($id)
    {
        JobPosition::find($id)->delete();
        $this->loadData();
    }

    public function toggleJobActive($id)
    {
        $job = JobPosition::find($id);
        if ($job) {
            $job->is_active = !$job->is_active;
            $job->save();
        }
        $this->loadData();
    }

    // --- Requirement Methods ---
    public function addOptionInput()
    {
        $this->optionsList[] = '';
    }

    public function removeOptionInput($index)
    {
        if (count($this->optionsList) > 1) {
            unset($this->optionsList[$index]);
            $this->optionsList = array_values($this->optionsList);
        }
    }

    public function addRequirement()
    {
        $this->validate([
            'question' => 'required|string|max:255',
            'type' => 'required|in:text,textarea,file,select',
        ]);

        if ($this->type === 'select') {
            $cleanOptionsList = collect($this->optionsList)
                ->map(fn($o) => trim($o))
                ->filter(fn($o) => $o !== '')
                ->toArray();

            if (empty($cleanOptionsList)) {
                $this->addError('optionsList', 'Mohon isi minimal satu opsi pilihan.');
                return;
            }
            
            $optionsString = implode(';', $cleanOptionsList);
        } else {
            $optionsString = null;
        }

        RecruitmentRequirement::create([
            'question' => $this->question,
            'type' => $this->type,
            'options' => $optionsString,
            'is_required' => $this->is_required,
            'is_active' => true,
            'order' => $this->requirements->count(),
        ]);

        $this->reset(['question', 'type', 'is_required']);
        $this->optionsList = ['', ''];
        $this->loadData();
        
        session()->flash('success_req', 'Persyaratan baru berhasil ditambahkan.');
    }

    public function editRequirement($id)
    {
        $req = RecruitmentRequirement::find($id);
        if ($req) {
            $this->editingRequirementId = $req->id;
            $this->editingRequirementQuestion = $req->question;
            $this->editingRequirementType = $req->type;
            $this->editingRequirementIsRequired = (bool)$req->is_required;
            if ($req->type === 'select' && $req->options) {
                $this->editingRequirementOptionsList = explode(';', $req->options);
            } else {
                $this->editingRequirementOptionsList = ['', ''];
            }
        }
    }

    public function addEditingOptionInput()
    {
        $this->editingRequirementOptionsList[] = '';
    }

    public function removeEditingOptionInput($index)
    {
        if (count($this->editingRequirementOptionsList) > 1) {
            unset($this->editingRequirementOptionsList[$index]);
            $this->editingRequirementOptionsList = array_values($this->editingRequirementOptionsList);
        }
    }

    public function closeEditRequirement()
    {
        $this->editingRequirementId = null;
        $this->reset(['editingRequirementQuestion', 'editingRequirementType', 'editingRequirementOptionsList', 'editingRequirementIsRequired']);
    }

    public function updateRequirement()
    {
        $this->validate([
            'editingRequirementQuestion' => 'required|string|max:255',
            'editingRequirementType' => 'required|in:text,textarea,file,select',
        ]);

        $req = RecruitmentRequirement::find($this->editingRequirementId);
        if ($req) {
            if ($this->editingRequirementType === 'select') {
                $cleanOptionsList = collect($this->editingRequirementOptionsList)
                    ->map(fn($o) => trim($o))
                    ->filter(fn($o) => $o !== '')
                    ->toArray();

                if (empty($cleanOptionsList)) {
                    $this->addError('editingRequirementOptionsList', 'Mohon isi minimal satu opsi pilihan.');
                    return;
                }
                
                $optionsString = implode(';', $cleanOptionsList);
            } else {
                $optionsString = null;
            }

            $req->update([
                'question' => $this->editingRequirementQuestion,
                'type' => $this->editingRequirementType,
                'options' => $optionsString,
                'is_required' => $this->editingRequirementIsRequired,
            ]);

            session()->flash('success_req', 'Persyaratan berhasil diperbarui.');
        }

        $this->closeEditRequirement();
        $this->loadData();
    }

    public function deleteRequirement($id)
    {
        RecruitmentRequirement::find($id)->delete();
        $this->loadData();
    }

    public function toggleActive($id)
    {
        $req = RecruitmentRequirement::find($id);
        $req->is_active = !$req->is_active;
        $req->save();
        $this->loadData();
    }

    public function toggleGlobalActive()
    {
        $this->globalIsActive = !$this->globalIsActive;
        \App\Models\RecruitmentSetting::setValue('is_active', $this->globalIsActive ? '1' : '0');

        if ($this->globalIsActive) {
            // If manual toggle is set to AKTIF, clear schedule
            \App\Models\RecruitmentSetting::setValue('scheduled_open_at', null);
            $this->globalScheduledOpenAt = '';
        }

        $this->loadData();
        session()->flash('success_global', 'Status pendaftaran global berhasil diperbarui menjadi ' . ($this->globalIsActive ? 'AKTIF' : 'NONAKTIF') . '.');
    }

    public function saveScheduledOpen()
    {
        $this->validate([
            'globalScheduledOpenAt' => 'required|date|after:now',
        ], [
            'globalScheduledOpenAt.required' => 'Tanggal & waktu wajib diisi.',
            'globalScheduledOpenAt.after' => 'Tanggal & waktu harus di masa mendatang.',
        ]);

        $scheduledVal = \Illuminate\Support\Carbon::parse($this->globalScheduledOpenAt, 'Asia/Jakarta')->format('Y-m-d H:i:s');

        // Set is_active to 0 so it closes immediately until that time
        \App\Models\RecruitmentSetting::setValue('is_active', '0');
        \App\Models\RecruitmentSetting::setValue('scheduled_open_at', $scheduledVal);

        $this->loadData();
        
        $formattedTime = \Illuminate\Support\Carbon::parse($scheduledVal, 'Asia/Jakarta')->translatedFormat('d M Y H:i');
        session()->flash('success_schedule', 'Jadwal pembukaan portal otomatis berhasil diatur pada ' . $formattedTime . '.');
    }

    public function cancelSchedule()
    {
        \App\Models\RecruitmentSetting::setValue('scheduled_open_at', null);
        $this->globalScheduledOpenAt = '';
        
        $this->loadData();
        session()->flash('success_schedule', 'Jadwal pembukaan otomatis telah berhasil dibatalkan.');
    }

    public function render()
    {
        return view('livewire.hrd.requirements')
            ->layout('components.layouts.hrd');
    }
}
