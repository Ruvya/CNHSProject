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
        Schema::table('subjects', function (Blueprint $table) {
            // Add DepEd curriculum fields if they don't exist
            if (!Schema::hasColumn('subjects', 'cluster')) {
                $table->string('cluster')->nullable()->after('strand');
            }
            if (!Schema::hasColumn('subjects', 'specialization')) {
                $table->string('specialization')->nullable()->after('cluster');
            }
            if (!Schema::hasColumn('subjects', 'is_core_subject')) {
                $table->boolean('is_core_subject')->default(false)->after('is_master_subject');
            }
            if (!Schema::hasColumn('subjects', 'prerequisite_subjects')) {
                $table->text('prerequisite_subjects')->nullable()->after('is_core_subject');
            }
            if (!Schema::hasColumn('subjects', 'units')) {
                $table->integer('units')->default(3)->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn([
                'cluster',
                'specialization',
                'is_core_subject',
                'prerequisite_subjects'
            ]);
        });
    }
};
