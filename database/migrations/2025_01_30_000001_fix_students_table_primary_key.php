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
        // The current students table has student_id as primary key, but we need id as primary key
        // We need to restructure the table properly

        if (Schema::hasTable('students')) {
            // Get all existing data
            $existingStudents = DB::table('students')->get();

            // Drop the existing table
            Schema::drop('students');

            // Create the new table with proper structure
            Schema::create('students', function (Blueprint $table) {
                $table->id(); // Auto-incrementing primary key
                $table->string('student_id')->unique(); // Student identifier (not primary key)
                $table->string('first_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('name')->nullable();
                $table->string('email')->unique()->nullable();
                $table->string('password')->nullable();
                $table->string('grade_level')->nullable();
                $table->string('year_level')->nullable();
                $table->string('section')->nullable();
                $table->string('gender')->nullable();
                $table->string('lrn')->nullable();
                $table->string('profile_picture')->nullable();
                $table->string('contact_number')->nullable();
                $table->text('address')->nullable();
                $table->string('parent_name')->nullable();
                $table->string('parent_contact')->nullable();
                $table->string('advisor')->nullable();
                $table->string('track')->nullable();
                $table->string('strand')->nullable();
                $table->string('province')->nullable();
                $table->string('municipality')->nullable();
                $table->string('barangay')->nullable();
                $table->text('permanent_address')->nullable();
                $table->string('phone')->nullable();
                $table->string('social_media')->nullable();
                $table->string('emergency_name')->nullable();
                $table->string('emergency_phone')->nullable();
                $table->string('emergency_relationship')->nullable();
                $table->boolean('is_temporary_account')->default(false);
                $table->boolean('profile_completed')->default(false);
                $table->rememberToken();
                $table->timestamps();
            });

            // Restore the data
            foreach ($existingStudents as $student) {
                $studentArray = (array) $student;
                // The new table will auto-generate the 'id' field
                // Keep student_id as the student identifier

                DB::table('students')->insert($studentArray);
            }
        } else {
            // Create the students table with proper structure
            Schema::create('students', function (Blueprint $table) {
                $table->id(); // Auto-incrementing primary key
                $table->string('student_id')->unique();
                $table->string('first_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('name')->nullable();
                $table->string('email')->unique()->nullable();
                $table->string('password')->nullable();
                $table->string('grade_level')->nullable();
                $table->string('year_level')->nullable();
                $table->string('section')->nullable();
                $table->string('gender')->nullable();
                $table->string('lrn')->nullable();
                $table->string('profile_picture')->nullable();
                $table->string('contact_number')->nullable();
                $table->text('address')->nullable();
                $table->string('parent_name')->nullable();
                $table->string('parent_contact')->nullable();
                $table->string('advisor')->nullable();
                $table->string('track')->nullable();
                $table->string('strand')->nullable();
                $table->string('province')->nullable();
                $table->string('municipality')->nullable();
                $table->string('barangay')->nullable();
                $table->text('permanent_address')->nullable();
                $table->string('phone')->nullable();
                $table->string('social_media')->nullable();
                $table->string('emergency_name')->nullable();
                $table->string('emergency_phone')->nullable();
                $table->string('emergency_relationship')->nullable();
                $table->boolean('is_temporary_account')->default(false);
                $table->boolean('profile_completed')->default(false);
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop the table or remove the primary key as it would be destructive
        // This migration is meant to fix data integrity issues
    }
};
