<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_yearly_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->string('school_year'); // e.g., 2023-2024
            $table->string('department')->nullable();
            $table->string('position')->nullable(); // e.g., Head Teacher, Subject Teacher, etc.
            $table->json('subjects_taught')->nullable(); // Array of subjects taught
            $table->json('grade_levels_handled')->nullable(); // Array of grade levels
            $table->string('advisory_section')->nullable(); // If teacher is an adviser
            $table->integer('total_students')->default(0); // Total students handled
            $table->decimal('teaching_load', 5, 2)->default(0.00); // Teaching load in hours
            $table->string('employment_status')->default('regular'); // regular, substitute, part-time
            $table->string('status')->default('active'); // active, inactive, transferred, resigned
            $table->text('notes')->nullable(); // Additional notes for the year
            $table->date('start_date')->nullable(); // Start date for the school year
            $table->date('end_date')->nullable(); // End date for the school year
            $table->timestamps();
            
            // Ensure unique record per teacher per school year
            $table->unique(['teacher_id', 'school_year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_yearly_records');
    }
};
