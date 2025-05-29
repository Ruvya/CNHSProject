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
        // Only run if subjects table exists
        if (Schema::hasTable('subjects')) {
            Schema::table('subjects', function (Blueprint $table) {
                // Add grading column if it doesn't exist
                if (!Schema::hasColumn('subjects', 'grading')) {
                    $table->string('grading')->nullable()->after('strand');
                }
            });

            // Migrate existing semester data to grading
            if (Schema::hasColumn('subjects', 'semester')) {
                DB::statement("UPDATE subjects SET grading = CASE
                    WHEN semester = '1st Semester' THEN 'First Grading'
                    WHEN semester = '2nd Semester' THEN 'Second Grading'
                    WHEN semester = 'Both Semesters' THEN 'All Gradings'
                    ELSE 'First Grading'
                END WHERE grading IS NULL");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('subjects')) {
            Schema::table('subjects', function (Blueprint $table) {
                if (Schema::hasColumn('subjects', 'grading')) {
                    $table->dropColumn('grading');
                }
            });
        }
    }
};
