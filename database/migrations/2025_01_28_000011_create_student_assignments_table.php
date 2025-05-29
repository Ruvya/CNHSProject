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
        if (!Schema::hasTable('student_assignments')) {
            Schema::create('student_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
                $table->string('school_year'); // e.g., "2024-2025"
                $table->string('grading_period'); // "First Grading", "Second Grading", "Third Grading", "Fourth Grading"
                $table->date('assignment_date')->default(now());
                $table->enum('status', ['active', 'transferred', 'dropped'])->default('active');
                $table->foreignId('assigned_by')->constrained('registrars')->onDelete('cascade');
                $table->text('notes')->nullable();
                $table->timestamps();

                // Ensure a student can only be assigned to one section per academic term
                $table->unique(['student_id', 'school_year', 'grading_period'], 'unique_student_assignment_per_term');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_assignments');
    }
};
