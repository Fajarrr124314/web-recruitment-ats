<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\ApplicationAnswer;
use App\Models\Candidate;
use App\Models\JobPosition;
use App\Models\RecruitmentRequirement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyCandidatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Recruitment Requirements for Education & Experience exist
        $eduReq = RecruitmentRequirement::where('question', 'like', '%pendidikan%')->first();
        if (!$eduReq) {
            $eduReq = RecruitmentRequirement::create([
                'type' => 'text',
                'question' => 'Pendidikan Terakhir (D3/S1/S2/S3 - Jurusan)',
                'is_required' => true,
                'is_active' => true,
                'order' => 6,
            ]);
        }

        $expReq = RecruitmentRequirement::where('question', 'like', '%pengalaman%')->first();
        if (!$expReq) {
            $expReq = RecruitmentRequirement::create([
                'type' => 'text',
                'question' => 'Total Pengalaman Kerja (Tahun)',
                'is_required' => true,
                'is_active' => true,
                'order' => 7,
            ]);
        }

        // Get existing standard requirements from print_r output
        $reqs = RecruitmentRequirement::all()->keyBy('id');
        $fullNameReqId = RecruitmentRequirement::where('question', 'like', '%nama%')->first()->id ?? 1;
        $birthReqId = RecruitmentRequirement::where('question', 'like', '%lahir%')->first()->id ?? 2;
        $genderReqId = RecruitmentRequirement::where('question', 'like', '%kelamin%')->first()->id ?? 7;
        $phoneReqId = RecruitmentRequirement::where('question', 'like', '%hp%')->first()->id ?? 9;
        $emailReqId = RecruitmentRequirement::where('question', 'like', '%email%')->first()->id ?? 10;
        $cvReqId = RecruitmentRequirement::where('question', 'like', '%cv%')->first()->id ?? 11;

        // Active Job Positions in database
        $positions = JobPosition::pluck('title')->toArray();
        if (empty($positions)) {
            $positions = ['Operator Produksi', 'Digital Marketing', 'Staff QC', 'IT Support'];
        }

        // Dummy Data Arrays
        $firstNames = ['Ahmad', 'Budi', 'Chandra', 'Dedi', 'Eko', 'Fajar', 'Guntur', 'Hadi', 'Iwan', 'Joko', 'Kurnia', 'Laksana', 'Mega', 'Novi', 'Oki', 'Putra', 'Qori', 'Rian', 'Siti', 'Taufik', 'Utama', 'Vina', 'Wawan', 'Yudi', 'Zainal', 'Aditya', 'Bagus', 'Citra', 'Dian', 'Elisa', 'Fitri', 'Gita', 'Hendra', 'Indra', 'Joni', 'Kartika', 'Lestari', 'Mulyadi', 'Nanda', 'Pratiwi', 'Rudi', 'Sari', 'Tri', 'Wahyu', 'Yanti'];
        $lastNames = ['Pratama', 'Hidayat', 'Santoso', 'Wijaya', 'Saputra', 'Setiawan', 'Kusuma', 'Siregar', 'Lubis', 'Nasution', 'Ginting', 'Sitorus', 'Manurung', 'Sihombing', 'Simanjuntak', 'Pangaribuan', 'Sinaga', 'Harahap', 'Tanjung', 'Pasaribu', 'Lestari', 'Utami', 'Wulandari', 'Putri', 'Sari', 'Indah', 'Rahmawati', 'Anggraini', 'Dewi', 'Permata', 'Kartika', 'Amalia', 'Safitri', 'Hidayah', 'Aulia', 'Fitriani', 'Ramadhani', 'Pratiwi', 'Ningsih', 'Setyowati', 'Sulistyo', 'Susanto', 'Budiman', 'Gunawan', 'Hartono'];

        $skillsPool = [
            'IT Support' => [
                ['Troubleshooting', 'Networking', 'Mikrotik', 'Linux', 'Windows Server', 'Cisco'],
                ['Hardware Maintenance', 'CCTV Setup', 'Technical Support', 'Active Directory', 'DHCP/DNS'],
                ['Network Security', 'VPN Configuration', 'IT Helpdesk', 'Server Administration', 'Virtualization'],
                ['LAN/WAN', 'Router & Switch Configuration', 'Linux Sysadmin', 'Troubleshooting Hardware', 'MySQL'],
            ],
            'Digital Marketing' => [
                ['SEO', 'Copywriting', 'Google Analytics', 'Google Ads', 'Content Marketing', 'Social Media Management'],
                ['Facebook Ads', 'Instagram Marketing', 'Tiktok Creative', 'Canva', 'Email Marketing', 'SEO Optimization'],
                ['Content Writing', 'Digital Campaign', 'Growth Hacking', 'Brand Strategy', 'SEM', 'Market Analysis'],
                ['Social Media Engagement', 'Graphic Design Basics', 'Copywriting', 'E-commerce Management', 'KPI Tracking'],
            ],
            'Staff QC' => [
                ['Quality Control', 'ISO 9001', 'Inspection', 'Reporting', 'Analytical Thinking', 'Risk Assessment'],
                ['QA Testing', 'Quality Assurance', 'Process Improvement', 'Statistical Analysis', 'Documentation'],
                ['Defect Analysis', 'Calibration', 'Product Inspection', 'Safety Standards', 'Attention to Detail'],
                ['Laboratory Analysis', 'Good Manufacturing Practices (GMP)', 'Hazard Analysis (HACCP)', 'QC Auditing'],
            ],
            'Operator Produksi' => [
                ['Machine Operation', 'Safety Protocols', 'Assembly Line', 'Teamwork', 'Physical Stamina'],
                ['Quality Standards', 'Equipment Maintenance', 'Packaging', 'Troubleshooting Machines', '5S Methodology'],
                ['Production Logistics', 'Lean Manufacturing', 'SOP Adherence', 'Material Handling', 'Safety Standards'],
                ['High Precision Assembly', 'Technical Inspection', 'Manual Assembly', 'Machine Setting', 'Standard Operating Procedures'],
            ],
        ];

        $educationPool = [
            'IT Support' => [
                'S1 Teknik Informatika', 'S1 Sistem Informasi', 'D3 Sistem Informasi', 
                'S1 Rekayasa Perangkat Lunak', 'D3 Teknik Komputer', 'S1 Sistem Komputer',
                'S2 Ilmu Komputer'
            ],
            'Digital Marketing' => [
                'S1 Ilmu Komunikasi', 'S1 Manajemen Pemasaran', 'S1 Sistem Informasi',
                'S1 Sastra Inggris', 'S1 Desain Komunikasi Visual', 'D3 Hubungan Masyarakat',
                'S1 Administrasi Bisnis'
            ],
            'Staff QC' => [
                'S1 Teknik Industri', 'S1 Teknik Kimia', 'S1 Farmasi', 'S1 Biologi',
                'S1 Fisika', 'D3 Teknik Kimia', 'S1 Teknik Pangan'
            ],
            'Operator Produksi' => [
                'D3 Teknik Mesin', 'D3 Teknik Elektro', 'S1 Teknik Industri',
                'D3 Teknik Otomotif', 'D3 Teknik Sipil', 'S1 Pendidikan Teknik Mesin'
            ],
        ];

        $workHistories = [
            'IT Support' => [
                "Bekerja selama [EXP] tahun di PT Teknologi Global sebagai IT Support Specialist.\n- Mengelola infrastruktur jaringan lokal (LAN/WAN) dengan 100+ user aktif.\n- Menangani troubleshooting hardware, software, dan printer secara berkala.\n- Memelihara server Windows Server dan Linux OS.",
                "Berpengalaman [EXP] tahun sebagai Network Administrator di CV Netindo.\n- Melakukan setup dan konfigurasi router Mikrotik serta switch Cisco.\n- Memelihara keamanan jaringan dan memantau akses VPN.\n- Memberikan bantuan teknis (helpdesk) tingkat 1 dan 2 bagi karyawan.",
            ],
            'Digital Marketing' => [
                "Bekerja [EXP] tahun sebagai Digital Marketer di PT E-Commerce Sukses.\n- Mengelola kampanye iklan berbayar (Facebook Ads & Google Ads) dengan budget bulanan.\n- Mengoptimalkan SEO website utama dan meningkatkan traffic organik hingga 50%.\n- Merancang copywriting menarik untuk newsletter mingguan.",
                "Berpengalaman [EXP] tahun sebagai Social Media Specialist di Agensi Kreatif Cipta.\n- Membuat content planning kreatif bulanan untuk Instagram dan Tiktok klien.\n- Melakukan riset pasar, analisis kompetitor, dan tracking performa campaign via Analytics.\n- Berkolaborasi dengan desainer grafis menggunakan Canva dan Figma.",
            ],
            'Staff QC' => [
                "Bekerja selama [EXP] tahun sebagai Staff Quality Control di PT Manufaktur Jaya.\n- Melakukan inspeksi kualitas bahan baku masuk (incoming QC) sesuai standar perusahaan.\n- Memastikan kepatuhan proses produksi terhadap standar ISO 9001.\n- Membuat laporan harian analisis defect/cacat produk kepada Manager QC.",
                "Berpengalaman [EXP] tahun sebagai QC Analyst di PT Global Chemical.\n- Melakukan pengujian lab terhadap sampel produk jadi (outgoing QC).\n- Melakukan kalibrasi rutin pada peralatan pengujian laboratorium.\n- Menerapkan metodologi GMP (Good Manufacturing Practices) di area produksi.",
            ],
            'Operator Produksi' => [
                "Berpengalaman selama [EXP] tahun sebagai Operator Produksi di PT Otomotif Prima.\n- Mengoperasikan mesin cetak logam presisi tinggi di jalur perakitan utama.\n- Mematuhi standar keselamatan kerja (K3) dan SOP produksi secara ketat.\n- Membantu pemeliharaan rutin harian mesin dan kebersihan area kerja (5S).",
                "Bekerja [EXP] tahun di PT Elektronik Maju sebagai Operator Assembly.\n- Melakukan perakitan sirkuit elektronik mikro dengan standar kualitas tinggi.\n- Mencapai target output produksi harian konsisten di atas 105%.\n- Melakukan inspeksi manual visual terhadap produk sebelum diteruskan ke QC.",
            ]
        ];

        $stages = ['Administrasi', 'Psikotes', 'Interview', 'MCU'];

        $this->command->info("Starting seeding of 200 premium dummy candidates...");

        for ($i = 1; $i <= 200; $i++) {
            // Generate names & email
            $fn = $firstNames[array_rand($firstNames)];
            $ln = $lastNames[array_rand($lastNames)];
            $name = $fn . ' ' . $ln;
            $email = strtolower($fn . '.' . $ln . $i . '@example.com');
            $phone = '0812' . rand(10000000, 99999999);
            
            // Randomize position, education, experience and stage
            $jobTitle = $positions[array_rand($positions)];
            
            // Education & Major
            $eduList = $educationPool[$jobTitle] ?? ['S1 Teknik Informatika', 'S1 Sistem Informasi'];
            $education = $eduList[array_rand($eduList)];
            
            // Experience Year
            $experienceYears = rand(1, 8);
            $experienceStr = $experienceYears . ' Tahun';
            
            // Stage
            $stage = $stages[array_rand($stages)];
            
            // User record
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'candidate',
            ]);

            // Skills
            $skillsChoices = $skillsPool[$jobTitle] ?? [['PHP', 'Laravel']];
            $skills = $skillsChoices[array_rand($skillsChoices)];

            // Work History
            $historyTemplates = $workHistories[$jobTitle] ?? ["Bekerja [EXP] tahun..."];
            $workHistory = str_replace('[EXP]', $experienceYears, $historyTemplates[array_rand($historyTemplates)]);
            
            // Candidate record
            $candidate = Candidate::create([
                'user_id' => $user->id,
                'phone' => $phone,
                'skills' => $skills,
                'work_history' => $workHistory,
                'cv_path' => 'dynamic_files/dummy_cv.pdf',
            ]);

            // Application record
            $application = Application::create([
                'candidate_id' => $candidate->id,
                'job_title' => $jobTitle,
                'company_name' => 'Web Rekrutmen Corp',
                'status' => $stage,
            ]);

            // Answers
            // 1. Nama Lengkap
            ApplicationAnswer::create([
                'application_id' => $application->id,
                'recruitment_requirement_id' => $fullNameReqId,
                'answer' => $name,
            ]);

            // 2. Tempat, tgl lahir
            $birthplaces = ['Jakarta', 'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Medan', 'Makassar', 'Palembang'];
            $bplace = $birthplaces[array_rand($birthplaces)];
            $bdate = rand(1, 28) . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . rand(1993, 2004);
            ApplicationAnswer::create([
                'application_id' => $application->id,
                'recruitment_requirement_id' => $birthReqId,
                'answer' => $bplace . ', ' . $bdate,
            ]);

            // 3. Jenis Kelamin
            $genders = ['Laki - Laki', 'Perempuan'];
            $gender = $genders[array_rand($genders)];
            ApplicationAnswer::create([
                'application_id' => $application->id,
                'recruitment_requirement_id' => $genderReqId,
                'answer' => $gender,
            ]);

            // 4. No HP aktif
            ApplicationAnswer::create([
                'application_id' => $application->id,
                'recruitment_requirement_id' => $phoneReqId,
                'answer' => $phone,
            ]);

            // 5. Email
            ApplicationAnswer::create([
                'application_id' => $application->id,
                'recruitment_requirement_id' => $emailReqId,
                'answer' => $email,
            ]);

            // 6. CV
            ApplicationAnswer::create([
                'application_id' => $application->id,
                'recruitment_requirement_id' => $cvReqId,
                'answer' => 'dynamic_files/dummy_cv.pdf',
            ]);

            // 7. Pendidikan Terakhir (Custom Requirement)
            ApplicationAnswer::create([
                'application_id' => $application->id,
                'recruitment_requirement_id' => $eduReq->id,
                'answer' => $education,
            ]);

            // 8. Total Pengalaman Kerja (Tahun) (Custom Requirement)
            ApplicationAnswer::create([
                'application_id' => $application->id,
                'recruitment_requirement_id' => $expReq->id,
                'answer' => $experienceStr,
            ]);
        }

        $this->command->info("200 premium dummy candidates successfully seeded!");
    }
}
