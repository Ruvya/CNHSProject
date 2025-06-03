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
        // Drop student_assignments table if it exists
        if (Schema::hasTable('student_assignments')) {
            Schema::dropIfExists('student_assignments');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate student_assignments table if needed (for rollback)
        if (!Schema::hasTable('student_assignments')) {
            Schema::create('student_assignments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->unsignedBigInteger('section_id');
                $table->string('school_year');
                $table->string('grading_period');
                $table->date('assignment_date');
                $table->enum('status', ['active', 'transferred', 'dropped'])->default('active');
                $table->unsignedBigInteger('assigned_by');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
                $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
                $table->foreign('assigned_by')->references('id')->on('registrars')->onDelete('cascade');
                $table->unique(['student_id', 'school_year', 'grading_period'], 'unique_student_per_term');
            });
        }
    }
};
