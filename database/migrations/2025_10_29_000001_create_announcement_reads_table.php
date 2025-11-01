<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('announcement_reads', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('announcement_id');
			$table->unsignedBigInteger('student_id');
			$table->timestamp('read_at')->nullable();
			$table->timestamps();

			$table->unique(['announcement_id', 'student_id']);
			$table->foreign('announcement_id')->references('id')->on('announcements')->onDelete('cascade');
			$table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('announcement_reads');
	}
};


