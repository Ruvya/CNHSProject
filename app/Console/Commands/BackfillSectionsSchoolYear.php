<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Section;
use App\Models\SchoolYear;

class BackfillSectionsSchoolYear extends Command
{
	protected $signature = 'app:backfill-sections-year {--dry-run : Only show what would change}';

	protected $description = 'Set sections.school_year to an existing SchoolYear (active or latest) when missing or invalid';

	public function handle(): int
	{
		$dryRun = (bool) $this->option('dry-run');

		$active = SchoolYear::active()->first();
		$latest = SchoolYear::orderByDesc('start_year')->first();
		$targetYear = optional($active)->name ?? optional($latest)->name;

		if (!$targetYear) {
			$this->error('No SchoolYear records found. Aborting.');
			return self::FAILURE;
		}

		$validYears = SchoolYear::pluck('name')->all();

		$toFix = Section::query()
			->whereNull('school_year')
			->orWhereNotIn('school_year', $validYears)
			->get();

		if ($toFix->isEmpty()) {
			$this->info('All sections already reference a valid school year.');
			return self::SUCCESS;
		}

		$this->info('Sections to update: ' . $toFix->count());

		foreach ($toFix as $section) {
			$this->line("- {$section->name} (current: " . ($section->school_year ?? 'NULL') . ") -> {$targetYear}");
			if (!$dryRun) {
				$section->school_year = $targetYear;
				$section->save();
			}
		}

		if ($dryRun) {
			$this->info('Dry run complete. No changes saved. Re-run without --dry-run to apply.');
		} else {
			$this->info('Backfill complete.');
		}

		return self::SUCCESS;
	}
} 