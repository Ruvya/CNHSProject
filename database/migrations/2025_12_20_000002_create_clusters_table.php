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
        Schema::create('clusters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('track_id')->constrained('tracks')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('order')->nullable();
            $table->timestamps();

            // Ensure cluster name is unique within the same track
            $table->unique(['track_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clusters');
    }
};

