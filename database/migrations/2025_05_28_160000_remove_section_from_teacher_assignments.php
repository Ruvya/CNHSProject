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
            // First drop the unique constraint that includes section_id
            try {
                $table->dropUnique('unique_teacher_assignment');
            } catch (\Exception $e) {
                // Constraint might not exist, continue
            }

            // Remove section_id column if it exists
            if (Schema::hasColumn('teacher_assignments', 'section_id')) {
                // First drop the foreign key constraint
                try {
                    $table->dropForeign(['section_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, continue
                }
                // Then drop the column
                $table->dropColumn('section_id');
            }

            // Add new unique constraint without section_id
            $table->unique(['teacher_id', 'subject_id', 'school_year', 'grading_period'], 'unique_teacher_assignment_no_section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_assignments', function (Blueprint $table) {
            // Drop the new unique constraint
            try {
                $table->dropUnique('unique_teacher_assignment_no_section');
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
