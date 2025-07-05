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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('name')->nullable(); // Full name computed field
            $table->string('email')->unique();
            $table->string('password');
            
            // Academic Information
            $table->string('grade_level');
            $table->string('year_level')->nullable();
            $table->string('section')->nullable();
            $table->string('track')->nullable();
            $table->string('strand')->nullable();
            $table->string('lrn')->nullable(); // Learner Reference Number
            
            // Personal Information
            $table->string('gender');
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('civil_status')->nullable();
            
            // Contact Information
            $table->string('contact_number')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('province')->nullable();
            $table->string('municipality')->nullable();
            $table->string('barangay')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('social_media')->nullable();
            
            // Parent/Guardian Information
            $table->string('parent_name')->nullable();
            $table->string('parent_contact')->nullable();
            
            // Emergency Contact Information
            $table->string('emergency_name')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->string('emergency_relationship')->nullable();
            
            // Academic Management
            $table->string('advisor')->nullable();
            
            // Profile Management
            $table->string('profile_picture')->nullable();
            $table->boolean('is_temporary_account')->default(false);
            $table->boolean('profile_completed')->default(false);
            $table->boolean('allow_profile_edit')->default(true);
            
            // Registrar Upload Tracking
            $table->boolean('registrar_data_uploaded')->default(false);
            $table->timestamp('registrar_upload_date')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
