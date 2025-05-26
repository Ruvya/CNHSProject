<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $teachers = Teacher::orderBy('name')->get();

        // Get all available grade levels for the filter dropdown
        $availableGradeLevels = Student::select('grade_level')
            ->distinct()
            ->whereNotNull('grade_level')
            ->orderBy('grade_level')
            ->pluck('grade_level');

        // Build the students query
        $studentsQuery = Student::query();

        // Apply grade level filter if provided
        $selectedGradeLevel = $request->get('grade_level');
        if ($selectedGradeLevel && $selectedGradeLevel !== 'all') {
            $studentsQuery->where('grade_level', $selectedGradeLevel);
        }

        $students = $studentsQuery->orderBy('first_name')->get();

        // Count students by grade level for statistics
        $studentsByGrade = Student::select('grade_level', \DB::raw('count(*) as count'))
            ->groupBy('grade_level')
            ->orderBy('grade_level')
            ->get();

        return view('admin.users.index', compact(
            'teachers',
            'students',
            'availableGradeLevels',
            'selectedGradeLevel',
            'studentsByGrade'
        ));
    }

    // Teacher Management
    public function createTeacher()
    {
        return view('admin.users.create-teacher');
    }

    public function storeTeacher(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers',
            'password' => 'required|string|min:8|confirmed',
            'strand' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Teacher::create($validated);

        return redirect()->route('admin.users')->with('success', 'Teacher created successfully.');
    }

    public function editTeacher(Teacher $teacher)
    {
        return view('admin.users.edit-teacher', compact('teacher'));
    }

    public function updateTeacher(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('teachers')->ignore($teacher->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'strand' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $teacher->update($validated);

        return redirect()->route('admin.users')->with('success', 'Teacher updated successfully.');
    }

    public function destroyTeacher(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->route('admin.users')->with('success', 'Teacher deleted successfully.');
    }

    // Student Management
    public function createStudent()
    {
        return view('admin.users.create-student');
    }

    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:255|unique:students',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'password' => 'required|string|min:8|confirmed',
            'grade_level' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'track' => 'nullable|string|max:255',
            'strand' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'parent_name' => 'nullable|string|max:255',
            'parent_contact' => 'nullable|string|max:255',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Student::create($validated);

        return redirect()->route('admin.users')->with('success', 'Student created successfully.');
    }

    public function editStudent(Student $student)
    {
        return view('admin.users.edit-student', compact('student'));
    }

    public function updateStudent(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'string', 'max:255', Rule::unique('students')->ignore($student->id)],
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('students')->ignore($student->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'grade_level' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'track' => 'nullable|string|max:255',
            'strand' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'parent_name' => 'nullable|string|max:255',
            'parent_contact' => 'nullable|string|max:255',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $student->update($validated);

        return redirect()->route('admin.users')->with('success', 'Student updated successfully.');
    }

    public function showStudent(Student $student)
    {
        try {
            // Load relationships for comprehensive profile data
            $student->load(['subjects', 'grades.subject']);

            // Get academic performance statistics
            $totalSubjects = $student->subjects()->count();

            // Handle grades - check if grades exist and have the right structure
            $gradesWithScores = collect();
            $averageGrade = null;
            $passedSubjects = 0;
            $failedSubjects = 0;
            $recentGrades = collect();

            // Try to get grades from the pivot table first
            $subjectsWithGrades = $student->subjects()->wherePivot('grade', '!=', null)->get();

            if ($subjectsWithGrades->count() > 0) {
                foreach ($subjectsWithGrades as $subject) {
                    $grade = $subject->pivot->grade;
                    if ($grade) {
                        $gradesWithScores->push((object)[
                            'grade' => $grade,
                            'subject' => $subject,
                            'created_at' => $subject->pivot->created_at
                        ]);

                        if ($grade >= 75) {
                            $passedSubjects++;
                        } else {
                            $failedSubjects++;
                        }
                    }
                }

                $averageGrade = $gradesWithScores->avg('grade');
                $recentGrades = $gradesWithScores->sortByDesc('created_at')->take(5);
            }

            // Get subjects grouped by grade level
            $subjectsByGrade = $student->subjects()->get()->groupBy('grade_level');

            // Calculate GPA if grades exist
            $gpa = null;
            if ($gradesWithScores->count() > 0) {
                $gpa = $this->calculateGPA($gradesWithScores);
            }

            return view('admin.users.show-student', compact(
                'student',
                'totalSubjects',
                'averageGrade',
                'passedSubjects',
                'failedSubjects',
                'recentGrades',
                'subjectsByGrade',
                'gpa'
            ));

        } catch (\Exception $e) {
            // If there's an error, show basic profile without grades
            return view('admin.users.show-student', [
                'student' => $student,
                'totalSubjects' => 0,
                'averageGrade' => null,
                'passedSubjects' => 0,
                'failedSubjects' => 0,
                'recentGrades' => collect(),
                'subjectsByGrade' => collect(),
                'gpa' => null
            ]);
        }
    }

    private function calculateGPA($grades)
    {
        $totalPoints = 0;
        $totalUnits = 0;

        foreach ($grades as $grade) {
            $gradePoint = $this->convertToGradePoint($grade->grade);
            $units = $grade->subject->units ?? 1;

            $totalPoints += $gradePoint * $units;
            $totalUnits += $units;
        }

        return $totalUnits > 0 ? round($totalPoints / $totalUnits, 2) : 0;
    }

    private function convertToGradePoint($numericGrade)
    {
        if ($numericGrade >= 97) return 4.0;
        if ($numericGrade >= 94) return 3.7;
        if ($numericGrade >= 91) return 3.3;
        if ($numericGrade >= 88) return 3.0;
        if ($numericGrade >= 85) return 2.7;
        if ($numericGrade >= 82) return 2.3;
        if ($numericGrade >= 79) return 2.0;
        if ($numericGrade >= 76) return 1.7;
        if ($numericGrade >= 75) return 1.0;
        return 0.0;
    }

    public function destroyStudent(Student $student)
    {
        $student->delete();
        return redirect()->route('admin.users')->with('success', 'Student deleted successfully.');
    }
}