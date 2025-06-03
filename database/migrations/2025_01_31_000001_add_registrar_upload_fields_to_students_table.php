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
        Schema::table('students', function (Blueprint $table) {
            $table->boolean('registrar_data_uploaded')->default(false)->after('profile_completed');
            $table->timestamp('registrar_upload_date')->nullable()->after('registrar_data_uploaded');
            $table->boolean('allow_profile_edit')->default(true)->after('registrar_upload_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'registrar_data_uploaded',
                'registrar_upload_date',
                'allow_profile_edit'
            ]);
        });
    }
};
