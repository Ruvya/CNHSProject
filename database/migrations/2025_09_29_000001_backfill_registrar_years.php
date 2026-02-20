<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        // Backfill RegistrarYear records for existing SchoolYear names
        $schoolYears = \App\Models\SchoolYear::query()->pluck('name');
        foreach ($schoolYears as $name) {
            if (!\App\Models\RegistrarYear::where('school_year', $name)->exists()) {
                \App\Models\RegistrarYear::create([
                    'school_year' => $name,
                    'metadata' => null,
                ]);
            }
        }
    }

    public function down(): void
    {
        // No-op: keep backfilled data
    }
};


