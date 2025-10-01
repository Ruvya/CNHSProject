<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add section_id back if missing
        if (!Schema::hasColumn('teacher_assignments', 'section_id')) {
            Schema::table('teacher_assignments', function (Blueprint $table) {
                $table->unsignedBigInteger('section_id')->nullable()->after('subject_id');
            });

            // Add foreign key (ignore if sections table missing in rare cases)
            try {
                Schema::table('teacher_assignments', function (Blueprint $table) {
                    $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
                });
            } catch (\Throwable $e) {
                // Proceed if FK cannot be created (e.g., due to existing data); can be fixed later.
            }
        }

        // Drop unique indexes that do NOT include section_id
        try {
            DB::statement("ALTER TABLE teacher_assignments DROP INDEX unique_teacher_subject_assignment");
        } catch (\Throwable $e) {}
        try {
            DB::statement("ALTER TABLE teacher_assignments DROP INDEX unique_teacher_assignment_fixed");
        } catch (\Throwable $e) {}
        try {
            DB::statement("ALTER TABLE teacher_assignments DROP INDEX unique_teacher_assignment_no_section");
        } catch (\Throwable $e) {}

        // Create unique index that includes section_id
        try {
            DB::statement("ALTER TABLE teacher_assignments ADD UNIQUE KEY unique_teacher_assignment (teacher_id, subject_id, section_id, school_year, grading_period)");
        } catch (\Throwable $e) {
            // If it already exists, ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the composite unique with section_id
        try {
            DB::statement("ALTER TABLE teacher_assignments DROP INDEX unique_teacher_assignment");
        } catch (\Throwable $e) {}

        // Recreate the unique index without section_id
        try {
            DB::statement("ALTER TABLE teacher_assignments ADD UNIQUE KEY unique_teacher_subject_assignment (teacher_id, subject_id, school_year, grading_period)");
        } catch (\Throwable $e) {}

        // Drop FK then column
        try {
            // Drop any FK on section_id
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'teacher_assignments'
                  AND COLUMN_NAME = 'section_id'
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            foreach ($foreignKeys as $fk) {
                try {
                    DB::statement("ALTER TABLE teacher_assignments DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                } catch (\Throwable $e) {}
            }
        } catch (\Throwable $e) {}

        if (Schema::hasColumn('teacher_assignments', 'section_id')) {
            Schema::table('teacher_assignments', function (Blueprint $table) {
                $table->dropColumn('section_id');
            });
        }
    }
}; 