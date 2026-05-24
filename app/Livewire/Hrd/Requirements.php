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
    public $is_required = true;

    // Job Position Form fields
    public $job_title = '';

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->requirements = RecruitmentRequirement::orderBy('order')->get();
        $this->jobPositions = JobPosition::orderBy('id', 'desc')->get();
    }

    // --- Job Position Methods ---
    public function addJobPosition()
    {
        $this->validate([
            'job_title' => 'required|string|max:255',
        ]);

        JobPosition::create([
            'title' => $this->job_title,
            'is_active' => true,
        ]);

        $this->reset(['job_title']);
        $this->loadData();
        
        session()->flash('success_job', 'Posisi lamaran baru berhasil ditambahkan.');
    }

    public function deleteJobPosition($id)
    {
        JobPosition::find($id)->delete();
        $this->loadData();
    }

    public function toggleJobActive($id)
    {
        $job = JobPosition::find($id);
        $job->is_active = !$job->is_active;
        $job->save();
        $this->loadData();
    }

    // --- Requirement Methods ---
    public function addRequirement()
    {
        $this->validate([
            'question' => 'required|string|max:255',
            'type' => 'required|in:text,textarea,file',
        ]);

        RecruitmentRequirement::create([
            'question' => $this->question,
            'type' => $this->type,
            'is_required' => $this->is_required,
            'is_active' => true,
            'order' => $this->requirements->count(),
        ]);

        $this->reset(['question', 'type', 'is_required']);
        $this->loadData();
        
        session()->flash('success_req', 'Persyaratan baru berhasil ditambahkan.');
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

    public function render()
    {
        return view('livewire.hrd.requirements')
            ->layout('components.layouts.hrd');
    }
}
