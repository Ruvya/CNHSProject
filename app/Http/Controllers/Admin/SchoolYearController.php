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
		}

		return back()->with('success', 'School year created successfully.');
	}

	public function show(SchoolYear $schoolYear): View
	{
		$yearKey = $schoolYear->name;

		$studentYearlyRecords = StudentYearlyRecord::with('student')
			->where('school_year', $yearKey)
			->orderBy('grade_level')
			->get();

		$teacherYearlyRecords = TeacherYearlyRecord::with('teacher')
			->where('school_year', $yearKey)
			->get();

		$sections = Section::where('school_year', $yearKey)
			->orderBy('grade_level')
			->orderBy('name')
			->get();

		$assignments = TeacherAssignment::with(['teacher', 'subject'])
			->where('school_year', $yearKey)
			->get();

		$subjects = Subject::whereHas('students', function($q) use ($yearKey) {
			$q->where('student_subject.school_year', $yearKey);
		})
		->withCount(['students as students_count' => function($q) use ($yearKey) {
			$q->where('student_subject.school_year', $yearKey);
		}])
		->get();

		$summary = [
			'total_students' => $studentYearlyRecords->count(),
			'total_teachers' => $teacherYearlyRecords->count(),
			'total_sections' => $sections->count(),
			'total_assignments' => $assignments->count(),
			'total_subjects' => $subjects->count(),
		];

		return view('admin.school_years.show', compact(
			'schoolYear',
			'studentYearlyRecords',
			'teacherYearlyRecords',
			'sections',
			'assignments',
			'subjects',
			'summary'
		));
	}

	public function activate(SchoolYear $schoolYear): RedirectResponse
	{
		SchoolYear::where('status', SchoolYear::STATUS_ACTIVE)
			->update(['status' => SchoolYear::STATUS_CLOSED]);

		$schoolYear->update(['status' => SchoolYear::STATUS_ACTIVE]);

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
}


