<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\StudentYearlyRecord;
use App\Models\TeacherYearlyRecord;
use App\Models\Section;
use App\Models\TeacherAssignment;
use App\Models\Subject;
use App\Models\Schedule;
use App\Services\YearlyRecordService;
use App\Services\PromotionService;

class SchoolYearController extends Controller
{
	public function index(): View
	{
		$years = SchoolYear::orderByDesc('start_year')->get();
		$active = SchoolYear::active()->first();
		return view('admin.school_years.index', compact('years', 'active'));
	}

	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'name' => 'required|string|unique:school_years,name',
			'start_year' => 'required|integer|min:2000|max:3000',
			'end_year' => 'required|integer|min:2000|max:3000|gt:start_year',
			'activate' => 'nullable|boolean',
		]);

		$schoolYear = SchoolYear::create([
			'name' => $validated['name'],
			'start_year' => $validated['start_year'],
			'end_year' => $validated['end_year'],
			'status' => $request->boolean('activate') ? SchoolYear::STATUS_ACTIVE : SchoolYear::STATUS_CLOSED,
		]);

		if ($schoolYear->status === SchoolYear::STATUS_ACTIVE) {
			SchoolYear::where('id', '!=', $schoolYear->id)
				->where('status', SchoolYear::STATUS_ACTIVE)
				->update(['status' => SchoolYear::STATUS_CLOSED]);

			// Ensure yearly records are created for the newly active school year
			app(YearlyRecordService::class)->ensureForSchoolYear($schoolYear->name);
		}

		return back()->with('success', 'School year created successfully.');
	}

	public function show(SchoolYear $schoolYear): View
	{
		$yearKey = $schoolYear->name;

		// Load all related data using the model relationships
		$studentYearlyRecords = $schoolYear->studentYearlyRecords()
			->with('student')
			->orderBy('grade_level')
			->get();

		$teacherYearlyRecords = $schoolYear->teacherYearlyRecords()
			->with('teacher')
			->get();

		$sections = $schoolYear->sections()
			->with('adviser')
			->orderBy('grade_level')
			->orderBy('name')
			->get();

		$assignments = $schoolYear->teacherAssignments()
			->with(['teacher', 'subject'])
			->get();

		$schedules = $schoolYear->schedules()
			->with(['teacher', 'subject', 'section'])
			->get();

		$subjects = Subject::whereHas('students', function($q) use ($yearKey) {
			$q->where('student_subject.school_year', $yearKey);
		})
		->withCount(['students as students_count' => function($q) use ($yearKey) {
			$q->where('student_subject.school_year', $yearKey);
		}])
		->get();

		// Get summary using the model's summary attribute
		$summary = $schoolYear->summary;

		// Add additional summary data
		$summary['total_schedules'] = $schedules->count();
		$summary['total_subjects'] = $subjects->count();

		// Get grade level distribution
		$gradeDistribution = $studentYearlyRecords->groupBy('grade_level')
			->map(function($students) {
				return $students->count();
			});

		// Get section capacity analysis
		$sectionAnalysis = $sections->map(function($section) {
			return [
				'name' => $section->name,
				'grade_level' => $section->grade_level,
				'current_enrollment' => $section->current_enrollment,
				'max_capacity' => $section->max_capacity,
				'utilization' => $section->max_capacity > 0 ? round(($section->current_enrollment / $section->max_capacity) * 100, 2) : 0,
				'status' => $section->status
			];
		});

		return view('admin.school_years.show', compact(
			'schoolYear',
			'studentYearlyRecords',
			'teacherYearlyRecords',
			'sections',
			'assignments',
			'schedules',
			'subjects',
			'summary',
			'gradeDistribution',
			'sectionAnalysis'
		));
	}

	public function activate(SchoolYear $schoolYear): RedirectResponse
	{
		SchoolYear::where('status', SchoolYear::STATUS_ACTIVE)
			->update(['status' => SchoolYear::STATUS_CLOSED]);

		$schoolYear->update(['status' => SchoolYear::STATUS_ACTIVE]);

		// Ensure yearly records exist for this active school year
		app(YearlyRecordService::class)->ensureForSchoolYear($schoolYear->name);

		return back()->with('success', 'School year activated.');
	}

	/**
	 * Promote eligible students for the given (current) school year.
	 * Optionally activate the next school year after promotion.
	 */
    public function promote(Request $request, SchoolYear $schoolYear): RedirectResponse
	{
		$validated = $request->validate([
			'passing_grade' => 'nullable|numeric|min:60|max:100',
            'dropped_ids' => 'nullable|string',
            'transferred_ids' => 'nullable|string',
		]);

		$passing = (float)($validated['passing_grade'] ?? 75);

        // Derive next year values from the current record to preserve naming format
        $nextStart = (int)$schoolYear->end_year;
        $nextEnd = $nextStart + 1;
        $hasPrefix = strpos($schoolYear->name, 'S.Y.') === 0;
        $usesEndash = strpos($schoolYear->name, '–') !== false; // en dash
        $separator = $usesEndash ? ' – ' : ' - ';
        $nextName = ($hasPrefix ? 'S.Y. ' : '') . $nextStart . $separator . $nextEnd;

        $service = app(PromotionService::class);
        // Pass the normalized next school year name to ensure StudentYearlyRecord uses the same key
        $result = $service->promoteForSchoolYear($schoolYear->name, $nextName, $passing);

        // Close any active and activate next
        SchoolYear::where('status', SchoolYear::STATUS_ACTIVE)
            ->update(['status' => SchoolYear::STATUS_CLOSED]);

        // Prefer existing record by exact year range, then normalize its name/status
        $nextYear = SchoolYear::where('start_year', $nextStart)
            ->where('end_year', $nextEnd)
            ->first();
        if ($nextYear) {
            $nextYear->update([
                'name' => $nextName,
                'status' => SchoolYear::STATUS_ACTIVE,
            ]);
        } else {
            $nextYear = SchoolYear::create([
                'name' => $nextName,
                'start_year' => $nextStart,
                'end_year' => $nextEnd,
                'status' => SchoolYear::STATUS_ACTIVE,
            ]);
        }

        // Cleanup duplicates of the same range (different names)
        SchoolYear::where('start_year', $nextStart)
            ->where('end_year', $nextEnd)
            ->where('id', '!=', $nextYear->id)
            ->delete();
        // Ensure yearly records exist for newly active school year
        app(YearlyRecordService::class)->ensureForSchoolYear($nextYear->name);

        // Apply overrides for dropped/transferred students
        $parseIds = function (?string $csv) {
            if (!$csv) return collect();
            return collect(explode(',', $csv))
                ->map(function ($x) { return (int)trim($x); })
                ->filter(function ($x) { return $x > 0; })
                ->unique();
        };

        $dropped = $parseIds($validated['dropped_ids'] ?? null);
        $transferred = $parseIds($validated['transferred_ids'] ?? null);

        if ($dropped->isNotEmpty() || $transferred->isNotEmpty()) {
            \DB::transaction(function () use ($dropped, $transferred, $schoolYear, $nextYear) {
                foreach ($dropped as $studentId) {
                    // Set next year record to dropped and revert grade level to previous year's level
                    $prev = \App\Models\StudentYearlyRecord::where('student_id', $studentId)
                        ->where('school_year', $schoolYear->name)
                        ->first();
                    $prevLevel = $prev ? $prev->grade_level : null;
                    \App\Models\StudentYearlyRecord::updateOrCreate([
                        'student_id' => $studentId,
                        'school_year' => $nextYear->name,
                    ], [
                        'grade_level' => $prevLevel,
                        'status' => 'dropped',
                    ]);
                    if ($prevLevel) {
                        \App\Models\Student::where('id', $studentId)->update(['grade_level' => $prevLevel]);
                    }
                }

                foreach ($transferred as $studentId) {
                    $prev = \App\Models\StudentYearlyRecord::where('student_id', $studentId)
                        ->where('school_year', $schoolYear->name)
                        ->first();
                    $prevLevel = $prev ? $prev->grade_level : null;
                    \App\Models\StudentYearlyRecord::updateOrCreate([
                        'student_id' => $studentId,
                        'school_year' => $nextYear->name,
                    ], [
                        'grade_level' => $prevLevel,
                        'status' => 'transferred',
                    ]);
                    if ($prevLevel) {
                        \App\Models\Student::where('id', $studentId)->update(['grade_level' => $prevLevel]);
                    }
                }
            });
        }

        $summary = "Promotion complete: {$result['promoted']} students promoted, {$result['retained']} retained, and {$result['graduated']} graduated. Activated next school year: {$nextYear->name}.";
        return back()->with('success', $summary);
	}

	public function close(SchoolYear $schoolYear): RedirectResponse
	{
		$schoolYear->update(['status' => SchoolYear::STATUS_CLOSED]);
		return back()->with('success', 'School year closed and archived.');
	}

	public function archive(SchoolYear $schoolYear): RedirectResponse
	{
		$schoolYear->update(['status' => SchoolYear::STATUS_ARCHIVED]);
		return back()->with('success', 'School year archived.');
	}

	public function reopen(SchoolYear $schoolYear): RedirectResponse
	{
		$schoolYear->update(['status' => SchoolYear::STATUS_CLOSED]);
		return back()->with('success', 'School year reopened for viewing. Activate to make it current.');
	}

	/**
	 * Update school year details
	 */
	public function update(Request $request, SchoolYear $schoolYear): RedirectResponse
	{
		$validated = $request->validate([
			'name' => 'required|string|unique:school_years,name,' . $schoolYear->id,
			'start_year' => 'required|integer|min:2000|max:3000',
			'end_year' => 'required|integer|min:2000|max:3000|gt:start_year',
		]);

		$schoolYear->update($validated);

		return back()->with('success', 'School year updated successfully.');
	}

	/**
	 * Delete school year (only if no records exist)
	 */
    public function destroy(Request $request, SchoolYear $schoolYear): RedirectResponse
	{
		// Check if school year has any records
		$hasRecords = $schoolYear->studentYearlyRecords()->exists() ||
			$schoolYear->teacherYearlyRecords()->exists() ||
			$schoolYear->sections()->exists() ||
			$schoolYear->teacherAssignments()->exists() ||
			$schoolYear->schedules()->exists();

        if ($hasRecords && !$request->boolean('force')) {
            return back()->with('error', 'Cannot delete school year with existing records without confirmation. Please confirm to delete all associated records.');
        }

        if ($hasRecords && $request->boolean('force')) {
            \DB::transaction(function () use ($schoolYear) {
                // Delete related records scoped by school year name
                \App\Models\StudentYearlyRecord::where('school_year', $schoolYear->name)->delete();
                \App\Models\TeacherYearlyRecord::where('school_year', $schoolYear->name)->delete();
                \App\Models\Section::where('school_year', $schoolYear->name)->delete();
                \App\Models\TeacherAssignment::where('school_year', $schoolYear->name)->delete();
                \App\Models\Schedule::where('school_year', $schoolYear->name)->delete();
            });
        }

		$schoolYear->delete();

		return redirect()->route('admin.school-years.index')
			->with('success', 'School year deleted successfully.');
	}

	/**
	 * Get school year statistics
	 */
	public function statistics(SchoolYear $schoolYear): View
	{
		$yearKey = $schoolYear->name;

		// Get detailed statistics
		$studentStats = $schoolYear->studentYearlyRecords()
			->selectRaw('grade_level, COUNT(*) as count')
			->groupBy('grade_level')
			->orderBy('grade_level')
			->get();

		$teacherStats = $schoolYear->teacherYearlyRecords()
			->selectRaw('department, COUNT(*) as count')
			->groupBy('department')
			->orderBy('department')
			->get();

		$sectionStats = $schoolYear->sections()
			->selectRaw('grade_level, track, COUNT(*) as count')
			->groupBy('grade_level', 'track')
			->orderBy('grade_level')
			->get();

		$assignmentStats = $schoolYear->teacherAssignments()
			->selectRaw('grading_period, COUNT(*) as count')
			->groupBy('grading_period')
			->orderBy('grading_period')
			->get();

		return view('admin.school_years.statistics', compact(
			'schoolYear',
			'studentStats',
			'teacherStats',
			'sectionStats',
			'assignmentStats'
		));
	}

	/**
	 * Export school year data
	 */
	public function export(SchoolYear $schoolYear)
	{
		// This would implement data export functionality
		// For now, return a simple response
		return back()->with('info', 'Export functionality will be implemented.');
	}
}


