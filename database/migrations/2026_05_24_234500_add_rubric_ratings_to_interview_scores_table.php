<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('interview_scores', function (Blueprint $table) {
            $table->integer('technical_rating')->default(3)->after('rating');
            $table->integer('communication_rating')->default(3)->after('technical_rating');
            $table->integer('problem_solving_rating')->default(3)->after('communication_rating');
            $table->integer('culture_fit_rating')->default(3)->after('problem_solving_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interview_scores', function (Blueprint $table) {
            $table->dropColumn([
                'technical_rating',
                'communication_rating',
                'problem_solving_rating',
                'culture_fit_rating'
            ]);
        });
    }
};
