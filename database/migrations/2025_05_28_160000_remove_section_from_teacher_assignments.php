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
        Schema::table('teacher_assignments', function (Blueprint $table) {
            // Remove section_id column if it exists
            if (Schema::hasColumn('teacher_assignments', 'section_id')) {
                $table->dropColumn('section_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_assignments', function (Blueprint $table) {
            // Add back section_id column
            $table->unsignedBigInteger('section_id')->nullable()->after('subject_id');
        });
    }
};
