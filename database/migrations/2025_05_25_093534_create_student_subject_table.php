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
        Schema::create('student_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');

            // Enrollment Information
            $table->string('school_year')->nullable(); // 2024-2025
            $table->string('semester')->nullable(); // 1st Semester, 2nd Semester
            $table->string('grading_period')->nullable(); // Q1, Q2, Q3, Q4, Final
            $table->enum('enrollment_status', ['enrolled', 'dropped', 'completed'])->default('enrolled');

            // Grade Information (moved to grades table, but keeping for compatibility)
            $table->decimal('grade', 5, 2)->nullable(); // For storing grades like 85.50
            $table->text('remarks')->nullable(); // Additional notes

            $table->timestamps();

            // Ensure a student can only be enrolled once per subject per school year
            $table->unique(['student_id', 'subject_id', 'school_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_subject');
    }
};
