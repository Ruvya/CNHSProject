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
        if (!Schema::hasTable('temporary_student_credentials')) {
            Schema::create('temporary_student_credentials', function (Blueprint $table) {
                $table->id();
                $table->string('student_id')->unique();
                $table->string('password');
                $table->boolean('is_used')->default(false);
                $table->unsignedBigInteger('created_by_admin_id')->nullable();
                $table->unsignedBigInteger('created_by_registrar_id')->nullable();
                $table->unsignedBigInteger('used_by_student_id')->nullable();
                $table->timestamp('used_at')->nullable();
                $table->text('notes')->nullable();
                $table->string('source')->default('manual'); // 'manual' or 'csv_upload'
                $table->timestamps();

                // Foreign key constraints
                $table->foreign('created_by_admin_id')->references('id')->on('admins')->onDelete('cascade');
                $table->foreign('created_by_registrar_id')->references('id')->on('registrars')->onDelete('cascade');
                $table->foreign('used_by_student_id')->references('id')->on('students')->onDelete('set null');

                // Indexes
                $table->index(['is_used', 'created_at']);
                $table->index('created_by_admin_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_student_credentials');
    }
};
