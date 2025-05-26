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
        Schema::table('students', function (Blueprint $table) {
            // $table->string('section')->nullable();
            // $table->string('address')->nullable();
            // $table->string('emergency_contact_name')->nullable();
            // $table->string('emergency_contact_relationship')->nullable();
            // $table->string('emergency_contact_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'section',
                'address',
                'emergency_contact_name',
                'emergency_contact_relationship',
                'emergency_contact_number'
            ]);
        });
    }
}; 