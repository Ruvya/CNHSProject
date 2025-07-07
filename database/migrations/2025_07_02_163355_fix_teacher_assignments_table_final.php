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
        // Check if section_id column exists and remove it if it does
        if (Schema::hasColumn('teacher_assignments', 'section_id')) {
            // First, drop any foreign key constraints on section_id
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
                } catch (\Exception $e) {
                    // Continue if constraint doesn't exist
                }
            }

            // Drop any unique constraints that might include section_id
            $indexes = DB::select("
                SELECT DISTINCT INDEX_NAME
                FROM information_schema.STATISTICS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'teacher_assignments'
                AND INDEX_NAME LIKE '%teacher_assignment%'
                AND NON_UNIQUE = 0
            ");

            foreach ($indexes as $index) {
                try {
                    DB::statement("ALTER TABLE teacher_assignments DROP INDEX {$index->INDEX_NAME}");
                } catch (\Exception $e) {
                    // Continue if index doesn't exist
                }
            }

            // Now drop the section_id column
            Schema::table('teacher_assignments', function (Blueprint $table) {
                $table->dropColumn('section_id');
            });
        }

        // Add new unique constraint without section_id
        try {
            DB::statement("ALTER TABLE teacher_assignments ADD UNIQUE KEY unique_teacher_subject_assignment (teacher_id, subject_id, school_year, grading_period)");
        } catch (\Exception $e) {
            // Index might already exist, continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back section_id column
        if (!Schema::hasColumn('teacher_assignments', 'section_id')) {
            Schema::table('teacher_assignments', function (Blueprint $table) {
                $table->unsignedBigInteger('section_id')->nullable()->after('subject_id');
                $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            });
        }

        // Drop the new unique constraint
        try {
            DB::statement("ALTER TABLE teacher_assignments DROP INDEX unique_teacher_subject_assignment");
        } catch (\Exception $e) {
            // Continue if index doesn't exist
        }
    }
};
