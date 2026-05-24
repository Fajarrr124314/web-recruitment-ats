<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\InterviewScore;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed HRD Users
        $hrd1 = User::create([
            'name' => 'Budi Rekrutmen',
            'email' => 'hrd1@example.com',
            'password' => Hash::make('password'),
            'role' => 'hrd',
        ]);

        $hrd2 = User::create([
            'name' => 'Siti Rekrutmen',
            'email' => 'hrd2@example.com',
            'password' => Hash::make('password'),
            'role' => 'hrd',
        ]);

        // 2. Seed Candidates
        // Candidate 1: Andi Pratama (Stage: Administrasi)
        $user1 = User::create([
            'name' => 'Andi Pratama',
            'email' => 'kandidat1@example.com',
            'password' => Hash::make('password'),
            'role' => 'candidate',
        ]);

        $candidate1 = Candidate::create([
            'user_id' => $user1->id,
            'phone' => '081234567890',
            'skills' => ['PHP', 'Laravel', 'Livewire', 'MySQL', 'Git'],
            'work_history' => "3 tahun bekerja sebagai Backend Developer di Startup A.\n- Membangun RESTful API dengan Laravel.\n- Mengoptimalkan query database MySQL.\n- Berkolaborasi menggunakan Git.",
            'cv_path' => null, // Will use upload or leave empty for seeder
            'portfolio_path' => null,
        ]);

        Application::create([
            'candidate_id' => $candidate1->id,
            'job_title' => 'Laravel Developer',
            'company_name' => 'Web Rekrutmen Corp',
            'status' => 'Administrasi',
        ]);

        // Candidate 2: Dewi Lestari (Stage: Interview)
        $user2 = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'kandidat2@example.com',
            'password' => Hash::make('password'),
            'role' => 'candidate',
        ]);

        $candidate2 = Candidate::create([
            'user_id' => $user2->id,
            'phone' => '081987654321',
            'skills' => ['Figma', 'Prototyping', 'User Research', 'Wireframing'],
            'work_history' => "2 tahun bekerja sebagai UI/UX Designer di Agensi Digital B.\n- Mendesain wireframe dan mockup high-fidelity di Figma.\n- Melakukan user testing untuk 5+ proyek aplikasi mobile.\n- Mengembangkan design system yang terintegrasi.",
            'cv_path' => null,
            'portfolio_path' => null,
        ]);

        $app2 = Application::create([
            'candidate_id' => $candidate2->id,
            'job_title' => 'UI/UX Designer',
            'company_name' => 'Web Rekrutmen Corp',
            'status' => 'Interview',
        ]);

        // Seed Interview Ratings for Dewi Lestari
        InterviewScore::create([
            'application_id' => $app2->id,
            'interviewer_id' => $hrd1->id,
            'rating' => 4,
            'notes' => 'Portofolio UI/UX sangat rapi dan estetik. Kemampuan komunikasi kandidat sangat baik.',
        ]);

        InterviewScore::create([
            'application_id' => $app2->id,
            'interviewer_id' => $hrd2->id,
            'rating' => 5,
            'notes' => 'Sangat menguasai metodologi design thinking. Relevansi portfolio dengan kebutuhan tim 100%.',
        ]);

        // Candidate 3: Rian Hidayat (Stage: Hired)
        $user3 = User::create([
            'name' => 'Rian Hidayat',
            'email' => 'kandidat3@example.com',
            'password' => Hash::make('password'),
            'role' => 'candidate',
        ]);

        $candidate3 = Candidate::create([
            'user_id' => $user3->id,
            'phone' => '082112233445',
            'skills' => ['JavaScript', 'Tailwind CSS', 'Vite', 'React', 'HTML5'],
            'work_history' => "4 tahun bekerja sebagai Senior Frontend Engineer di TechCorp C.\n- Memimpin migrasi stack frontend ke React + Vite.\n- Meningkatkan skor performa Lighthouse sebesar 40%.\n- Menerapkan arsitektur CSS modern dengan Tailwind CSS.",
            'cv_path' => null,
            'portfolio_path' => null,
        ]);

        $app3 = Application::create([
            'candidate_id' => $candidate3->id,
            'job_title' => 'Frontend Engineer',
            'company_name' => 'Web Rekrutmen Corp',
            'status' => 'Hired',
        ]);

        InterviewScore::create([
            'application_id' => $app3->id,
            'interviewer_id' => $hrd1->id,
            'rating' => 5,
            'notes' => 'Kemampuan teknis frontend luar biasa. Sangat menguasai optimasi web performance.',
        ]);
    }
}
