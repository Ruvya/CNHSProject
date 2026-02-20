<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolYear;
use App\Services\YearlyRecordService;

class BackfillYearlyRecords extends Command
{
    protected $signature = 'yearly-records:backfill {year? : School year name, e.g., 2025-2026} {--all : Backfill for all school years}';

    protected $description = 'Create missing StudentYearlyRecord and TeacherYearlyRecord entries for the specified or all school years';

    public function handle(YearlyRecordService $service): int
    {
        $year = $this->argument('year');
        $all = $this->option('all');

        if (!$all && !$year) {
            $this->error('Specify a {year} or use --all.');
            return self::FAILURE;
        }

        $years = collect();
        if ($all) {
            $years = SchoolYear::orderBy('start_year')->pluck('name');
        } else {
            $exists = SchoolYear::where('name', $year)->exists();
            if (!$exists) {
                $this->error("School year '{$year}' not found.");
                return self::FAILURE;
            }
            $years = collect([$year]);
        }

        foreach ($years as $sy) {
            $this->info("Backfilling yearly records for {$sy}...");
            $result = $service->ensureForSchoolYear($sy);
            $this->line("  Students created: {$result['students_created']}");
            $this->line("  Teachers created: {$result['teachers_created']}");
        }

        $this->info('Done.');
        return self::SUCCESS;
    }
}


