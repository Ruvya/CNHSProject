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
        // First, add the new semester columns (idempotent)
        if (!Schema::hasColumn('sections', 'semester')) {
            Schema::table('sections', function (Blueprint $table) {
                $table->enum('semester', ['1st Semester', '2nd Semester'])->nullable()->after('school_year');
            });
        }

        if (!Schema::hasColumn('teacher_assignments', 'semester')) {
            Schema::table('teacher_assignments', function (Blueprint $table) {
                $table->enum('semester', ['1st Semester', '2nd Semester'])->nullable()->after('school_year');
            });
        }

        if (!Schema::hasColumn('grades', 'semester')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->enum('semester', ['1st Semester', '2nd Semester'])->nullable()->after('school_year');
            });
        }

        if (Schema::hasTable('schedules') && !Schema::hasColumn('schedules', 'semester')) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->enum('semester', ['1st Semester', '2nd Semester'])->nullable()->after('school_year');
            });
        }

        // Convert existing data from quarterly to semester system
        $this->convertQuarterlyToSemester();

        // Now drop the old grading_period columns (safe if already dropped)
        if (Schema::hasColumn('sections', 'grading_period')) {
            Schema::table('sections', function (Blueprint $table) {
                $table->dropColumn('grading_period');
            });
        }

        if (Schema::hasColumn('teacher_assignments', 'grading_period')) {
            Schema::table('teacher_assignments', function (Blueprint $table) {
                $table->dropColumn('grading_period');
            });
        }

        if (Schema::hasColumn('grades', 'grading_period')) {
            Schema::table('grades', function (Blueprint $table) {
                $table->dropColumn('grading_period');
            });
        }

        if (Schema::hasTable('schedules') && Schema::hasColumn('schedules', 'grading_period')) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropColumn('grading_period');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to grading_period system
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn('semester');
            $table->string('grading_period')->after('school_year');
        });

        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->dropColumn('semester');
            $table->string('grading_period')->after('school_year');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn('semester');
            $table->string('grading_period')->after('school_year');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('semester');
            $table->string('grading_period')->after('school_year');
        });
    }

    /**
     * Convert existing quarterly data to semester system
     */
    private function convertQuarterlyToSemester(): void
    {
        // Convert sections
        if (Schema::hasColumn('sections', 'grading_period')) {
            DB::table('sections')
                ->whereIn('grading_period', ['First Grading', 'Second Grading'])
                ->update(['semester' => '1st Semester']);

            DB::table('sections')
                ->whereIn('grading_period', ['Third Grading', 'Fourth Grading'])
                ->update(['semester' => '2nd Semester']);
        }

        // Convert teacher assignments
        if (Schema::hasColumn('teacher_assignments', 'grading_period')) {
            DB::table('teacher_assignments')
                ->whereIn('grading_period', ['First Grading', 'Second Grading'])
                ->update(['semester' => '1st Semester']);

            DB::table('teacher_assignments')
                ->whereIn('grading_period', ['Third Grading', 'Fourth Grading'])
                ->update(['semester' => '2nd Semester']);
        }

        // Convert grades
        if (Schema::hasColumn('grades', 'grading_period')) {
            DB::table('grades')
                ->whereIn('grading_period', ['First Grading', 'Second Grading'])
                ->update(['semester' => '1st Semester']);

            DB::table('grades')
                ->whereIn('grading_period', ['Third Grading', 'Fourth Grading'])
                ->update(['semester' => '2nd Semester']);
        }

        // Convert schedules
        if (Schema::hasTable('schedules') && Schema::hasColumn('schedules', 'grading_period')) {
            DB::table('schedules')
                ->whereIn('grading_period', ['First Grading', 'Second Grading'])
                ->update(['semester' => '1st Semester']);

            DB::table('schedules')
                ->whereIn('grading_period', ['Third Grading', 'Fourth Grading'])
                ->update(['semester' => '2nd Semester']);
        }
    }
};
