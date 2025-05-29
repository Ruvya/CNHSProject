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
        Schema::table('student_subject', function (Blueprint $table) {
            // Add grading_period column if it doesn't exist
            if (!Schema::hasColumn('student_subject', 'grading_period')) {
                $table->string('grading_period')->nullable()->after('school_year');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_subject', function (Blueprint $table) {
            // Remove grading_period column if it exists
            if (Schema::hasColumn('student_subject', 'grading_period')) {
                $table->dropColumn('grading_period');
            }
        });
    }
};
