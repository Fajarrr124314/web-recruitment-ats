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
        Schema::table('recruitment_requirements', function (Blueprint $table) {
            // Change type column from enum to string to support new input types
            $table->string('type')->default('text')->change();
            // Add options column to configure select choices
            $table->text('options')->nullable()->after('question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recruitment_requirements', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
};
