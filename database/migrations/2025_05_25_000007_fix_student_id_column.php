<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if students table exists
        if (Schema::hasTable('students')) {
            // Create a backup of the students table
            DB::statement('CREATE TABLE students_backup LIKE students');
            DB::statement('INSERT INTO students_backup SELECT * FROM students');
            
            // Drop the existing table
            Schema::dropIfExists('students');
            
            // Create the table with the correct structure
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('middle_name')->nullable();
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('student_id')->unique(); // Changed from increments to string
                $table->string('password');
                $table->string('grade_level');
                $table->string('gender');
                $table->timestamps();
            });
            
            // Try to restore data from backup
            try {
                // Get column names from new table
                $columns = Schema::getColumnListing('students');
                $columnList = implode(', ', array_filter($columns, function($col) {
                    return $col != 'id'; // Skip the id column as it's auto-increment
                }));
                
                // Insert data from backup
                DB::statement("INSERT INTO students ($columnList) SELECT $columnList FROM students_backup");
                
                // Drop backup table
                DB::statement('DROP TABLE students_backup');
            } catch (\Exception $e) {
                // If restore fails, keep the backup table for manual recovery
                \Log::error('Failed to restore students data: ' . $e->getMessage());
            }
        } else {
            // Create the table if it doesn't exist
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('middle_name')->nullable();
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('student_id')->unique();
                $table->string('password');
                $table->string('grade_level');
                $table->string('gender');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // No down method needed
    }
};