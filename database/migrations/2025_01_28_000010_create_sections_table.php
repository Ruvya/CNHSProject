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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "11-ABM-A", "12-STEM-B"
            $table->string('grade_level'); // "Grade 11", "Grade 12"
            $table->string('track'); // "ABM", "STEM", "HUMSS", "TVL"
            $table->string('strand')->nullable(); // Specific strand if applicable
            $table->integer('max_capacity')->default(40);
            $table->integer('current_enrollment')->default(0);
            $table->string('school_year'); // e.g., "2024-2025"
            $table->string('grading_period'); // "First Grading", "Second Grading", "Third Grading", "Fourth Grading"
            $table->foreignId('adviser_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->string('room')->nullable(); // Classroom assignment
            $table->enum('status', ['active', 'inactive', 'full'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Ensure unique section per grade-track-school_year-grading_period
            $table->unique(['name', 'school_year', 'grading_period'], 'unique_section_per_term');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
