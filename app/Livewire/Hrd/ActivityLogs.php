<?php

namespace App\Livewire\Hrd;

use App\Models\RecruiterActivityLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogs extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterAction = '';

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !$user->isHrd()) {
            return redirect()->route('login');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterAction()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterAction']);
        $this->resetPage();
    }

    public function render()
    {
        $query = RecruiterActivityLog::with(['user', 'application.candidate.user'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->whereHas('user', function ($uq) {
                        $uq->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('application.candidate.user', function ($cq) {
                        $cq->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterAction, function ($q) {
                $q->where('action', $this->filterAction);
            })
            ->latest();

        $logs = $query->paginate(15);

        // Get unique action types for filter options
        $availableActions = ['Pindah Tahap', 'Tolak Kandidat', 'Beri Penilaian'];

        return view('livewire.hrd.activity-logs', [
            'logs' => $logs,
            'availableActions' => $availableActions,
        ])->layout('components.layouts.hrd');
    }
}
