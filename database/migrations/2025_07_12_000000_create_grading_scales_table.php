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
        Schema::create('grading_scales', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('min_grade'); // e.g., 75
            $table->unsignedTinyInteger('max_grade'); // e.g., 79
            $table->string('description'); // e.g., 'Fairly Satisfactory'
            $table->string('remarks'); // e.g., 'Passed', 'Failed'
            $table->unsignedTinyInteger('order')->default(0); // for display ordering
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_scales');
    }
}; 