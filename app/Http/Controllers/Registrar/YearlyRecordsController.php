<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentYearlyRecord;
use App\Models\TeacherYearlyRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class YearlyRecordsController extends Controller
{
    /**
     * Display yearly records overview
     */
    public function index()
    {
        // Check if tables exist and create them if they don't
        $this->ensureTablesExist();

        // Get available school years from both student and teacher records with error handling
        try {
            $studentYears = StudentYearlyRecord::select('school_year')
                ->distinct()
                ->orderBy('school_year', 'desc')
                ->pluck('school_year');
        } catch (\Exception $e) {
            $studentYears = collect([]);
        }

        try {
            $teacherYears = TeacherYearlyRecord::select('school_year')
                ->distinct()
                ->orderBy('school_year', 'desc')
                ->pluck('school_year');
        } catch (\Exception $e) {
            $teacherYears = collect([]);
        }
            
        $allYears = $studentYears->merge($teacherYears)->unique()->sort()->reverse()->values();
        
        // Get current school year
        $currentSchoolYear = $this->getCurrentSchoolYear();
        
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
    public function show($schoolYear)
    {
        // Validate school year format
        if (!preg_match('/^\d{4}-\d{4}$/', $schoolYear)) {
            return redirect()->route('registrar.yearly-records.index')
                ->with('error', 'Invalid school year format.');
        }
        
        // Get student records for the year
        $studentRecords = StudentYearlyRecord::with('student')
            ->where('school_year', $schoolYear)
            ->orderBy('grade_level')
            ->orderBy('section')
            ->paginate(20, ['*'], 'students_page');
            
        // Get teacher records for the year
        $teacherRecords = TeacherYearlyRecord::with('teacher')
            ->where('school_year', $schoolYear)
            ->orderBy('department')
            ->orderBy('position')
            ->paginate(20, ['*'], 'teachers_page');
            
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
            'studentRecords',
            'teacherRecords',
            'yearStats'
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
