<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentYearlyRecord;
use App\Models\TeacherYearlyRecord;
use App\Models\Section;
use App\Models\Subject;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class YearlyRecordsController extends Controller
{
    /**
     * Display yearly records overview
     */
    public function index()
    {
        // Source of truth for school years is Admin-managed SchoolYear model
        $allYears = SchoolYear::notArchived()
            ->orderByDesc('start_year')
            ->pluck('name');

        // Current school year is the active SchoolYear if available; fallback to computed
        $currentSchoolYear = optional(SchoolYear::active()->first())->name ?? $this->getCurrentSchoolYear();
        
        // Get statistics for current year
        $currentYearStats = [
            'total_students' => StudentYearlyRecord::where('school_year', $currentSchoolYear)->count(),
            'total_teachers' => TeacherYearlyRecord::where('school_year', $currentSchoolYear)->count(),
            'active_students' => StudentYearlyRecord::where('school_year', $currentSchoolYear)
                ->where('status', 'enrolled')->count(),
            'active_teachers' => TeacherYearlyRecord::where('school_year', $currentSchoolYear)
                ->where('status', 'active')->count(),
        ];
        
        // Get recent records
        $recentStudentRecords = StudentYearlyRecord::with('student')
            ->where('school_year', $currentSchoolYear)
            ->latest()
            ->take(5)
            ->get();
            
        $recentTeacherRecords = TeacherYearlyRecord::with('teacher')
            ->where('school_year', $currentSchoolYear)
            ->latest()
            ->take(5)
            ->get();
        
        return view('registrar.yearly-records.index', compact(
            'allYears',
            'currentSchoolYear',
            'currentYearStats',
            'recentStudentRecords',
            'recentTeacherRecords'
        ));
    }

    /**
     * Show records for a specific school year
     */
    public function show(Request $request, $schoolYear)
    {
        // Ensure the school year exists and is not archived (allow any admin-defined name)
        $year = SchoolYear::notArchived()->where('name', $schoolYear)->first();
        if (!$year) {
            return redirect()->route('registrar.yearly-records.index')
                ->with('error', 'Selected school year does not exist or is archived.');
        }
        
        // Read filters
        $selectedGrade = $request->get('grade_level');
        $selectedSection = $request->get('section');

        // Get sections for the year (respect filters if provided)
        $sectionsQuery = Section::where('school_year', $schoolYear)
            ->with(['adviser'])
            ->orderBy('grade_level')
            ->orderBy('name');

        if ($selectedGrade) {
            $sectionsQuery->where('grade_level', $selectedGrade);
        }
        if ($selectedSection) {
            $sectionsQuery->where('name', $selectedSection);
        }

        $sections = $sectionsQuery->get();

        // Get student records for the year grouped by section
        $studentRecordsQuery = StudentYearlyRecord::with(['student' => function($q) use ($schoolYear) {
                $q->with(['subjects' => function($sq) use ($schoolYear) {
                    $sq->wherePivot('school_year', $schoolYear);
                }]);
            }])
            ->where('school_year', $schoolYear)
            ->orderBy('grade_level')
            ->orderBy('section');

        if ($selectedGrade) {
            $studentRecordsQuery->where('grade_level', $selectedGrade);
        }
        if ($selectedSection) {
            $studentRecordsQuery->where('section', $selectedSection);
        }

        $studentRecords = $studentRecordsQuery->get()->groupBy('section');

        // Fallback collections for views
        // 1) All student records for this school year (flat list)
        $allStudentRecordsQuery = StudentYearlyRecord::with(['student' => function($q) use ($schoolYear) {
                $q->with(['subjects' => function($sq) use ($schoolYear) {
                    $sq->wherePivot('school_year', $schoolYear);
                }]);
            }])
            ->where('school_year', $schoolYear)
            ->orderBy('grade_level');

        if ($selectedGrade) {
            $allStudentRecordsQuery->where('grade_level', $selectedGrade);
        }
        if ($selectedSection) {
            $allStudentRecordsQuery->where('section', $selectedSection);
        }

        $allStudentRecords = $allStudentRecordsQuery->get();

        // 2) Students without an assigned section for this year
        $unsectionedStudentRecordsQuery = StudentYearlyRecord::with(['student' => function($q) use ($schoolYear) {
                $q->with(['subjects' => function($sq) use ($schoolYear) {
                    $sq->wherePivot('school_year', $schoolYear);
                }]);
            }])
            ->where('school_year', $schoolYear)
            ->where(function($q) {
                $q->whereNull('section')->orWhere('section', '');
            })
            ->orderBy('grade_level');

        if ($selectedGrade) {
            $unsectionedStudentRecordsQuery->where('grade_level', $selectedGrade);
        }

        $unsectionedStudentRecords = $unsectionedStudentRecordsQuery->get();

        // Filter dropdown options
        $availableGradeLevels = StudentYearlyRecord::where('school_year', $schoolYear)
            ->select('grade_level')
            ->distinct()
            ->orderBy('grade_level')
            ->pluck('grade_level');

        $availableSections = StudentYearlyRecord::where('school_year', $schoolYear)
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->select('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        // Get teacher records for the year (paginated for view links())
        $teacherRecords = TeacherYearlyRecord::with('teacher')
            ->where('school_year', $schoolYear)
            ->orderBy('department')
            ->orderBy('position')
            ->paginate(15);
            
        // Get statistics for this year
        $yearStats = [
            'total_students' => StudentYearlyRecord::where('school_year', $schoolYear)->count(),
            'total_teachers' => TeacherYearlyRecord::where('school_year', $schoolYear)->count(),
            'students_by_grade' => StudentYearlyRecord::where('school_year', $schoolYear)
                ->select('grade_level', DB::raw('count(*) as count'))
                ->groupBy('grade_level')
                ->orderBy('grade_level')
                ->get(),
            'students_by_status' => StudentYearlyRecord::where('school_year', $schoolYear)
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
            'teachers_by_department' => TeacherYearlyRecord::where('school_year', $schoolYear)
                ->select('department', DB::raw('count(*) as count'))
                ->groupBy('department')
                ->orderBy('department')
                ->get(),
            'teachers_by_status' => TeacherYearlyRecord::where('school_year', $schoolYear)
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
        ];
        
        return view('registrar.yearly-records.show', compact(
            'schoolYear',
            'sections',
            'studentRecords',
            'allStudentRecords',
            'unsectionedStudentRecords',
            'teacherRecords',
            'yearStats',
            'availableGradeLevels',
            'availableSections',
            'selectedGrade',
            'selectedSection'
        ));
    }

    /**
     * Create records for new school year
     */
    public function createNewYear(Request $request)
    {
        $request->validate([
            'school_year' => 'required|string|regex:/^\d{4}-\d{4}$/',
            'copy_from_year' => 'nullable|string|regex:/^\d{4}-\d{4}$/',
        ]);
        
        $newSchoolYear = $request->school_year;
        $copyFromYear = $request->copy_from_year;
        
        // Check if records already exist for this year
        $existingStudentRecords = StudentYearlyRecord::where('school_year', $newSchoolYear)->exists();
        $existingTeacherRecords = TeacherYearlyRecord::where('school_year', $newSchoolYear)->exists();
        
        if ($existingStudentRecords || $existingTeacherRecords) {
            return back()->with('error', 'Records for this school year already exist.');
        }
        
        DB::transaction(function () use ($newSchoolYear, $copyFromYear) {
            // Create student records
            if ($copyFromYear) {
                // Copy from previous year and promote students
                $previousStudentRecords = StudentYearlyRecord::where('school_year', $copyFromYear)
                    ->where('status', 'enrolled')
                    ->get();
                    
                foreach ($previousStudentRecords as $record) {
                    // Promote grade level
                    $newGradeLevel = $this->promoteGradeLevel($record->grade_level);
                    
                    if ($newGradeLevel) {
                        StudentYearlyRecord::create([
                            'student_id' => $record->student_id,
                            'school_year' => $newSchoolYear,
                            'grade_level' => $newGradeLevel,
                            'section' => null, // To be assigned later
                            'status' => 'enrolled',
                        ]);
                    }
                }
                
                // Copy teacher records
                $previousTeacherRecords = TeacherYearlyRecord::where('school_year', $copyFromYear)
                    ->where('status', 'active')
                    ->get();
                    
                foreach ($previousTeacherRecords as $record) {
                    TeacherYearlyRecord::create([
                        'teacher_id' => $record->teacher_id,
                        'school_year' => $newSchoolYear,
                        'department' => $record->department,
                        'position' => $record->position,
                        'subjects_taught' => $record->subjects_taught,
                        'grade_levels_handled' => $record->grade_levels_handled,
                        'employment_status' => $record->employment_status,
                        'status' => 'active',
                    ]);
                }
            } else {
                // Create records for all current students
                $students = Student::all();
                foreach ($students as $student) {
                    StudentYearlyRecord::create([
                        'student_id' => $student->id,
                        'school_year' => $newSchoolYear,
                        'grade_level' => $student->grade_level,
                        'section' => $student->section,
                        'status' => 'enrolled',
                    ]);
                }
                
                // Create records for all current teachers
                $teachers = Teacher::where('status', 'active')->get();
                foreach ($teachers as $teacher) {
                    TeacherYearlyRecord::create([
                        'teacher_id' => $teacher->id,
                        'school_year' => $newSchoolYear,
                        'department' => $teacher->department ?? 'General',
                        'position' => 'Teacher',
                        'employment_status' => 'regular',
                        'status' => 'active',
                    ]);
                }
            }
        });
        
        return redirect()->route('registrar.yearly-records.show', $newSchoolYear)
            ->with('success', 'New school year records created successfully.');
    }

    /**
     * Ensure required tables exist, create them if they don't
     */
    private function ensureTablesExist()
    {
        try {
            // Check if student_yearly_records table exists
            if (!\Illuminate\Support\Facades\Schema::hasTable('student_yearly_records')) {
                \Illuminate\Support\Facades\Schema::create('student_yearly_records', function ($table) {
                    $table->id();
                    $table->foreignId('student_id')->nullable()->constrained()->onDelete('cascade');
                    $table->string('school_year');
                    $table->string('grade_level');
                    $table->string('section')->nullable();
                    $table->string('status')->default('enrolled');
                    $table->timestamps();

                    $table->index(['school_year']);
                    $table->index(['grade_level']);
                    $table->index(['status']);
                });

                // Add sample data
                $currentYear = date('Y');
                $schoolYears = [
                    ($currentYear - 1) . '-' . $currentYear,
                    $currentYear . '-' . ($currentYear + 1)
                ];

                foreach ($schoolYears as $schoolYear) {
                    \Illuminate\Support\Facades\DB::table('student_yearly_records')->insert([
                        'student_id' => null,
                        'school_year' => $schoolYear,
                        'grade_level' => 'Grade 11',
                        'section' => 'A',
                        'status' => 'enrolled',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Check if teacher_yearly_records table exists
            if (!\Illuminate\Support\Facades\Schema::hasTable('teacher_yearly_records')) {
                \Illuminate\Support\Facades\Schema::create('teacher_yearly_records', function ($table) {
                    $table->id();
                    $table->foreignId('teacher_id')->nullable()->constrained()->onDelete('cascade');
                    $table->string('school_year');
                    $table->string('department')->nullable();
                    $table->string('position')->nullable();
                    $table->json('subjects_taught')->nullable();
                    $table->json('grade_levels_handled')->nullable();
                    $table->string('advisory_section')->nullable();
                    $table->integer('total_students')->default(0);
                    $table->decimal('teaching_load', 5, 2)->default(0.00);
                    $table->string('employment_status')->default('regular');
                    $table->string('status')->default('active');
                    $table->text('notes')->nullable();
                    $table->date('start_date')->nullable();
                    $table->date('end_date')->nullable();
                    $table->timestamps();

                    $table->unique(['teacher_id', 'school_year']);
                    $table->index(['school_year']);
                });
            }
        } catch (\Exception $e) {
            // If table creation fails, log the error but don't break the page
            \Log::error('Failed to create yearly records tables: ' . $e->getMessage());
        }
    }

    /**
     * Promote grade level for next year
     */
    private function promoteGradeLevel($currentGrade)
    {
        switch ($currentGrade) {
            case 'Grade 11':
                return 'Grade 12';
            case 'Grade 12':
                return null; // Graduated
            default:
                return $currentGrade; // Keep same grade if not recognized
        }
    }

    /**
     * Get current school year
     */
    private function getCurrentSchoolYear()
    {
        $currentMonth = date('n');
        $currentYear = date('Y');
        
        // School year typically starts in June/July
        if ($currentMonth >= 6) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }
}
