<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\TemporaryStudentCredential;
use App\Imports\StudentsImport;
use App\Exports\StudentTemplateExport;
use App\Helpers\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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

        if ($request->filled('cluster')) {
            $query->where('cluster', $request->cluster);
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

        // Check if grades table exists before trying to load grades
        $hasGradesTable = \Illuminate\Support\Facades\Schema::hasTable('grades');

        if ($hasGradesTable) {
            $students = $query->with(['subjects', 'grades'])
                             ->orderBy('last_name')
                             ->orderBy('first_name')
                             ->paginate(20);
        } else {
            $students = $query->with(['subjects'])
                             ->orderBy('last_name')
                             ->orderBy('first_name')
                             ->paginate(20);
        }

        // Get filter options
        $gradeLevels = Student::distinct()->pluck('grade_level')->filter();
        $sections = Student::distinct()->pluck('section')->filter();
        $tracks = Student::distinct()->pluck('track')->filter();
        $clusters = Student::distinct()->pluck('cluster')->filter();

        return view('registrar.students.index', compact(
            'students',
            'gradeLevels',
            'sections',
            'tracks',
            'clusters'
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

        // Validate section-strand consistency when provided
        if (!empty($studentData['section'])) {
            $sectionModel = Section::where('name', $studentData['section'])->first();
            if ($sectionModel) {
                $sectionStrand = $sectionModel->strand ?: $sectionModel->track;
                $studentStrand = ($studentData['strand'] ?? null) ?: ($studentData['track'] ?? null);
                if ($studentStrand && strcasecmp($sectionStrand, $studentStrand) !== 0) {
                    return back()->withErrors(['section' => "Selected section does not belong to the student's strand."])->withInput();
                }
                if (method_exists($sectionModel, 'isFull') && $sectionModel->isFull()) {
                    return back()->withErrors(['section' => 'Selected section is already full.'])->withInput();
                }
            }
        }

        $student = Student::create($studentData);

        return redirect()->route('registrar.students.index')
                        ->with('success', 'Student profile created successfully.');
    }

    /**
     * Display the specified student
     */
    public function show(Student $student, Request $request)
    {
        // Determine selected school year (from query or active)
        $selectedYear = $request->query('school_year');
        if (!$selectedYear) {
            $selectedYear = optional(\App\Models\SchoolYear::active()->first())->name;
        }

        // Check if grades table exists before trying to load grades
        $hasGradesTable = \Illuminate\Support\Facades\Schema::hasTable('grades');

        // Eager load subjects limited to the selected school year and their grades for this student
        $student->load(['subjects' => function ($q) use ($selectedYear) {
            if ($selectedYear) {
                $q->wherePivot('school_year', $selectedYear);
            }
        }]);

        // Load yearly record for the selected school year
        $yearlyRecord = \App\Models\StudentYearlyRecord::where('student_id', $student->id)
            ->when($selectedYear, function($q) use ($selectedYear) {
                $q->where('school_year', $selectedYear);
            })
            ->first();

        // Load grades and map by subject for quick lookup in the view
        $gradesBySubject = collect();
        if ($hasGradesTable) {
            $student->load(['grades' => function($q) use ($selectedYear) {
                if ($selectedYear) {
                    $q->where('school_year', $selectedYear);
                }
            }, 'grades.subject']);
            $gradesBySubject = $student->grades->keyBy('subject_id');
        }

        // Determine available school years for this student (for quick switching)
        $availableSchoolYears = \App\Models\StudentYearlyRecord::where('student_id', $student->id)
            ->orderByDesc('school_year')
            ->pluck('school_year');

        // Calculate academic statistics based on the selected school year
        $enrolledSubjects = $student->subjects;
        $totalSubjects = $enrolledSubjects->count();

        $allGrades = collect();
        foreach ($enrolledSubjects as $subject) {
            if ($subject->pivot && $subject->pivot->grade !== null) {
                $allGrades->push((float)$subject->pivot->grade);
            }
        }
        if ($hasGradesTable) {
            $gradeRecords = $student->grades()->when($selectedYear, function($q) use ($selectedYear){
                    $q->where('school_year', $selectedYear);
                })
                ->whereNotNull('final_grade')
                ->get();
            foreach ($gradeRecords as $grade) {
                $allGrades->push((float)$grade->final_grade);
            }
        }

        $averageGrade = $allGrades->count() > 0 ? round($allGrades->avg(), 2) : null;
        $passedSubjects = $allGrades->filter(function($grade) {
            return $grade >= 75;
        })->count();
        $failedSubjects = $allGrades->filter(function($grade) {
            return $grade < 75;
        })->count();

        return view('registrar.students.show', [
            'student' => $student,
            'totalSubjects' => $totalSubjects,
            'averageGrade' => $averageGrade,
            'passedSubjects' => $passedSubjects,
            'failedSubjects' => $failedSubjects,
            'schoolYear' => $selectedYear,
            'yearlyRecord' => $yearlyRecord,
            'gradesBySubject' => $gradesBySubject,
            'availableSchoolYears' => $availableSchoolYears,
        ]);
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

        // Validate section-strand consistency when provided
        if (!empty($studentData['section'])) {
            $sectionModel = Section::where('name', $studentData['section'])->first();
            if ($sectionModel) {
                $sectionStrand = $sectionModel->strand ?: $sectionModel->track;
                $studentStrand = ($studentData['strand'] ?? null) ?: ($studentData['track'] ?? null);
                if ($studentStrand && strcasecmp($sectionStrand, $studentStrand) !== 0) {
                    return back()->withErrors(['section' => "Selected section does not belong to the student's strand."])->withInput();
                }
                if (method_exists($sectionModel, 'isFull') && $sectionModel->isFull()) {
                    return back()->withErrors(['section' => 'Selected section is already full.'])->withInput();
                }
            }
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

            // Delete all grades (only if grades table exists)
            if (\Illuminate\Support\Facades\Schema::hasTable('grades')) {
                $student->grades()->delete();
            }

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
                    // Delete grades only if grades table exists
                    if (\Illuminate\Support\Facades\Schema::hasTable('grades')) {
                        $student->grades()->delete();
                    }
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

    /**
     * Show Excel upload form
     */
    public function showUploadForm()
    {
        return view('registrar.students.upload');
    }

    /**
     * Handle Excel file upload and import students
     */
    public function uploadExcel(Request $request)
    {
        // Configure PHP settings for large file processing
        $originalSettings = UploadHelper::configureForLargeUploads();
        $startTime = microtime(true);

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:20480', // 20MB max
            'grade_level' => 'required|string|in:Grade 11,Grade 12',
            'track' => 'required|string|in:Academic Track,TVL Track,Sports Track,Arts and Design Track',
        ]);

        try {
            $file = $request->file('excel_file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $userId = auth()->guard('registrar')->id();

            // Validate file size using helper
            if (!UploadHelper::isFileSizeValid($fileSize)) {
                UploadHelper::restoreOriginalSettings($originalSettings);
                return redirect()->back()
                    ->with('error', 'File size exceeds the maximum allowed limit of ' . UploadHelper::formatBytes(UploadHelper::getMaxUploadSize()));
            }

            // Log upload attempt
            UploadHelper::logUploadAttempt($fileName, $fileSize, $userId);

            // Get selected grade level and track
            $gradeLevel = $request->input('grade_level');
            $track = $request->input('track');

            // Handle CSV files directly without Excel package
            if ($file->getClientOriginalExtension() === 'csv') {
                $result = $this->importCsvFile($file, $gradeLevel, $track);

                // Restore original settings
                UploadHelper::restoreOriginalSettings($originalSettings);

                return $result;
            }

            // Handle Excel files using Laravel Excel package with chunking
            $import = new StudentsImport($gradeLevel, $track);

            // Process with chunking for better memory management
            Excel::import($import, $file);

            $summary = [
                'success_count' => $import->getSuccessCount(),
                'update_count' => $import->getUpdateCount(),
                'skip_count' => $import->getSkipCount(),
                'error_count' => count($import->getErrors()),
                'errors' => $import->getErrors(),
                'detected_columns' => $import->getDetectedColumns(),
            ];

            // Calculate processing time
            $processingTime = round(microtime(true) - $startTime, 2);

            // Log successful upload
            UploadHelper::logUploadCompletion($fileName, $summary, $processingTime);

            // Restore original settings
            UploadHelper::restoreOriginalSettings($originalSettings);

            if ($import->hasErrors()) {
                return redirect()->back()
                    ->with('warning', 'Import completed with some errors.')
                    ->with('import_summary', $summary);
            }

            // Create detailed success message
            $successMessage = 'Students imported successfully! ';
            if ($summary['success_count'] > 0) {
                $successMessage .= $summary['success_count'] . ' new students created. ';
            }
            if ($summary['update_count'] > 0) {
                $successMessage .= $summary['update_count'] . ' existing student profiles updated. ';
            }

            return redirect()->route('registrar.students.index')
                ->with('success', $successMessage)
                ->with('import_summary', $summary);

        } catch (\Throwable $e) {
            // Restore original settings on error
            UploadHelper::restoreOriginalSettings($originalSettings);

            // Log the error
            UploadHelper::logUploadError($fileName ?? 'unknown', $e->getMessage(), $userId ?? null);

            // Provide user-friendly error messages
            $errorMessage = 'Error importing file: ';

            if (strpos($e->getMessage(), 'Maximum execution time') !== false) {
                $errorMessage .= 'The file is too large and took too long to process. Please try with a smaller file or contact support.';
            } elseif (strpos($e->getMessage(), 'memory') !== false) {
                $errorMessage .= 'The file is too large for available memory. Please try with a smaller file.';
            } elseif (strpos($e->getMessage(), 'timeout') !== false) {
                $errorMessage .= 'The upload timed out. Please try again or use a smaller file.';
            } else {
                $errorMessage .= $e->getMessage();
            }

            return redirect()->back()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Download sample Excel template
     */
    public function downloadTemplate(Request $request)
    {
        // SF1-SHS Format Headers based on DepEd School Form 1
        $headers = [
            'LRN',
            'Last Name',
            'First Name',
            'Middle Name',
            'Name Extension',
            'Date of Birth',
            'Age',
            'Sex',
            'Place of Birth',
            'Mother Tongue',
            'IP/Ethnicity',
            'Religion',
            'Complete Address',
            'Father\'s Name',
            'Mother\'s Name',
            'Guardian\'s Name',
            'Relationship',
            'Contact Number',
            'Date of Enrollment',
            'Grade Level',
            'Section',
            'Track',
            'Cluster',
            'Adviser',
            'School Year',
            'Remarks'
        ];

        // SF1-SHS Format Sample Data
        $sampleData = [
            [
                'LRN' => '123456789012',
                'Last Name' => 'DELA CRUZ',
                'First Name' => 'JUAN',
                'Middle Name' => 'SANTOS',
                'Name Extension' => '',
                'Date of Birth' => '05/15/2006',
                'Age' => '17',
                'Sex' => 'M',
                'Place of Birth' => 'QUEZON CITY',
                'Mother Tongue' => 'TAGALOG',
                'IP/Ethnicity' => 'FILIPINO',
                'Religion' => 'CATHOLIC',
                'Complete Address' => '123 MAIN ST, QUEZON CITY',
                'Father\'s Name' => 'PEDRO DELA CRUZ',
                'Mother\'s Name' => 'MARIA SANTOS DELA CRUZ',
                'Guardian\'s Name' => 'MARIA SANTOS DELA CRUZ',
                'Relationship' => 'MOTHER',
                'Contact Number' => '09123456789',
                'Date of Enrollment' => '08/15/2024',
                'Grade Level' => 'Grade 11',
                'Section' => 'EINSTEIN',
                'Track' => 'Academic Track',
                'Strand' => 'STEM',
                'Adviser' => 'MS. TEACHER',
                'School Year' => '2024-2025',
                'Remarks' => 'NEW'
            ],
            [
                'LRN' => '123456789013',
                'Last Name' => 'GARCIA',
                'First Name' => 'MARIA',
                'Middle Name' => 'LOPEZ',
                'Name Extension' => '',
                'Date of Birth' => '03/22/2006',
                'Age' => '17',
                'Sex' => 'F',
                'Place of Birth' => 'MANILA',
                'Mother Tongue' => 'TAGALOG',
                'IP/Ethnicity' => 'FILIPINO',
                'Religion' => 'CATHOLIC',
                'Complete Address' => '456 RIZAL ST, MANILA',
                'Father\'s Name' => 'JOSE GARCIA',
                'Mother\'s Name' => 'ANA LOPEZ GARCIA',
                'Guardian\'s Name' => 'ANA LOPEZ GARCIA',
                'Relationship' => 'MOTHER',
                'Contact Number' => '09987654321',
                'Date of Enrollment' => '08/15/2024',
                'Grade Level' => 'Grade 12',
                'Section' => 'NEWTON',
                'Track' => 'TVL Track',
                'Strand' => 'ICT',
                'Adviser' => 'MR. ADVISOR',
                'School Year' => '2024-2025',
                'Remarks' => 'TRANSFEREE'
            ]
        ];

        // Check if user wants Excel format
        $format = $request->get('format', 'csv');

        if ($format === 'excel') {
            // Create Excel template using dedicated export class
            try {
                // For the new SF1-SHS format, we don't need to pass headers and sample data
                // as they are built directly in the export class
                $export = new StudentTemplateExport([], []);
                return Excel::download($export, 'SF1-SHS_Student_Register_Template.xlsx');
            } catch (\Exception $e) {
                // Fallback to CSV if Excel fails
                return redirect()->route('registrar.students.template')
                    ->with('warning', 'Excel template generation failed. Downloaded CSV template instead.');
            }
        }

        // Default to CSV format - SF1-SHS Format
        $csvData = [
            '123456789012',
            'DELA CRUZ',
            'JUAN',
            'SANTOS',
            '',
            '05/15/2006',
            '17',
            'M',
            'QUEZON CITY',
            'TAGALOG',
            'FILIPINO',
            'CATHOLIC',
            '123 MAIN ST, QUEZON CITY',
            'PEDRO DELA CRUZ',
            'MARIA SANTOS DELA CRUZ',
            'MARIA SANTOS DELA CRUZ',
            'MOTHER',
            '09123456789',
            '08/15/2024',
            'Grade 11',
            'EINSTEIN',
            'Academic Track',
            'STEM',
            'MS. TEACHER',
            '2024-2025',
            'NEW'
        ];

        $filename = 'SF1-SHS_Student_Register_Template.csv';

        $callback = function() use ($headers, $csvData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fputcsv($file, $csvData);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Import CSV file directly
     */
    private function importCsvFile($file, $gradeLevel = null, $track = null)
    {
        $successCount = 0;
        $updateCount = 0;
        $errorCount = 0;
        $errors = [];

        try {
            $handle = fopen($file->getPathname(), 'r');

            // Read header row
            $headers = fgetcsv($handle);
            if (!$headers) {
                throw new \Exception('Invalid CSV file format');
            }

            // Convert headers to lowercase for easier matching
            $headers = array_map('strtolower', $headers);

            // Check for required student_id column
            if (!in_array('student_id', $headers)) {
                throw new \Exception('CSV file must contain a "student_id" column');
            }

            $rowNumber = 2; // Start from row 2 (after header)

            while (($data = fgetcsv($handle)) !== false) {
                try {
                    $studentData = [];

                    // Map CSV data to student fields
                    foreach ($headers as $index => $header) {
                        if (isset($data[$index])) {
                            $value = trim($data[$index]);

                            // Handle date parsing for date_of_birth
                            if ($header === 'date_of_birth' && !empty($value)) {
                                $value = $this->parseDate($value);
                            }

                            $studentData[$header] = $value;
                        }
                    }

                    // Validate required fields
                    if (empty($studentData['student_id'])) {
                        $errors[] = "Row {$rowNumber}: Student ID is required";
                        $errorCount++;
                        $rowNumber++;
                        continue;
                    }

                    // Check if student exists
                    $existingStudent = Student::where('student_id', $studentData['student_id'])->first();

                    if ($existingStudent) {
                        // Update existing student
                        $this->updateStudentFromCsv($existingStudent, $studentData, $gradeLevel, $track);
                        $updateCount++;
                    } else {
                        // Create new student
                        $this->createStudentFromCsv($studentData, $gradeLevel, $track);
                        $successCount++;
                    }

                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    $errorCount++;
                }

                $rowNumber++;
            }

            fclose($handle);

            $summary = [
                'success_count' => $successCount,
                'update_count' => $updateCount,
                'skip_count' => 0,
                'error_count' => $errorCount,
                'errors' => $errors,
            ];

            if ($errorCount > 0) {
                return redirect()->back()
                    ->with('warning', 'Import completed with some errors.')
                    ->with('import_summary', $summary);
            }

            return redirect()->route('registrar.students.index')
                ->with('success', 'Students imported successfully!')
                ->with('import_summary', $summary);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error importing CSV file: ' . $e->getMessage());
        }
    }

    /**
     * Update existing student from CSV data
     */
    private function updateStudentFromCsv($student, $data, $gradeLevel = null, $track = null)
    {
        // Apply selected grade level and track if provided
        if ($gradeLevel) {
            $data['grade_level'] = $gradeLevel;
        }
        if ($track) {
            $data['track'] = $track;
        }

        // Mark as registrar-managed data
        $data['registrar_data_uploaded'] = true;
        $data['registrar_upload_date'] = now();
        $data['allow_profile_edit'] = false; // Prevent students from editing

        // Don't update password if student already has one and it's not temporary
        if (!$student->is_temporary_account && $student->password) {
            unset($data['password']);
        }

        $student->update($data);
    }

    /**
     * Create new student from CSV data
     */
    private function createStudentFromCsv($data, $gradeLevel = null, $track = null)
    {
        // Apply selected grade level and track if provided
        if ($gradeLevel) {
            $data['grade_level'] = $gradeLevel;
        }
        if ($track) {
            $data['track'] = $track;
        }

        // Generate "Temp_123" password structure
        $plainPassword = 'Temp_123';
        if (empty($data['password'])) {
            $data['password'] = Hash::make($plainPassword);
            $data['is_temporary_account'] = true;
        } else {
            $plainPassword = $data['password']; // Use provided password
            $data['password'] = Hash::make($data['password']);
        }

        // Mark as registrar-managed data
        $data['registrar_data_uploaded'] = true;
        $data['registrar_upload_date'] = now();
        $data['allow_profile_edit'] = false; // Prevent students from editing
        $data['profile_completed'] = false;

        // Create the student record
        $student = Student::create($data);

        // Create TemporaryStudentCredential record for admin portal display
        TemporaryStudentCredential::create([
            'student_id' => $data['student_id'],
            'password' => $plainPassword, // Store plain password for display
            'created_by_registrar_id' => auth()->guard('registrar')->id(),
            'source' => 'csv_upload',
            'notes' => 'Generated from CSV upload - ' . ($gradeLevel ?? 'Unknown Grade') . ' - ' . ($track ?? 'Unknown Track'),
            'is_used' => false
        ]);
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        // Try different date formats
        $formats = ['Y-m-d', 'm/d/Y', 'd/m/Y', 'Y/m/d', 'm-d-Y', 'd-m-Y'];

        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $dateString);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        // If no format matches, try strtotime
        $timestamp = strtotime($dateString);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }

        return null;
    }

    /**
     * Show yearly student records overview
     */
    public function yearlyRecords()
    {
        // Get all years that have student records
        $years = Student::selectRaw('YEAR(created_at) as year, COUNT(*) as count')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        // Get current year statistics
        $currentYear = date('Y');
        $currentYearStats = [
            'total_students' => Student::whereYear('created_at', $currentYear)->count(),
            'grade_11' => Student::whereYear('created_at', $currentYear)->where('grade_level', 'Grade 11')->count(),
            'grade_12' => Student::whereYear('created_at', $currentYear)->where('grade_level', 'Grade 12')->count(),
            'academic_track' => Student::whereYear('created_at', $currentYear)->where('track', 'Academic')->count(),
            'tvl_track' => Student::whereYear('created_at', $currentYear)->where('track', 'TVL')->count(),
        ];

        // Get recent activity
        $recentStudents = Student::whereYear('created_at', $currentYear)
            ->latest()
            ->take(10)
            ->get();

        return view('registrar.students.yearly-records', compact(
            'years',
            'currentYear',
            'currentYearStats',
            'recentStudents'
        ));
    }

    /**
     * Show students for a specific year
     */
    public function showYearlyRecords($year)
    {
        // Validate year
        if (!is_numeric($year) || $year < 2020 || $year > date('Y') + 1) {
            return redirect()->route('registrar.students.records')
                ->with('error', 'Invalid year specified.');
        }

        // Get students for the specified year
        $students = Student::whereYear('created_at', $year)
            ->with(['subjects'])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(50);

        // Get statistics for this year
        $yearStats = [
            'total_students' => Student::whereYear('created_at', $year)->count(),
            'grade_11' => Student::whereYear('created_at', $year)->where('grade_level', 'Grade 11')->count(),
            'grade_12' => Student::whereYear('created_at', $year)->where('grade_level', 'Grade 12')->count(),
            'academic_track' => Student::whereYear('created_at', $year)->where('track', 'Academic')->count(),
            'tvl_track' => Student::whereYear('created_at', $year)->where('track', 'TVL')->count(),
            'male_students' => Student::whereYear('created_at', $year)->where('gender', 'Male')->count(),
            'female_students' => Student::whereYear('created_at', $year)->where('gender', 'Female')->count(),
        ];

        // Get track and strand breakdown
        $trackBreakdown = Student::whereYear('created_at', $year)
            ->selectRaw('track, strand, COUNT(*) as count')
            ->groupBy('track', 'strand')
            ->orderBy('track')
            ->orderBy('strand')
            ->get();

        return view('registrar.students.yearly-records-detail', compact(
            'students',
            'year',
            'yearStats',
            'trackBreakdown'
        ));
    }

    /**
     * Archive students for a specific year
     */
    public function archiveYear($year)
    {
        try {
            // Validate year
            if (!is_numeric($year) || $year < 2020 || $year >= date('Y')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot archive current year or invalid year.'
                ]);
            }

            // Count students to be archived
            $studentCount = Student::whereYear('created_at', $year)->count();

            if ($studentCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No students found for year ' . $year
                ]);
            }

            // Add archived flag to students (you might want to create an archived_students table instead)
            Student::whereYear('created_at', $year)
                ->update([
                    'archived' => true,
                    'archived_at' => now(),
                    'archived_by' => auth()->guard('registrar')->id()
                ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully archived {$studentCount} students from year {$year}."
            ]);

        } catch (\Exception $e) {
            \Log::error('Error archiving year: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error archiving students: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export yearly records to Excel
     */
    public function exportYearlyRecords($year)
    {
        try {
            // Validate year
            if (!is_numeric($year) || $year < 2020 || $year > date('Y') + 1) {
                return redirect()->route('registrar.students.records')
                    ->with('error', 'Invalid year specified.');
            }

            // Get students for the year
            $students = Student::whereYear('created_at', $year)
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();

            if ($students->isEmpty()) {
                return redirect()->route('registrar.students.records')
                    ->with('error', 'No students found for year ' . $year);
            }

            // Create export data
            $exportData = [];
            foreach ($students as $student) {
                $exportData[] = [
                    'Student ID' => $student->student_id,
                    'Last Name' => $student->last_name,
                    'First Name' => $student->first_name,
                    'Middle Name' => $student->middle_name,
                    'Email' => $student->email,
                    'Grade Level' => $student->grade_level,
                    'Track' => $student->track,
                    'Strand' => $student->strand,
                    'Gender' => $student->gender,
                    'Date of Birth' => $student->date_of_birth,
                    'Contact Number' => $student->contact_number,
                    'Address' => $student->address,
                    'Parent Name' => $student->parent_name,
                    'Parent Contact' => $student->parent_contact,
                    'Enrollment Date' => $student->created_at->format('Y-m-d'),
                ];
            }

            // Create Excel export
            $export = new StudentTemplateExport(
                array_keys($exportData[0]),
                $exportData
            );

            $filename = "student_records_{$year}_" . date('Y-m-d_H-i-s') . '.xlsx';

            return Excel::download($export, $filename);

        } catch (\Exception $e) {
            \Log::error('Error exporting yearly records: ' . $e->getMessage());
            return redirect()->route('registrar.students.records')
                ->with('error', 'Error exporting records: ' . $e->getMessage());
        }
    }
}
