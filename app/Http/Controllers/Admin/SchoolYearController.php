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
	public function destroy(SchoolYear $schoolYear): RedirectResponse
	{
		// Check if school year has any records
		$hasRecords = $schoolYear->studentYearlyRecords()->exists() ||
			$schoolYear->teacherYearlyRecords()->exists() ||
			$schoolYear->sections()->exists() ||
			$schoolYear->teacherAssignments()->exists() ||
			$schoolYear->schedules()->exists();

		if ($hasRecords) {
			return back()->with('error', 'Cannot delete school year with existing records. Archive it instead.');
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


