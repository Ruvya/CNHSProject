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
            $table->decimal('grade', 5, 2)->nullable(); // For storing grades like 85.50
            $table->string('quarter')->nullable(); // Q1, Q2, Q3, Q4, Final
            $table->string('school_year')->nullable(); // 2024-2025
            $table->text('remarks')->nullable(); // Additional notes
            $table->timestamps();

            // Ensure a student can only be enrolled once per subject
            $table->unique(['student_id', 'subject_id']);
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
