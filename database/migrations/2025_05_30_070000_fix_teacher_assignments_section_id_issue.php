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
        // Use raw SQL to handle the complex constraint dependencies
        if (Schema::hasColumn('teacher_assignments', 'section_id')) {
            // First, get all foreign key constraints
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'teacher_assignments'
                AND COLUMN_NAME = 'section_id'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            // Drop foreign key constraints
            foreach ($foreignKeys as $fk) {
                try {
                    DB::statement("ALTER TABLE teacher_assignments DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                } catch (\Exception $e) {
                    // Continue if constraint doesn't exist
                }
            }

            // Drop unique constraints that include section_id
            try {
                DB::statement("ALTER TABLE teacher_assignments DROP INDEX unique_teacher_assignment");
            } catch (\Exception $e) {
                // Continue if index doesn't exist
            }

            try {
                DB::statement("ALTER TABLE teacher_assignments DROP INDEX unique_teacher_assignment_no_section");
            } catch (\Exception $e) {
                // Continue if index doesn't exist
            }

            // Drop the section_id column
            DB::statement("ALTER TABLE teacher_assignments DROP COLUMN section_id");

            // Add new unique constraint without section_id
            DB::statement("ALTER TABLE teacher_assignments ADD UNIQUE KEY unique_teacher_assignment_fixed (teacher_id, subject_id, school_year, grading_period)");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_assignments', function (Blueprint $table) {
            // Drop the new unique constraint
            try {
                $table->dropUnique('unique_teacher_assignment_fixed');
            } catch (\Exception $e) {
                // Constraint might not exist, continue
            }

            // Add back section_id column
            $table->unsignedBigInteger('section_id')->nullable()->after('subject_id');
            // Add back the foreign key constraint
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');

            // Add back the original unique constraint
            $table->unique(['teacher_id', 'subject_id', 'section_id', 'school_year', 'grading_period'], 'unique_teacher_assignment');
        });
    }
};
