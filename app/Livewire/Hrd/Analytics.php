<?php

namespace App\Livewire\Hrd;

use App\Models\Application;
use App\Models\JobPosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Analytics extends Component
{
    public function mount()
    {
        $user = Auth::user();
        if (!$user || !$user->isHrd()) {
            return redirect()->route('login');
        }
    }

    public function render()
    {
        // 1. General Metrics
        $totalCandidates = Application::where('status', '!=', 'Draft')->count();
        $totalHired = Application::where('status', 'Hired')->count();
        $totalRejected = Application::where('status', 'Ditolak')->count();
        $totalActive = Application::whereNotIn('status', ['Hired', 'Ditolak', 'Draft'])->count();

        $acceptanceRate = $totalCandidates > 0 ? round(($totalHired / $totalCandidates) * 100, 1) : 0;
        $rejectionRate = $totalCandidates > 0 ? round(($totalRejected / $totalCandidates) * 100, 1) : 0;

        // 2. Cumulative Recruitment Funnel Data
        // To build a realistic funnel, we calculate cumulative pass-through counts:
        // - Administrasi: All applicants (active + hired + rejected)
        // - Psikotes: Those who successfully passed Administrasi (status in Psikotes, Interview, MCU, Hired)
        // - Interview: Those who passed Psikotes (status in Interview, MCU, Hired)
        // - MCU: Those who passed Interview (status in MCU, Hired)
        // - Hired: Those who successfully passed MCU (status in Hired)
        
        $administrasiCount = Application::where('status', '!=', 'Draft')->count();
        
        $psikotesCount = Application::whereIn('status', ['Psikotes', 'Interview', 'MCU', 'Hired'])->count();
        
        $interviewCount = Application::whereIn('status', ['Interview', 'MCU', 'Hired'])->count();
        
        $mcuCount = Application::whereIn('status', ['MCU', 'Hired'])->count();
        
        $hiredCount = Application::where('status', 'Hired')->count();

        // Calculate stage-to-stage conversion rates (persentase kelolosan)
        $conversionRates = [
            'Administrasi' => [
                'count' => $administrasiCount,
                'rate' => 100,
                'next_stage' => 'Psikotes',
                'label' => 'Total Pelamar'
            ],
            'Psikotes' => [
                'count' => $psikotesCount,
                'rate' => $administrasiCount > 0 ? round(($psikotesCount / $administrasiCount) * 100, 1) : 0,
                'next_stage' => 'Interview',
                'label' => 'Lolos Administrasi'
            ],
            'Interview' => [
                'count' => $interviewCount,
                'rate' => $psikotesCount > 0 ? round(($interviewCount / $psikotesCount) * 100, 1) : 0,
                'next_stage' => 'MCU',
                'label' => 'Lolos Psikotes'
            ],
            'MCU' => [
                'count' => $mcuCount,
                'rate' => $interviewCount > 0 ? round(($mcuCount / $interviewCount) * 100, 1) : 0,
                'next_stage' => 'Hired',
                'label' => 'Lolos Interview'
            ],
            'Hired' => [
                'count' => $hiredCount,
                'rate' => $mcuCount > 0 ? round(($hiredCount / $mcuCount) * 100, 1) : 0,
                'next_stage' => 'Selesai',
                'label' => 'Sukses Hired'
            ],
        ];

        // 3. Average Time-to-Hire (Durasi Rekrutmen dalam Hari)
        // Calculated as avg(updated_at - created_at) in days for hired candidates
        // We use TIMESTAMPDIFF for MySQL/MariaDB compatibilities
        $avgTimeToHire = Application::where('status', 'Hired')
            ->selectRaw('AVG(TIMESTAMPDIFF(DAY, created_at, updated_at)) as avg_days')
            ->first()
            ->avg_days;

        $timeToHire = $avgTimeToHire !== null ? round($avgTimeToHire, 1) : null;

        // 4. Top 5 Candidates Leaderboard based on highest average ratings
        $topCandidates = Application::where('status', '!=', 'Draft')
            ->has('interviewScores')
            ->with(['candidate.user', 'interviewScores'])
            ->withAvg('interviewScores', 'rating')
            ->orderByDesc('interview_scores_avg_rating')
            ->take(5)
            ->get();

        // 5. Job Position Sebaran & Success Rate Analysis
        $positionsData = Application::where('status', '!=', 'Draft')
            ->select('job_title', DB::raw('count(*) as total'))
            ->groupBy('job_title')
            ->orderByDesc('total')
            ->get()
            ->map(function ($pos) {
                $hired = Application::where('job_title', $pos->job_title)
                    ->where('status', 'Hired')
                    ->count();
                $pos->hired_count = $hired;
                $pos->success_rate = $pos->total > 0 ? round(($hired / $pos->total) * 100, 1) : 0;
                return $pos;
            });

        return view('livewire.hrd.analytics', [
            'totalCandidates' => $totalCandidates,
            'totalHired' => $totalHired,
            'totalRejected' => $totalRejected,
            'totalActive' => $totalActive,
            'acceptanceRate' => $acceptanceRate,
            'rejectionRate' => $rejectionRate,
            'conversionRates' => $conversionRates,
            'timeToHire' => $timeToHire,
            'topCandidates' => $topCandidates,
            'positionsData' => $positionsData,
        ])->layout('components.layouts.hrd');
    }
}
