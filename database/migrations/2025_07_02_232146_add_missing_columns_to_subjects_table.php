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
            // Add missing columns that the Subject model expects
            // Place after an existing column; original table has 'track' but not 'strand'
            $table->string('cluster')->nullable()->after('track');
            $table->string('specialization')->nullable()->after('cluster');
            $table->string('grading')->nullable()->after('specialization');
            $table->boolean('is_core_subject')->default(false)->after('is_master_subject');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['cluster', 'specialization', 'grading', 'is_core_subject']);
        });
    }
};
