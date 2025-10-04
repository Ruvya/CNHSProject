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
        // Update teacher_assignments table to include "Both Semesters" option
        if (Schema::hasColumn('teacher_assignments', 'semester')) {
            // For MySQL, we need to modify the enum column
            DB::statement("ALTER TABLE teacher_assignments MODIFY COLUMN semester ENUM('1st Semester', '2nd Semester', 'Both Semesters') NULL");
        }

        // Update subjects table to include "Both Semesters" option if it has semester column
        if (Schema::hasColumn('subjects', 'semester')) {
            DB::statement("ALTER TABLE subjects MODIFY COLUMN semester ENUM('1st Semester', '2nd Semester', 'Both Semesters') NULL");
        }

        // Update sections table to include "Both Semesters" option if it has semester column
        if (Schema::hasColumn('sections', 'semester')) {
            DB::statement("ALTER TABLE sections MODIFY COLUMN semester ENUM('1st Semester', '2nd Semester', 'Both Semesters') NULL");
        }

        // Update grades table to include "Both Semesters" option if it has semester column
        if (Schema::hasColumn('grades', 'semester')) {
            DB::statement("ALTER TABLE grades MODIFY COLUMN semester ENUM('1st Semester', '2nd Semester', 'Both Semesters') NULL");
        }

        // Update schedules table to include "Both Semesters" option if it exists and has semester column
        if (Schema::hasTable('schedules') && Schema::hasColumn('schedules', 'semester')) {
            DB::statement("ALTER TABLE schedules MODIFY COLUMN semester ENUM('1st Semester', '2nd Semester', 'Both Semesters') NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        if (Schema::hasColumn('teacher_assignments', 'semester')) {
            DB::statement("ALTER TABLE teacher_assignments MODIFY COLUMN semester ENUM('1st Semester', '2nd Semester') NULL");
        }

        if (Schema::hasColumn('subjects', 'semester')) {
            DB::statement("ALTER TABLE subjects MODIFY COLUMN semester ENUM('1st Semester', '2nd Semester') NULL");
        }

        if (Schema::hasColumn('sections', 'semester')) {
            DB::statement("ALTER TABLE sections MODIFY COLUMN semester ENUM('1st Semester', '2nd Semester') NULL");
        }

        if (Schema::hasColumn('grades', 'semester')) {
            DB::statement("ALTER TABLE grades MODIFY COLUMN semester ENUM('1st Semester', '2nd Semester') NULL");
        }

        if (Schema::hasTable('schedules') && Schema::hasColumn('schedules', 'semester')) {
            DB::statement("ALTER TABLE schedules MODIFY COLUMN semester ENUM('1st Semester', '2nd Semester') NULL");
        }
    }
};