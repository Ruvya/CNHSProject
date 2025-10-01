<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registrar_years', function (Blueprint $table) {
            $table->id();
            $table->string('school_year')->unique();
            $table->foreignId('created_by')->nullable()->constrained('registrars')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('school_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrar_years');
    }
};


