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
        // Note: student_assignments table functionality has been removed

        // Update teacher_assignments table
        if (Schema::hasTable('teacher_assignments')) {
            // Add grading_period column if it doesn't exist
            if (!Schema::hasColumn('teacher_assignments', 'grading_period')) {
                Schema::table('teacher_assignments', function (Blueprint $table) {
                    $table->string('grading_period')->nullable()->after('school_year');
                });
            }

            // Migrate data from semester to grading_period if semester column exists
            if (Schema::hasColumn('teacher_assignments', 'semester')) {
                DB::statement("UPDATE teacher_assignments SET grading_period = CASE
                    WHEN semester = '1st Semester' THEN 'First Grading'
                    WHEN semester = '2nd Semester' THEN 'Second Grading'
                    WHEN semester = 'Both Semesters' THEN 'All Gradings'
                    ELSE 'First Grading'
                END WHERE grading_period IS NULL");

                // Drop the old semester column
                Schema::table('teacher_assignments', function (Blueprint $table) {
                    $table->dropColumn('semester');
                });
            }
        }

        // Update sections table
        if (Schema::hasTable('sections')) {
            // Add grading_period column if it doesn't exist
            if (!Schema::hasColumn('sections', 'grading_period')) {
                Schema::table('sections', function (Blueprint $table) {
                    $table->string('grading_period')->nullable()->after('school_year');
                });
            }

            // Migrate data from semester to grading_period if semester column exists
            if (Schema::hasColumn('sections', 'semester')) {
                DB::statement("UPDATE sections SET grading_period = CASE
                    WHEN semester = '1st Semester' THEN 'First Grading'
                    WHEN semester = '2nd Semester' THEN 'Second Grading'
                    WHEN semester = 'Both Semesters' THEN 'All Gradings'
                    ELSE 'First Grading'
                END WHERE grading_period IS NULL");

                // Drop the old semester column
                Schema::table('sections', function (Blueprint $table) {
                    $table->dropColumn('semester');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: student_assignments table functionality has been removed

        if (Schema::hasTable('teacher_assignments')) {
            if (!Schema::hasColumn('teacher_assignments', 'semester')) {
                Schema::table('teacher_assignments', function (Blueprint $table) {
                    $table->string('semester')->nullable()->after('school_year');
                });
            }

            if (Schema::hasColumn('teacher_assignments', 'grading_period')) {
                DB::statement("UPDATE teacher_assignments SET semester = CASE
                    WHEN grading_period = 'First Grading' THEN '1st Semester'
                    WHEN grading_period = 'Second Grading' THEN '1st Semester'
                    WHEN grading_period = 'Third Grading' THEN '2nd Semester'
                    WHEN grading_period = 'Fourth Grading' THEN '2nd Semester'
                    WHEN grading_period = 'All Gradings' THEN 'Both Semesters'
                    ELSE '1st Semester'
                END WHERE semester IS NULL");

                Schema::table('teacher_assignments', function (Blueprint $table) {
                    $table->dropColumn('grading_period');
                });
            }
        }

        if (Schema::hasTable('sections')) {
            if (!Schema::hasColumn('sections', 'semester')) {
                Schema::table('sections', function (Blueprint $table) {
                    $table->string('semester')->nullable()->after('school_year');
                });
            }

            if (Schema::hasColumn('sections', 'grading_period')) {
                DB::statement("UPDATE sections SET semester = CASE
                    WHEN grading_period = 'First Grading' THEN '1st Semester'
                    WHEN grading_period = 'Second Grading' THEN '1st Semester'
                    WHEN grading_period = 'Third Grading' THEN '2nd Semester'
                    WHEN grading_period = 'Fourth Grading' THEN '2nd Semester'
                    WHEN grading_period = 'All Gradings' THEN 'Both Semesters'
                    ELSE '1st Semester'
                END WHERE semester IS NULL");

                Schema::table('sections', function (Blueprint $table) {
                    $table->dropColumn('grading_period');
                });
            }
        }
    }
};
