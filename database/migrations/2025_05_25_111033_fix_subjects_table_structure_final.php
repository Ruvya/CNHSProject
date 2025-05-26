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
        // First, check what columns exist
        $columns = Schema::getColumnListing('subjects');

        Schema::table('subjects', function (Blueprint $table) use ($columns) {
            // Add name column if it doesn't exist
            if (!in_array('name', $columns)) {
                $table->string('name')->nullable()->after('id');
            }

            // Add code column if it doesn't exist
            if (!in_array('code', $columns)) {
                $table->string('code')->nullable()->after('name');
            }
        });

        // Copy data from old columns to new columns if they exist
        if (in_array('subject_name', $columns) && in_array('name', Schema::getColumnListing('subjects'))) {
            DB::statement('UPDATE subjects SET name = subject_name WHERE name IS NULL AND subject_name IS NOT NULL');
        }

        if (in_array('subject_code', $columns) && in_array('code', Schema::getColumnListing('subjects'))) {
            DB::statement('UPDATE subjects SET code = subject_code WHERE code IS NULL AND subject_code IS NOT NULL');
        }

        // Make name and code required
        Schema::table('subjects', function (Blueprint $table) {
            $currentColumns = Schema::getColumnListing('subjects');

            if (in_array('name', $currentColumns)) {
                $table->string('name')->nullable(false)->change();
            }
            if (in_array('code', $currentColumns)) {
                $table->string('code')->nullable(false)->change();
            }
        });

        // Add unique constraint to code if it doesn't exist
        try {
            Schema::table('subjects', function (Blueprint $table) {
                $table->unique('code');
            });
        } catch (\Exception $e) {
            // Unique constraint already exists, ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $columns = Schema::getColumnListing('subjects');
            if (in_array('name', $columns)) {
                $table->dropColumn('name');
            }
            if (in_array('code', $columns)) {
                $table->dropColumn('code');
            }
        });
    }
};
