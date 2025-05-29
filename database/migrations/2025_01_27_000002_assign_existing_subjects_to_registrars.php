<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Subject;
use App\Models\Registrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run if subjects table exists
        if (Schema::hasTable('subjects') && Schema::hasColumn('subjects', 'registrar_id')) {
            // Get the first registrar to assign existing subjects to
            $firstRegistrar = Registrar::first();

            if ($firstRegistrar) {
                // Update all subjects that don't have a registrar_id assigned
                Subject::whereNull('registrar_id')->update([
                    'registrar_id' => $firstRegistrar->id
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only run if subjects table exists
        if (Schema::hasTable('subjects') && Schema::hasColumn('subjects', 'registrar_id')) {
            // Set all registrar_id to null
            Subject::whereNotNull('registrar_id')->update([
                'registrar_id' => null
            ]);
        }
    }
};
