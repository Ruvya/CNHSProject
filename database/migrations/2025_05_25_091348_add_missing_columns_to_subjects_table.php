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
        Schema::table('subjects', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('subjects', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('subjects', 'code')) {
                $table->string('code')->unique()->after('name');
            }
            if (!Schema::hasColumn('subjects', 'grade_level')) {
                $table->string('grade_level')->after('code');
            }
            if (!Schema::hasColumn('subjects', 'units')) {
                $table->integer('units')->default(3)->after('grade_level');
            }
            if (!Schema::hasColumn('subjects', 'teacher_id')) {
                $table->foreignId('teacher_id')->nullable()->constrained()->after('units');
            }
            if (!Schema::hasColumn('subjects', 'description')) {
                $table->text('description')->nullable()->after('teacher_id');
            }
            if (!Schema::hasColumn('subjects', 'track')) {
                $table->string('track')->nullable()->after('description');
            }
            if (!Schema::hasColumn('subjects', 'strand')) {
                $table->string('strand')->nullable()->after('track');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['description', 'track', 'strand']);
        });
    }
};
