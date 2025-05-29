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
        Schema::table('student_assignments', function (Blueprint $table) {
            // Remove section_id column if it exists
            if (Schema::hasColumn('student_assignments', 'section_id')) {
                $table->dropColumn('section_id');
            }
            
            // Add new academic-based columns
            if (!Schema::hasColumn('student_assignments', 'grade_level')) {
                $table->string('grade_level')->after('grading_period');
            }
            
            if (!Schema::hasColumn('student_assignments', 'track')) {
                $table->string('track')->after('grade_level');
            }
            
            if (!Schema::hasColumn('student_assignments', 'strand')) {
                $table->string('strand')->after('track');
            }
            
            if (!Schema::hasColumn('student_assignments', 'subjects')) {
                $table->json('subjects')->nullable()->after('strand');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_assignments', function (Blueprint $table) {
            // Add back section_id column
            $table->unsignedBigInteger('section_id')->nullable()->after('student_id');
            
            // Remove academic-based columns
            if (Schema::hasColumn('student_assignments', 'grade_level')) {
                $table->dropColumn('grade_level');
            }
            
            if (Schema::hasColumn('student_assignments', 'track')) {
                $table->dropColumn('track');
            }
            
            if (Schema::hasColumn('student_assignments', 'strand')) {
                $table->dropColumn('strand');
            }
            
            if (Schema::hasColumn('student_assignments', 'subjects')) {
                $table->dropColumn('subjects');
            }
        });
    }
};
