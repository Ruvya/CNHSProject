<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Drop the unique constraint first
            $table->dropUnique(['code']);
        });

        // Then modify the column to be nullable
        DB::statement('ALTER TABLE subjects MODIFY code VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, set any NULL values to a placeholder or empty string
        DB::statement("UPDATE subjects SET code = CONCAT('SUBJECT_', id) WHERE code IS NULL");

        // Then make it NOT NULL and add unique constraint back
        Schema::table('subjects', function (Blueprint $table) {
            DB::statement('ALTER TABLE subjects MODIFY code VARCHAR(255) NOT NULL');
            $table->unique('code');
        });
    }
};
