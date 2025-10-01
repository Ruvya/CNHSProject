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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['general', 'laboratory', 'computer_lab', 'science_lab', 'library', 'gymnasium', 'auditorium', 'special'])->default('general');
            $table->integer('capacity')->default(30);
            $table->string('location')->nullable();
            $table->json('equipment')->nullable()->comment('Available equipment in the room');
            $table->boolean('is_available')->default(true);
            $table->json('special_requirements')->nullable()->comment('Special requirements for certain subjects');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
