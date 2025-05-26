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
        Schema::table('teachers', function (Blueprint $table) {
            // Add missing columns for teacher management
            if (!Schema::hasColumn('teachers', 'strand')) {
                $table->string('strand')->nullable()->after('subject');
            }
            if (!Schema::hasColumn('teachers', 'contact_number')) {
                $table->string('contact_number')->nullable()->after('strand');
            }
            if (!Schema::hasColumn('teachers', 'address')) {
                $table->text('address')->nullable()->after('contact_number');
            }
            if (!Schema::hasColumn('teachers', 'status')) {
                $table->string('status')->default('active')->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $columnsToCheck = ['strand', 'contact_number', 'address', 'status'];
            $columnsToRemove = [];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('teachers', $column)) {
                    $columnsToRemove[] = $column;
                }
            }

            if (!empty($columnsToRemove)) {
                $table->dropColumn($columnsToRemove);
            }
        });
    }
};
