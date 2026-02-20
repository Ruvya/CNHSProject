<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolYear;
use App\Services\PromotionService;
use App\Services\YearlyRecordService;

class PromoteEligibleStudents extends Command
{
    protected $signature = 'students:promote {year? : Source school year (defaults to active)} {--passing=75 : Passing grade threshold} {--activate-next : Activate the next school year after promotion}';

    protected $description = 'Promote students based on their general average for a school year, retain failing, and mark graduates.';

    public function handle(): int
    {
        $year = $this->argument('year');
        if (!$year) {
            $active = SchoolYear::active()->first();
            if (!$active) {
                $this->error('No active school year found. Specify a year explicitly.');
                return self::FAILURE;
            }
            $year = $active->name;
        }

        $passing = (float)$this->option('passing');

        /** @var PromotionService $service */
        $service = app(PromotionService::class);
        $result = $service->promoteForSchoolYear($year, null, $passing);

        $this->info("Promotion complete: {$result['promoted']} promoted, {$result['retained']} retained, {$result['graduated']} graduated.");

        if ($this->option('activate-next')) {
            $next = $service->nextSchoolYear($year);
            SchoolYear::where('status', SchoolYear::STATUS_ACTIVE)->update(['status' => SchoolYear::STATUS_CLOSED]);
            $nextYear = SchoolYear::firstOrCreate([
                'name' => $next,
            ], [
                'start_year' => (int)substr($next, 0, 4),
                'end_year' => (int)substr($next, 5, 4),
                'status' => SchoolYear::STATUS_ACTIVE,
            ]);
            if ($nextYear->status !== SchoolYear::STATUS_ACTIVE) {
                $nextYear->update(['status' => SchoolYear::STATUS_ACTIVE]);
            }
            app(YearlyRecordService::class)->ensureForSchoolYear($nextYear->name);
            $this->info("Activated next school year: {$nextYear->name}");
        }

        return self::SUCCESS;
    }
}


