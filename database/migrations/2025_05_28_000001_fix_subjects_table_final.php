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
        // Get current column listing
        $columns = Schema::getColumnListing('subjects');
        
        Schema::table('subjects', function (Blueprint $table) use ($columns) {
            // Ensure we have the correct columns
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
                $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null')->after('units');
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
            if (!in_array('cluster', $columns)) {
                $table->string('cluster')->nullable()->after('strand');
            }
            if (!in_array('specialization', $columns)) {
                $table->string('specialization')->nullable()->after('cluster');
            }
            if (!in_array('grading', $columns)) {
                $table->string('grading')->nullable()->after('specialization');
            }
            if (!in_array('semester', $columns)) {
                $table->string('semester')->nullable()->after('grading');
            }
            if (!in_array('is_master_subject', $columns)) {
                $table->boolean('is_master_subject')->default(false)->after('semester');
            }
            if (!in_array('is_core_subject', $columns)) {
                $table->boolean('is_core_subject')->default(false)->after('is_master_subject');
            }
            if (!in_array('prerequisite_subjects', $columns)) {
                $table->text('prerequisite_subjects')->nullable()->after('is_core_subject');
            }
            if (!in_array('registrar_id', $columns)) {
                $table->foreignId('registrar_id')->nullable()->constrained('registrars')->onDelete('set null')->after('prerequisite_subjects');
            }
        });

        // Migrate data from old columns to new columns if they exist
        $updatedColumns = Schema::getColumnListing('subjects');
        
        if (in_array('subject_name', $updatedColumns) && in_array('name', $updatedColumns)) {
            DB::statement('UPDATE subjects SET name = subject_name WHERE (name IS NULL OR name = "") AND subject_name IS NOT NULL');
        }
        
        if (in_array('subject_code', $updatedColumns) && in_array('code', $updatedColumns)) {
            DB::statement('UPDATE subjects SET code = subject_code WHERE (code IS NULL OR code = "") AND subject_code IS NOT NULL');
        }

        // Drop old columns if they exist and we have data in new columns
        Schema::table('subjects', function (Blueprint $table) use ($updatedColumns) {
            if (in_array('subject_name', $updatedColumns)) {
                $table->dropColumn('subject_name');
            }
            if (in_array('subject_code', $updatedColumns)) {
                $table->dropColumn('subject_code');
            }
        });

        // Ensure code column is unique
        try {
            Schema::table('subjects', function (Blueprint $table) {
                $table->unique('code');
            });
        } catch (\Exception $e) {
            // Unique constraint might already exist, ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Add back old columns
            $table->string('subject_name')->nullable();
            $table->string('subject_code')->nullable();
        });

        // Copy data back
        DB::statement('UPDATE subjects SET subject_name = name WHERE subject_name IS NULL');
        DB::statement('UPDATE subjects SET subject_code = code WHERE subject_code IS NULL');
    }
};
