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
        Schema::table('subjects', function (Blueprint $table) {
            // Schedule fields
            $table->string('schedule_days')->nullable()->comment('Days of the week (e.g., "Monday,Wednesday,Friday")');
            $table->time('start_time')->nullable()->comment('Class start time');
            $table->time('end_time')->nullable()->comment('Class end time');
            $table->string('room')->nullable()->comment('Classroom or venue');
            $table->text('schedule_notes')->nullable()->comment('Additional schedule information');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['schedule_days', 'start_time', 'end_time', 'room', 'schedule_notes']);
        });
    }
};
