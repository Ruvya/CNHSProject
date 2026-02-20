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
            // Add track column
            $table->string('track')->nullable()->after('subject');

            // Rename strand to cluster
            $table->renameColumn('strand', 'cluster');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Remove track column
            $table->dropColumn('track');

            // Rename cluster back to strand
            $table->renameColumn('cluster', 'strand');
        });
    }
};
