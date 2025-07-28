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
        Schema::table('temporary_student_credentials', function (Blueprint $table) {
            // Make admin_id nullable and add registrar_id
            $table->unsignedBigInteger('created_by_admin_id')->nullable()->change();
            $table->unsignedBigInteger('created_by_registrar_id')->nullable()->after('created_by_admin_id');
            $table->string('source')->default('manual')->after('notes');

            // Add foreign key for registrar
            $table->foreign('created_by_registrar_id')->references('id')->on('registrars')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temporary_student_credentials', function (Blueprint $table) {
            $table->dropForeign(['created_by_registrar_id']);
            $table->dropColumn(['created_by_registrar_id', 'source']);
            $table->unsignedBigInteger('created_by_admin_id')->nullable(false)->change();
        });
    }
};
