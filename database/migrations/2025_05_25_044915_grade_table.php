<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');

            // Quarterly Grades
            $table->decimal('quarter_1', 5, 2)->nullable();
            $table->decimal('quarter_2', 5, 2)->nullable();
            $table->decimal('quarter_3', 5, 2)->nullable();
            $table->decimal('quarter_4', 5, 2)->nullable();

            // Computed Grades
            $table->decimal('final_grade', 5, 2)->nullable();
            $table->decimal('general_average', 5, 2)->nullable();

            // Academic Period
            $table->string('school_year')->nullable(); // e.g., 2024-2025
            $table->string('grading_period')->nullable(); // Q1, Q2, Q3, Q4, Final

            // Additional Information
            $table->string('remarks')->nullable(); // Passed, Failed, Incomplete
            $table->text('notes')->nullable(); // Teacher notes

            $table->timestamps();

            // Ensure unique grade entry per student-subject-grading period
            $table->unique(['student_id', 'subject_id', 'grading_period', 'school_year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('grades');
    }
}; 