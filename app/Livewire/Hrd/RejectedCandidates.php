<?php

namespace App\Livewire\Hrd;

use Livewire\Component;
use App\Models\Application;
use Livewire\WithPagination;

class RejectedCandidates extends Component
{
    use WithPagination;

    public string $search = '';
    
    public function deleteApplication(int $id)
    {
        $app = Application::find($id);
        if ($app) {
            $name = $app->candidate->user->name;
            $app->delete();
            session()->flash('board_success', "Data kandidat {$name} telah dihapus permanen.");
        }
    }

    public function render()
    {
        $query = Application::with('candidate.user')
            ->where('status', 'Ditolak')
            ->when($this->search, function($q) {
                $q->whereHas('candidate.user', function($uq) {
                    $uq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest();

        return view('livewire.hrd.rejected-candidates', [
            'rejectedApplications' => $query->paginate(20),
        ])->layout('components.layouts.hrd');
    }
}
