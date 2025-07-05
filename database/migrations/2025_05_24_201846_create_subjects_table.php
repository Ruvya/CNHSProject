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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Subject code (e.g., ENG101)
            $table->string('name'); // Subject name (e.g., English 1)
            $table->text('description')->nullable();

            // Academic Classification
            $table->string('grade_level'); // Grade 11, Grade 12, etc.
            $table->string('track')->nullable(); // Academic, TVL, Sports, Arts & Design
            $table->string('strand')->nullable(); // STEM, HUMSS, GAS, etc.

            // DepEd Curriculum Fields
            $table->string('subject_type')->nullable(); // Core, Applied, Specialized
            $table->string('semester')->nullable(); // 1st Semester, 2nd Semester, Whole Year
            $table->string('grading_period')->nullable(); // Q1, Q2, Q3, Q4, Final

            // Management
            $table->boolean('is_master_subject')->default(false);
            $table->foreignId('registrar_id')->nullable()->constrained('registrars')->onDelete('set null');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
