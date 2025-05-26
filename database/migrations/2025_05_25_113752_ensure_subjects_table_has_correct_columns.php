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
        // Check if subjects table exists, if not create it
        if (!Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('grade_level');
                $table->integer('units')->default(3);
                $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('set null');
                $table->text('description')->nullable();
                $table->string('track')->nullable();
                $table->string('strand')->nullable();
                $table->string('semester')->nullable(); // Add semester field
                $table->string('department')->nullable(); // Add department field
                $table->boolean('is_master_subject')->default(true); // Distinguish master subjects
                $table->timestamps();
            });
        } else {
            // Table exists, ensure it has the required columns
            Schema::table('subjects', function (Blueprint $table) {
                $columns = Schema::getColumnListing('subjects');

                if (!in_array('name', $columns)) {
                    $table->string('name')->after('id');
                }
                if (!in_array('code', $columns)) {
                    $table->string('code')->after('name');
                }
                if (!in_array('grade_level', $columns)) {
                    $table->string('grade_level')->after('code');
                }
                if (!in_array('units', $columns)) {
                    $table->integer('units')->default(3)->after('grade_level');
                }
                if (!in_array('teacher_id', $columns)) {
                    $table->foreignId('teacher_id')->nullable()->after('units');
                }
                if (!in_array('description', $columns)) {
                    $table->text('description')->nullable()->after('teacher_id');
                }
                if (!in_array('track', $columns)) {
                    $table->string('track')->nullable()->after('description');
                }
                if (!in_array('strand', $columns)) {
                    $table->string('strand')->nullable()->after('track');
                }
            });

            // Copy data from old columns if they exist
            $columns = Schema::getColumnListing('subjects');
            if (in_array('subject_name', $columns) && in_array('name', $columns)) {
                DB::statement('UPDATE subjects SET name = subject_name WHERE name IS NULL OR name = ""');
            }
            if (in_array('subject_code', $columns) && in_array('code', $columns)) {
                DB::statement('UPDATE subjects SET code = subject_code WHERE code IS NULL OR code = ""');
            }
        }

        // Ensure code column is unique
        try {
            Schema::table('subjects', function (Blueprint $table) {
                $table->unique('code');
            });
        } catch (\Exception $e) {
            // Unique constraint might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table, just remove columns we added
        Schema::table('subjects', function (Blueprint $table) {
            $columns = Schema::getColumnListing('subjects');

            if (in_array('name', $columns) && in_array('subject_name', $columns)) {
                $table->dropColumn('name');
            }
            if (in_array('code', $columns) && in_array('subject_code', $columns)) {
                $table->dropColumn('code');
            }
        });
    }
};
