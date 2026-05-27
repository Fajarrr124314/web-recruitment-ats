<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom 'stage' ke tabel interview_scores agar setiap penilaian
     * dapat diidentifikasi tahap rekrutmen mana yang sedang dievaluasi.
     */
    public function up(): void
    {
        Schema::table('interview_scores', function (Blueprint $table) {
            $table->string('stage', 50)->nullable()->after('notes')
                  ->comment('Tahap rekrutmen: Administrasi, Psikotes, Interview, MCU');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_scores', function (Blueprint $table) {
            $table->dropColumn('stage');
        });
    }
};
