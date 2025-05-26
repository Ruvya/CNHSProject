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
        Schema::create('subject_offerings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('registrar_id')->constrained('registrars')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->string('school_year'); // e.g., "2024-2025"
            $table->string('grading'); // e.g., "First Grading", "Second Grading"
            $table->string('grade_level'); // e.g., "Grade 11", "Grade 12"
            $table->string('track'); // e.g., "ABM", "STEM", "HUMSS", "TVL"
            $table->integer('max_students')->default(40);
            $table->integer('enrolled_students')->default(0);
            $table->enum('status', ['active', 'inactive', 'full'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Ensure unique offering per subject-school_year-semester-section
            $table->unique(['subject_id', 'school_year', 'semester', 'section'], 'unique_subject_offering');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_offerings');
    }
};
