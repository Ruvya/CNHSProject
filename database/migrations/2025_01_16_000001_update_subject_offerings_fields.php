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
        // First, add the new columns
        Schema::table('subject_offerings', function (Blueprint $table) {
            if (!Schema::hasColumn('subject_offerings', 'grading')) {
                $table->string('grading')->nullable()->after('school_year');
            }
            if (!Schema::hasColumn('subject_offerings', 'track')) {
                $table->string('track')->nullable()->after('grade_level');
            }
        });

        // Migrate data from semester to grading
        if (Schema::hasColumn('subject_offerings', 'semester')) {
            DB::statement("UPDATE subject_offerings SET grading = CASE 
                WHEN semester = '1st Semester' THEN 'First Grading'
                WHEN semester = '2nd Semester' THEN 'Second Grading'
                ELSE 'First Grading'
            END WHERE grading IS NULL");
        }

        // Migrate data from section to track (default to STEM if not recognizable)
        if (Schema::hasColumn('subject_offerings', 'section')) {
            DB::statement("UPDATE subject_offerings SET track = CASE 
                WHEN UPPER(section) LIKE '%ABM%' THEN 'ABM'
                WHEN UPPER(section) LIKE '%STEM%' THEN 'STEM'
                WHEN UPPER(section) LIKE '%HUMSS%' THEN 'HUMSS'
                WHEN UPPER(section) LIKE '%TVL%' THEN 'TVL'
                ELSE 'STEM'
            END WHERE track IS NULL");
        }

        // Remove the old columns
        Schema::table('subject_offerings', function (Blueprint $table) {
            if (Schema::hasColumn('subject_offerings', 'semester')) {
                $table->dropColumn('semester');
            }
            if (Schema::hasColumn('subject_offerings', 'section')) {
                $table->dropColumn('section');
            }
        });

        // Remove room column from subject_schedules
        Schema::table('subject_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('subject_schedules', 'room')) {
                $table->dropColumn('room');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the old columns
        Schema::table('subject_offerings', function (Blueprint $table) {
            if (!Schema::hasColumn('subject_offerings', 'semester')) {
                $table->string('semester')->nullable()->after('school_year');
            }
            if (!Schema::hasColumn('subject_offerings', 'section')) {
                $table->string('section')->nullable()->after('grade_level');
            }
        });

        // Migrate data back
        if (Schema::hasColumn('subject_offerings', 'grading')) {
            DB::statement("UPDATE subject_offerings SET semester = CASE 
                WHEN grading = 'First Grading' THEN '1st Semester'
                WHEN grading = 'Second Grading' THEN '2nd Semester'
                WHEN grading = 'Third Grading' THEN '1st Semester'
                WHEN grading = 'Fourth Grading' THEN '2nd Semester'
                ELSE '1st Semester'
            END WHERE semester IS NULL");
        }

        if (Schema::hasColumn('subject_offerings', 'track')) {
            DB::statement("UPDATE subject_offerings SET section = track WHERE section IS NULL");
        }

        // Remove the new columns
        Schema::table('subject_offerings', function (Blueprint $table) {
            if (Schema::hasColumn('subject_offerings', 'grading')) {
                $table->dropColumn('grading');
            }
            if (Schema::hasColumn('subject_offerings', 'track')) {
                $table->dropColumn('track');
            }
        });

        // Add back room column to subject_schedules
        Schema::table('subject_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('subject_schedules', 'room')) {
                $table->string('room')->nullable()->after('end_time');
            }
        });
    }
};
