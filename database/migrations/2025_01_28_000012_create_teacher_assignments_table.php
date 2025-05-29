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
        if (!Schema::hasTable('teacher_assignments')) {
            Schema::create('teacher_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
                $table->string('school_year'); // e.g., "2024-2025"
                $table->string('grading_period'); // "First Grading", "Second Grading", "Third Grading", "Fourth Grading"
                $table->json('schedule')->nullable(); // Store teaching schedule as JSON
                $table->date('assignment_date')->default(now());
                $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
                $table->foreignId('assigned_by')->constrained('registrars')->onDelete('cascade');
                $table->text('notes')->nullable();
                $table->timestamps();

                // Ensure unique teacher-subject-section assignment per term
                $table->unique(['teacher_id', 'subject_id', 'section_id', 'school_year', 'grading_period'], 'unique_teacher_assignment');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_assignments');
    }
};
