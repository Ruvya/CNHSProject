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
        // Drop the existing unique index if it exists using raw SQL, since
        // wrapping dropUnique in the Blueprint closure won't catch runtime errors
        // thrown when the ALTER TABLE executes.
        try {
            DB::statement("ALTER TABLE `teacher_assignments` DROP INDEX `unique_teacher_assignment`");
        } catch (\Throwable $e) {
            // Index might not exist; ignore and proceed
        }

        // Drop the foreign key on section_id if it exists before modifying the table
        try {
            // Detect FK name dynamically in case it differs
            $fkName = DB::table('information_schema.KEY_COLUMN_USAGE')
                ->where('TABLE_SCHEMA', DB::getDatabaseName())
                ->where('TABLE_NAME', 'teacher_assignments')
                ->where('COLUMN_NAME', 'section_id')
                ->whereNotNull('CONSTRAINT_NAME')
                ->value('CONSTRAINT_NAME');

            if ($fkName) {
                DB::statement("ALTER TABLE `teacher_assignments` DROP FOREIGN KEY `{$fkName}`");
            }
        } catch (\Throwable $e) {
            // FK might not exist; ignore
        }

        Schema::table('teacher_assignments', function (Blueprint $table) {
            // Remove section_id column if it exists
            if (Schema::hasColumn('teacher_assignments', 'section_id')) {
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
