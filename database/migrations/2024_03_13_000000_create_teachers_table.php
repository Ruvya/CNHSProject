<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // Teaching Assignment
            $table->string('subject')->nullable(); // Primary subject
            $table->string('strand')->nullable(); // STEM, HUMSS, GAS, etc.
            $table->string('department')->nullable(); // Academic department

            // Contact Information
            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();

            // Profile
            $table->string('profile_picture')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teachers');
    }
};