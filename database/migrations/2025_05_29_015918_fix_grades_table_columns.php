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
        // Check if grades table exists, if not create it
        if (!Schema::hasTable('grades')) {
            Schema::create('grades', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->decimal('quarter1', 5, 2)->nullable();
                $table->decimal('quarter2', 5, 2)->nullable();
                $table->decimal('quarter3', 5, 2)->nullable();
                $table->decimal('quarter4', 5, 2)->nullable();
                $table->decimal('final_grade', 5, 2)->nullable();
                $table->string('remarks')->nullable();
                $table->timestamps();

                // Add unique constraint to prevent duplicate records
                $table->unique(['student_id', 'subject_id']);
            });
        } else {
            // Table exists, check and add missing columns
            Schema::table('grades', function (Blueprint $table) {
                if (!Schema::hasColumn('grades', 'quarter1')) {
                    $table->decimal('quarter1', 5, 2)->nullable();
                }
                if (!Schema::hasColumn('grades', 'quarter2')) {
                    $table->decimal('quarter2', 5, 2)->nullable();
                }
                if (!Schema::hasColumn('grades', 'quarter3')) {
                    $table->decimal('quarter3', 5, 2)->nullable();
                }
                if (!Schema::hasColumn('grades', 'quarter4')) {
                    $table->decimal('quarter4', 5, 2)->nullable();
                }
                if (!Schema::hasColumn('grades', 'final_grade')) {
                    $table->decimal('final_grade', 5, 2)->nullable();
                }
                if (!Schema::hasColumn('grades', 'remarks')) {
                    $table->string('remarks')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table in down method to preserve data
        // Schema::dropIfExists('grades');
    }
};
