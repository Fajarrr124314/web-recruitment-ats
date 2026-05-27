<?php

namespace App\Support;

/**
 * StageRubric — Definisi rubrik evaluasi per tahap rekrutmen.
 *
 * Setiap tahap memiliki 4 dimensi penilaian yang relevan.
 * Kolom database tetap sama (technical_rating, communication_rating, dll.)
 * namun labelnya berbeda-beda sesuai konteks tahap.
 */
class StageRubric
{
    /**
     * Pemetaan kolom DB ke property Livewire.
     */
    public const COLUMN_TO_PROPERTY = [
        'technical_rating'       => 'technicalRating',
        'communication_rating'   => 'communicationRating',
        'problem_solving_rating' => 'problemSolvingRating',
        'culture_fit_rating'     => 'cultureFitRating',
    ];

    /**
     * Definisi dimensi rubrik per tahap.
     * Setiap dimensi: label (tampilan), key (kolom DB), desc (keterangan).
     */
    public static array $rubrics = [
        'Administrasi' => [
            [
                'label' => 'Kelengkapan Berkas',
                'key'   => 'technical_rating',
                'desc'  => 'CV, ijazah, sertifikat, dan dokumen pendukung',
                'icon'  => 'document',
            ],
            [
                'label' => 'Kesesuaian Pendidikan',
                'key'   => 'communication_rating',
                'desc'  => 'Jenjang & jurusan sesuai persyaratan',
                'icon'  => 'academic',
            ],
            [
                'label' => 'Kesesuaian Pengalaman',
                'key'   => 'problem_solving_rating',
                'desc'  => 'Lama & relevansi pengalaman kerja',
                'icon'  => 'briefcase',
            ],
            [
                'label' => 'Kesesuaian Kriteria',
                'key'   => 'culture_fit_rating',
                'desc'  => 'Memenuhi syarat umum lowongan',
                'icon'  => 'check',
            ],
        ],

        'Psikotes' => [
            [
                'label' => 'Kemampuan Kognitif',
                'key'   => 'technical_rating',
                'desc'  => 'IQ, logika, dan kemampuan berpikir',
                'icon'  => 'chip',
            ],
            [
                'label' => 'Stabilitas Emosional',
                'key'   => 'communication_rating',
                'desc'  => 'Kontrol emosi & ketahanan tekanan',
                'icon'  => 'heart',
            ],
            [
                'label' => 'Kemampuan Analitis',
                'key'   => 'problem_solving_rating',
                'desc'  => 'Analisis data dan penalaran abstrak',
                'icon'  => 'chart',
            ],
            [
                'label' => 'Kesesuaian Kepribadian',
                'key'   => 'culture_fit_rating',
                'desc'  => 'Profil kepribadian sesuai posisi',
                'icon'  => 'user',
            ],
        ],

        'Interview' => [
            [
                'label' => 'Kompetensi Teknikal',
                'key'   => 'technical_rating',
                'desc'  => 'Penguasaan skill & tools teknis',
                'icon'  => 'code',
            ],
            [
                'label' => 'Komunikasi',
                'key'   => 'communication_rating',
                'desc'  => 'Kejelasan, kelancaran, dan adaptasi',
                'icon'  => 'chat',
            ],
            [
                'label' => 'Problem Solving',
                'key'   => 'problem_solving_rating',
                'desc'  => 'Analisis, logika, dan pemecahan masalah',
                'icon'  => 'puzzle',
            ],
            [
                'label' => 'Culture Fit',
                'key'   => 'culture_fit_rating',
                'desc'  => 'Nilai, attitude, dan kecocokan budaya',
                'icon'  => 'star',
            ],
        ],

        'MCU' => [
            [
                'label' => 'Kesehatan Umum',
                'key'   => 'technical_rating',
                'desc'  => 'Kondisi kesehatan secara keseluruhan',
                'icon'  => 'health',
            ],
            [
                'label' => 'Bebas Kelainan Fisik',
                'key'   => 'communication_rating',
                'desc'  => 'Tidak ada kelainan fisik & bawaan',
                'icon'  => 'shield',
            ],
            [
                'label' => 'Kebugaran Jasmani',
                'key'   => 'problem_solving_rating',
                'desc'  => 'Kemampuan fisik sesuai tuntutan kerja',
                'icon'  => 'bolt',
            ],
            [
                'label' => 'Riwayat Penyakit',
                'key'   => 'culture_fit_rating',
                'desc'  => 'Tidak ada riwayat penyakit kronis',
                'icon'  => 'clipboard',
            ],
        ],
    ];

    /**
     * Ambil definisi dimensi rubrik untuk tahap tertentu.
     */
    public static function getDimensions(string $stage): array
    {
        return self::$rubrics[$stage] ?? self::$rubrics['Interview'];
    }

    /**
     * Hitung data slider (dengan nilai Livewire property saat ini) untuk view.
     * @param string $stage - Tahap rekrutmen aktif
     * @param object $component - Livewire component (untuk akses property)
     */
    public static function getSliderDimensions(string $stage, object $component): array
    {
        $dims = self::getDimensions($stage);
        return array_map(function ($dim) use ($component) {
            $propKey = self::COLUMN_TO_PROPERTY[$dim['key']];
            return array_merge($dim, [
                'livewire_key' => $propKey,
                'val'          => $component->{$propKey},
            ]);
        }, $dims);
    }

    /**
     * Ambil label-label dimensi untuk tahap tertentu.
     */
    public static function getLabels(string $stage): array
    {
        return array_column(self::getDimensions($stage), 'label');
    }

    /**
     * Warna badge per tahap untuk UI.
     */
    public static function getStageBadgeClass(string $stage): string
    {
        return match ($stage) {
            'Administrasi' => 'bg-blue-50 text-blue-600 border-blue-200',
            'Psikotes'     => 'bg-rose-50 text-rose-600 border-rose-200',
            'Interview'    => 'bg-amber-50 text-amber-600 border-amber-200',
            'MCU'          => 'bg-purple-50 text-purple-600 border-purple-200',
            default        => 'bg-slate-50 text-slate-600 border-slate-200',
        };
    }
}
