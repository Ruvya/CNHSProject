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
            // Add name column if it doesn't exist
            if (!Schema::hasColumn('subjects', 'name')) {
                $table->string('name')->nullable()->after('id');
            }

            // Add code column if it doesn't exist
            if (!Schema::hasColumn('subjects', 'code')) {
                $table->string('code')->nullable()->after('name');
            }
        });

        // Copy data from subject_name to name and subject_code to code if they exist
        if (Schema::hasColumn('subjects', 'subject_name') && Schema::hasColumn('subjects', 'name')) {
            DB::statement('UPDATE subjects SET name = subject_name WHERE name IS NULL AND subject_name IS NOT NULL');
        }

        if (Schema::hasColumn('subjects', 'subject_code') && Schema::hasColumn('subjects', 'code')) {
            DB::statement('UPDATE subjects SET code = subject_code WHERE code IS NULL AND subject_code IS NOT NULL');
        }

        // Make name and code required after copying data
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'name')) {
                $table->string('name')->nullable(false)->change();
            }
            if (Schema::hasColumn('subjects', 'code')) {
                $table->string('code')->nullable(false)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('subjects', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};
