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
        // Only add the column if the subjects table exists
        if (Schema::hasTable('subjects')) {
            Schema::table('subjects', function (Blueprint $table) {
                if (!Schema::hasColumn('subjects', 'registrar_id')) {
                    $table->unsignedBigInteger('registrar_id')->nullable()->after('teacher_id');
                    $table->foreign('registrar_id')->references('id')->on('registrars')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('subjects') && Schema::hasColumn('subjects', 'registrar_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->dropForeign(['registrar_id']);
                $table->dropColumn('registrar_id');
            });
        }
    }
};
