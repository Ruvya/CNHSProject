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
		Schema::create('school_years', function (Blueprint $table) {
			$table->id();
			$table->string('name')->unique();
			$table->unsignedSmallInteger('start_year');
			$table->unsignedSmallInteger('end_year');
			$table->enum('status', ['active', 'closed', 'archived'])->default('closed');
			$table->timestamps();
		});

		// Ensure only one active year at a time via partial index (where supported)
		// Fallback enforcement will be handled at application level
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('school_years');
	}
};


