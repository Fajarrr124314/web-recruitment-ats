<?php

namespace App\Livewire\Candidate;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\RecruitmentRequirement;
use App\Models\ApplicationAnswer;
use App\Models\JobPosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class JobForm extends Component
{
    use WithFileUploads;

    public string $phone = '';
    public string $job_title = '';
    public string $company_name = 'Web Rekrutmen Corp';
    
    public $requirements = [];
    public $dynamicAnswers = [];
    public $dynamicFiles = [];
    public $existingFiles = [];
    public $jobPositions = [];

    // Track status if already applied
    public $existingApplication;
    public $draftApplicationId;

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !$user->isCandidate()) {
            return redirect()->route('login');
        }

        $this->requirements = RecruitmentRequirement::where('is_active', true)->orderBy('order')->get();
        $this->jobPositions = JobPosition::where('is_active', true)->orderBy('title')->get();
        
        if ($this->jobPositions->isNotEmpty()) {
            $this->job_title = $this->jobPositions->first()->title;
        }

        // Check if candidate profile already exists
        $candidate = $user->candidate;
        if ($candidate) {
            $this->phone = $candidate->phone === '-' ? '' : $candidate->phone;
            $app = Application::with('answers.requirement')->where('candidate_id', $candidate->id)->first();
            
            // Check 1-month cooldown
            if ($app && $app->status === 'Ditolak' && $app->rejected_at) {
                if ($app->rejected_at->addDays(30)->isPast()) {
                    $app->delete(); // Reset form for new application
                    $app = null;
                }
            }

            if ($app && $app->status === 'Draft') {
                $this->draftApplicationId = $app->id;
                $this->job_title = $app->job_title;
                foreach ($app->answers as $ans) {
                    if ($ans->requirement->type === 'file') {
                        $this->existingFiles[$ans->requirement->id] = $ans->answer_text;
                    } else {
                        $this->dynamicAnswers[$ans->requirement->id] = $ans->answer_text;
                    }
                }
                $this->existingApplication = null;
            } else {
                $this->existingApplication = $app;
            }
        }
    }

    public function updated($propertyName)
    {
        $this->saveDraft();
    }

    protected function saveDraft()
    {
        $user = Auth::user();
        if (!$user || !$this->job_title) return;

        $candidate = Candidate::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $this->phone ?: '-',
                'skills' => [],
                'work_history' => 'Dikumpulkan via formulir dinamis',
            ]
        );

        if ($this->draftApplicationId) {
            $app = Application::find($this->draftApplicationId);
            if ($app) {
                $app->update([
                    'job_title' => $this->job_title,
                    'company_name' => $this->company_name,
                ]);
            }
        } else {
            $app = Application::create([
                'candidate_id' => $candidate->id,
                'job_title' => $this->job_title,
                'company_name' => $this->company_name,
                'status' => 'Draft',
            ]);
            $this->draftApplicationId = $app->id;
        }

        // Save Answers
        foreach ($this->requirements as $req) {
            $answerText = null;
            if ($req->type === 'file' && isset($this->dynamicFiles[$req->id])) {
                $answerText = $this->dynamicFiles[$req->id]->store('dynamic_files', 'public');
                // Store path into existingFiles so we can track it immediately
                $this->existingFiles[$req->id] = $answerText;
                unset($this->dynamicFiles[$req->id]); // Clear temp file so it doesn't re-upload
            } elseif ($req->type !== 'file' && isset($this->dynamicAnswers[$req->id])) {
                $answerText = $this->dynamicAnswers[$req->id];
            }

            if ($answerText !== null) {
                ApplicationAnswer::updateOrCreate(
                    [
                        'application_id' => $this->draftApplicationId,
                        'recruitment_requirement_id' => $req->id,
                    ],
                    [
                        'answer' => $answerText
                    ]
                );
            }
        }
    }

    public function submitApplication()
    {
        $user = Auth::user();
        
        $rules = [
            'phone' => 'required|string|min:9|max:15',
            'job_title' => 'required|string',
            'company_name' => 'required|string',
        ];
        
        $messages = [
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.min' => 'Nomor WhatsApp terlalu pendek.',
        ];

        foreach ($this->requirements as $req) {
            if ($req->type === 'file') {
                $isRequired = $req->is_required && !isset($this->existingFiles[$req->id]);
                $rules['dynamicFiles.'.$req->id] = ($isRequired ? 'required|' : 'nullable|') . 'file|max:2048';
                $messages['dynamicFiles.'.$req->id.'.required'] = 'File untuk ' . $req->question . ' wajib diunggah.';
                $messages['dynamicFiles.'.$req->id.'.max'] = 'Ukuran file ' . $req->question . ' maksimal 2MB.';
            } else {
                if ($req->is_required) {
                    $rules['dynamicAnswers.'.$req->id] = 'required|string';
                    $messages['dynamicAnswers.'.$req->id.'.required'] = 'Pertanyaan ' . $req->question . ' wajib diisi.';
                } else {
                    $rules['dynamicAnswers.'.$req->id] = 'nullable|string';
                }
            }
        }

        $this->validate($rules, $messages);

        $this->saveDraft();

        if ($this->draftApplicationId) {
            $app = Application::find($this->draftApplicationId);
            $app->update(['status' => 'Administrasi']);
            $this->existingApplication = $app;
            $this->draftApplicationId = null;
        }

        session()->flash('success', 'Lamaran Anda berhasil dikirim! Silakan pantau status Anda.');
        
        // Refresh
        $this->existingApplication->load('answers.requirement');
    }

    public function render()
    {
        return view('livewire.candidate.job-form')
            ->layout('components.layouts.app');
    }
}
