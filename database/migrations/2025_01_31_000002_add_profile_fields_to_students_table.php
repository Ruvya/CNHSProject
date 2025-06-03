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
            // Add new profile fields required by registrar upload
            if (!Schema::hasColumn('students', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('students', 'place_of_birth')) {
                $table->string('place_of_birth')->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('students', 'nationality')) {
                $table->string('nationality')->nullable()->after('place_of_birth');
            }
            if (!Schema::hasColumn('students', 'religion')) {
                $table->string('religion')->nullable()->after('nationality');
            }
            if (!Schema::hasColumn('students', 'civil_status')) {
                $table->string('civil_status')->nullable()->after('religion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $columns = ['date_of_birth', 'place_of_birth', 'nationality', 'religion', 'civil_status'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
