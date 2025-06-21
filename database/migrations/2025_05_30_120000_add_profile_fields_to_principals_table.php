<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('principals', function (Blueprint $table) {
            // Add profile picture
            $table->string('profile_picture')->nullable()->after('email');
            
            // Add contact information
            $table->string('contact_number')->nullable()->after('profile_picture');
            $table->text('address')->nullable()->after('contact_number');
            
            // Add professional information
            $table->string('position')->default('Principal')->after('address');
            $table->text('bio')->nullable()->after('position');
            $table->string('education')->nullable()->after('bio');
            $table->integer('years_of_experience')->nullable()->after('education');
            
            // Add personal information
            $table->date('date_of_birth')->nullable()->after('years_of_experience');
            $table->string('gender')->nullable()->after('date_of_birth');
            
            // Add emergency contact
            $table->string('emergency_contact_name')->nullable()->after('gender');
            $table->string('emergency_contact_number')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_number');
            
            // Add social media links
            $table->string('facebook')->nullable()->after('emergency_contact_relationship');
            $table->string('linkedin')->nullable()->after('facebook');
            $table->string('twitter')->nullable()->after('linkedin');
            
            // Add status
            $table->string('status')->default('active')->after('twitter');
        });
    }

    public function down()
    {
        Schema::table('principals', function (Blueprint $table) {
            $table->dropColumn([
                'profile_picture',
                'contact_number',
                'address',
                'position',
                'bio',
                'education',
                'years_of_experience',
                'date_of_birth',
                'gender',
                'emergency_contact_name',
                'emergency_contact_number',
                'emergency_contact_relationship',
                'facebook',
                'linkedin',
                'twitter',
                'status'
            ]);
        });
    }
};
