<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of students with advanced filtering
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Advanced filtering
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        if ($request->filled('track')) {
            $query->where('track', $request->track);
        }

        if ($request->filled('strand')) {
            $query->where('strand', $request->strand);
        }

        if ($request->filled('enrollment_status')) {
            if ($request->enrollment_status === 'enrolled') {
                $query->whereHas('subjects');
            } elseif ($request->enrollment_status === 'not_enrolled') {
                $query->whereDoesntHave('subjects');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->with(['subjects', 'grades'])
                         ->orderBy('last_name')
                         ->orderBy('first_name')
                         ->paginate(20);

        // Get filter options
        $gradeLevels = Student::distinct()->pluck('grade_level')->filter();
        $sections = Student::distinct()->pluck('section')->filter();
        $tracks = Student::distinct()->pluck('track')->filter();
        $strands = Student::distinct()->pluck('strand')->filter();

        return view('registrar.students.index', compact(
            'students',
            'gradeLevels',
            'sections',
            'tracks',
            'strands'
        ));
    }

    /**
     * Show the form for creating a new student
     */
    public function create()
    {
        return view('registrar.students.create');
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|unique:students,student_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|min:8',
            'grade_level' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'parent_name' => 'nullable|string|max:255',
            'parent_contact' => 'nullable|string|max:20',
            'track' => 'nullable|string',
            'strand' => 'nullable|string',
            'section' => 'nullable|string|max:50',
            'lrn' => 'nullable|string|max:20',
        ]);

        $studentData = $request->all();
        $studentData['password'] = Hash::make($request->password);

        $student = Student::create($studentData);

        return redirect()->route('registrar.students.index')
                        ->with('success', 'Student profile created successfully.');
    }

    /**
     * Display the specified student
     */
    public function show(Student $student)
    {
        $student->load(['subjects.grades' => function($query) use ($student) {
            $query->where('student_id', $student->id);
        }, 'grades.subject']);

        // Calculate academic statistics
        $totalSubjects = $student->subjects()->count();
        $enrolledSubjects = $student->subjects;

        // Get grades from pivot table and grades table
        $allGrades = collect();

        // From pivot table
        foreach ($enrolledSubjects as $subject) {
            if ($subject->pivot && $subject->pivot->grade) {
                $allGrades->push($subject->pivot->grade);
            }
        }

        // From grades table
        $gradeRecords = $student->grades()->whereNotNull('final_grade')->get();
        foreach ($gradeRecords as $grade) {
            $allGrades->push($grade->final_grade);
        }

        $averageGrade = $allGrades->count() > 0 ? $allGrades->avg() : null;
        $passedSubjects = $allGrades->filter(function($grade) {
            return $grade >= 75;
        })->count();
        $failedSubjects = $allGrades->filter(function($grade) {
            return $grade < 75;
        })->count();

        return view('registrar.students.show', compact(
            'student',
            'totalSubjects',
            'averageGrade',
            'passedSubjects',
            'failedSubjects'
        ));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(Student $student)
    {
        return view('registrar.students.edit', compact('student'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'student_id' => 'required|unique:students,student_id,' . $student->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'grade_level' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'parent_name' => 'nullable|string|max:255',
            'parent_contact' => 'nullable|string|max:20',
            'track' => 'nullable|string',
            'strand' => 'nullable|string',
            'section' => 'nullable|string|max:50',
            'lrn' => 'nullable|string|max:20',
        ]);

        $studentData = $request->except(['password']);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $studentData['password'] = Hash::make($request->password);
        }

        $student->update($studentData);

        return redirect()->route('registrar.students.show', $student)
                        ->with('success', 'Student record updated successfully.');
    }

    /**
     * Remove the specified student
     */
    public function destroy(Student $student)
    {
        try {
            // Detach all subjects first
            $student->subjects()->detach();

            // Delete all grades
            $student->grades()->delete();

            // Delete the student
            $student->delete();

            return redirect()->route('registrar.students.index')
                            ->with('success', 'Student record deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('registrar.students.index')
                            ->with('error', 'Error deleting student record. Please try again.');
        }
    }

    /**
     * Manage student enrollment
     */
    public function enrollment(Student $student)
    {
        $availableSubjects = Subject::all();
        $enrolledSubjects = $student->subjects;

        return view('registrar.students.enrollment', compact(
            'student',
            'availableSubjects',
            'enrolledSubjects'
        ));
    }

    /**
     * Update student enrollment
     */
    public function updateEnrollment(Request $request, Student $student)
    {
        $request->validate([
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        // Sync subjects with the student
        $student->subjects()->sync($request->subjects ?? []);

        return redirect()->route('registrar.students.show', $student)
                        ->with('success', 'Student enrollment updated successfully.');
    }

    /**
     * Activate/Deactivate student enrollment
     */
    public function toggleEnrollmentStatus(Student $student)
    {
        // Toggle enrollment status (you can add an 'active' field to students table)
        // For now, we'll use the presence of subjects as enrollment status

        if ($student->subjects()->count() > 0) {
            // Deactivate - remove all subjects
            $student->subjects()->detach();
            $message = 'Student enrollment deactivated successfully.';
        } else {
            $message = 'Student has no subjects enrolled. Please enroll in subjects first.';
        }

        return redirect()->route('registrar.students.show', $student)
                        ->with('success', $message);
    }

    /**
     * Bulk operations for students
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,transfer_section',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'new_section' => 'required_if:action,transfer_section|string|max:50'
        ]);

        $students = Student::whereIn('id', $request->student_ids);

        switch ($request->action) {
            case 'delete':
                foreach ($students->get() as $student) {
                    $student->subjects()->detach();
                    $student->grades()->delete();
                    $student->delete();
                }
                $message = 'Selected students deleted successfully.';
                break;

            case 'transfer_section':
                $students->update(['section' => $request->new_section]);
                $message = 'Selected students transferred to section ' . $request->new_section . ' successfully.';
                break;

            default:
                $message = 'Action completed successfully.';
        }

        return redirect()->route('registrar.students.index')
                        ->with('success', $message);
    }
}
