<?php

namespace App\Livewire\Hrd;

use App\Models\Application;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Overview extends Component
{
    public function render()
    {
        $currentYear = date('Y');

        // General Stats
        $totalCandidates = Application::where('status', '!=', 'Draft')->count();
        $totalHired = Application::where('status', 'Hired')->count();
        $totalRejected = Application::where('status', 'Ditolak')->count();
        $totalActive = Application::whereNotIn('status', ['Hired', 'Ditolak', 'Draft'])->count();

        // Data for Chart (Candidates per month for current year)
        // Group by month
        $monthlyData = Application::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('status', '!=', 'Draft')
        ->whereYear('created_at', $currentYear)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Initialize all months with 0
        $chartData = array_fill(1, 12, 0);
        foreach ($monthlyData as $data) {
            $chartData[$data->month] = $data->count;
        }

        return view('livewire.hrd.overview', [
            'totalCandidates' => $totalCandidates,
            'totalHired' => $totalHired,
            'totalRejected' => $totalRejected,
            'totalActive' => $totalActive,
            'chartData' => array_values($chartData), // 0-indexed for JS
            'currentYear' => $currentYear
        ])->layout('components.layouts.hrd');
    }
}
