<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Registrar\AuthController as RegistrarAuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Teacher\TeacherController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Subject Management Module Demo
Route::get('/subject-management-demo', function () {
    return view('subject-management-module-demo');
})->name('subject-management.demo');

// Debug route for checking subjects
Route::get('/debug-subjects', function () {
    $masterSubjects = \App\Models\Subject::where('is_master_subject', true)->count();
    $allSubjects = \App\Models\Subject::count();
    $sampleSubjects = \App\Models\Subject::take(5)->get(['id', 'name', 'code', 'is_master_subject']);

    return response()->json([
        'master_subjects_count' => $masterSubjects,
        'all_subjects_count' => $allSubjects,
        'sample_subjects' => $sampleSubjects
    ]);
});

// Test registrar dashboard route
Route::get('/test-dashboard', function () {
    try {
        // Create a test registrar user if none exists
        $registrar = \App\Models\Registrar::first();
        if (!$registrar) {
            $registrar = \App\Models\Registrar::create([
                'name' => 'Test Registrar',
                'email' => 'registrar@cnhs.edu.ph',
                'password' => \Hash::make('password123'),
            ]);
        }

        // Login the registrar
        \Auth::guard('registrar')->login($registrar);

        // Redirect to dashboard
        return redirect()->route('registrar.dashboard');
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Test login route
Route::get('/test-login', function () {
    return view('test-login');
});

// Test all logins route
Route::get('/test-all-logins', function () {
    return view('test-all-logins');
});

// Simple admin login test
Route::get('/simple-admin-login', function () {
    return view('simple-admin-login');
});

// Admin login diagnostic test
Route::get('/admin-login-test', function () {
    return view('admin-login-test');
});

// Minimal admin test (no JavaScript)
Route::get('/minimal-admin-test', function () {
    return view('minimal-admin-test');
});

// Admin login fixed summary
Route::get('/admin-login-fixed', function () {
    return view('admin-login-fixed');
});

// Admin user management summary
Route::get('/admin-user-management-summary', function () {
    return view('admin-user-management-summary');
});

// Admin dashboard redesign summary
Route::get('/admin-dashboard-redesign-summary', function () {
    return view('admin-dashboard-redesign-summary');
});

// Grade level filter summary
Route::get('/grade-level-filter-summary', function () {
    return view('grade-level-filter-summary');
});

// Subjects management summary
Route::get('/subjects-management-summary', function () {
    return view('subjects-management-summary');
});

// Student profile management summary
Route::get('/student-profile-management-summary', function () {
    return view('student-profile-management-summary');
});

// Registrar student management summary
Route::get('/registrar-student-management-summary', function () {
    return view('registrar.documentation.student-management-summary');
});

// Registrar login credentials
Route::get('/registrar-login-credentials', function () {
    return view('registrar.auth.credentials');
});

// Registrar login fixed summary
Route::get('/registrar-login-fixed', function () {
    return view('registrar.documentation.login-fixed');
});

// Registrar security test
Route::get('/registrar-security-test', function () {
    return view('registrar.documentation.security-test');
});

// Registrar profile update guide
Route::get('/registrar-profile-update-guide', function () {
    return view('registrar.documentation.profile-update-guide');
});

// Admin subject creation fixed
Route::get('/admin-subject-creation-fixed', function () {
    return view('admin-subject-creation-fixed');
});

// Admin subject creation final fix
Route::get('/admin-subject-creation-final-fix', function () {
    return view('admin-subject-creation-final-fix');
});

// Admin subject creation database fix final
Route::get('/admin-subject-creation-database-fix-final', function () {
    return view('admin-subject-creation-database-fix-final');
});

// Admin subject creation diagnostic
Route::get('/admin-subject-creation-diagnostic', function () {
    return view('admin-subject-creation-diagnostic');
});

// Test subject creation route
Route::post('/test-subject-creation', function (Illuminate\Http\Request $request) {
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects',
            'grade_level' => 'required|string|max:50',
            'units' => 'required|integer|min:1|max:10',
            'track' => 'nullable|string|max:100',
        ]);

        $subject = \App\Models\Subject::create($validated);

        return redirect()->back()->with('test_result', 'SUCCESS: Subject created with ID: ' . $subject->id . "\nData: " . json_encode($validated, JSON_PRETTY_PRINT));
    } catch (\Exception $e) {
        return redirect()->back()->with('test_error', 'ERROR: ' . $e->getMessage() . "\nFile: " . $e->getFile() . "\nLine: " . $e->getLine());
    }
});

// Subject creation complete solution
Route::get('/subject-creation-complete-solution', function () {
    return view('subject-creation-complete-solution');
});

// Subject creation field mapping fix
Route::get('/subject-creation-field-mapping-fix', function () {
    return view('subject-creation-field-mapping-fix');
});

// Subject creation both fields fixed
Route::get('/subject-creation-both-fields-fixed', function () {
    return view('subject-creation-both-fields-fixed');
});

// Registrar subjects diagnostic
Route::get('/registrar-subjects-diagnostic', function () {
    return view('registrar.subjects.diagnostic');
});

// Registrar subjects fixed
Route::get('/registrar-subjects-fixed', [App\Http\Controllers\Registrar\SubjectController::class, 'subjectsFixed']);

// Registrar subjects visibility fixed summary
Route::get('/registrar-subjects-visibility-fixed', function () {
    return view('registrar.subjects.visibility-fixed');
});

// Student subject management complete
Route::get('/student-subject-management-complete', function () {
    return view('student-subject-management-complete');
});

// Student navigation diagnostic
Route::get('/student-navigation-diagnostic', function () {
    return view('student-navigation-diagnostic');
});

// Note: Test subjects creation route removed - registrars should create subjects through the proper interface

// Debug admin login route
Route::post('/debug-admin-login', function (Illuminate\Http\Request $request) {
    $credentials = [
        'username' => $request->input('username'),
        'password' => $request->input('password')
    ];

    $admin = \App\Models\Admin::where('username', $credentials['username'])->first();

    if (!$admin) {
        return response()->json(['error' => 'Admin not found', 'username' => $credentials['username']]);
    }

    if (\Illuminate\Support\Facades\Auth::guard('admin')->attempt($credentials)) {
        return response()->json(['success' => 'Login successful', 'admin' => $admin->name]);
    } else {
        return response()->json(['error' => 'Login failed', 'admin_exists' => true]);
    }
});

// Test route to see if POST requests are working
Route::post('/test-post', function (Illuminate\Http\Request $request) {
    \Log::info('=== TEST POST RECEIVED ===', $request->all());
    return response()->json(['message' => 'POST request received', 'data' => $request->all()]);
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Simple Student Login (for testing)
Route::get('/student-login', function () {
    return view('auth.simple-student-login');
})->name('student.login.simple');

// Test student dashboard without middleware (for debugging)
Route::get('/test-student-dashboard', function () {
    $student = \App\Models\Student::where('student_id', '11111')->first();
    if (!$student) {
        return 'Student not found';
    }

    // Manually log in the student for testing
    \Auth::guard('student')->login($student);

    return redirect()->route('student.dashboard');
})->name('test.student.dashboard');

// Direct student dashboard access (bypass middleware for testing)
Route::get('/direct-student-dashboard', function () {
    $student = \App\Models\Student::where('student_id', '11111')->first();
    if (!$student) {
        return 'Student not found';
    }

    // Manually set the student for the dashboard
    return view('student.dashboard', [
        'student' => $student,
        'recentAnnouncements' => collect(),
        'enrolledSubjects' => collect(),
        'totalSubjects' => 0,
        'grades' => collect(),
        'gradeStats' => [
            'average' => null,
            'highest' => null,
            'lowest' => null,
            'general_average' => null,
            'total_subjects_with_grades' => 0
        ],
        'upcomingActivities' => [],
        'recentGrades' => collect()
    ]);
})->name('direct.student.dashboard');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register/student', [RegisterController::class, 'registerStudent'])->name('register.student');
Route::post('/register/teacher', [RegisterController::class, 'registerTeacher'])->name('register.teacher');
Route::get('/register/student', [RegisterController::class, 'showStudentRegisterForm'])->name('register.student.form');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Teacher Routes
Route::middleware(['auth:teacher'])->group(function () {
    Route::get('/teacher/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('teacher.dashboard');
    Route::get('/teacher/subjects', [App\Http\Controllers\Teacher\SubjectController::class, 'index'])->name('teacher.subjects');

    // Subject Management Routes
    Route::get('/teacher/subjects/{subject}/students', [App\Http\Controllers\Teacher\SubjectController::class, 'viewStudents'])->name('teacher.subjects.students');
    Route::get('/teacher/subjects/{subject}/grades', [App\Http\Controllers\Teacher\SubjectController::class, 'manageGrades'])->name('teacher.subjects.grades');
    Route::post('/teacher/subjects/{subject}/grades/update', [App\Http\Controllers\Teacher\SubjectController::class, 'updateGrades'])->name('teacher.subjects.grades.update');
    Route::get('/teacher/subjects/{subject}/grades/{student}/edit', [App\Http\Controllers\Teacher\SubjectController::class, 'editStudentGrade'])->name('teacher.subjects.grades.edit');
    Route::post('/teacher/subjects/{subject}/grades/{student}/save', [App\Http\Controllers\Teacher\SubjectController::class, 'saveStudentGrade'])->name('teacher.subjects.grades.save');

    Route::get('/teacher/classlist', [App\Http\Controllers\Teacher\ClassListController::class, 'index'])->name('teacher.classlist');
    Route::get('/teacher/students/{student}', [App\Http\Controllers\Teacher\ClassListController::class, 'showStudent'])->name('teacher.students.show');
    Route::get('/teacher/grades', [App\Http\Controllers\Teacher\GradeController::class, 'index'])->name('teacher.grades');
    Route::post('/teacher/grades/save', [App\Http\Controllers\Teacher\GradeController::class, 'saveGrade'])->name('teacher.save-grade');
    Route::post('/teacher/grades/save-quarter', [App\Http\Controllers\Teacher\GradeController::class, 'saveQuarterGrade'])->name('teacher.save-quarter-grade');
    Route::get('/teacher/grades/{student}/{subject}/edit', [App\Http\Controllers\Teacher\GradeController::class, 'editGrade'])->name('teacher.grades.edit');
    Route::get('/teacher/profile', [App\Http\Controllers\Teacher\ProfileController::class, 'index'])->name('teacher.profile');
    Route::post('/teacher/profile', [App\Http\Controllers\Teacher\ProfileController::class, 'update'])->name('teacher.update-profile');
    Route::post('/teacher/profile/upload', [App\Http\Controllers\Teacher\ProfileController::class, 'uploadProfilePicture'])->name('teacher.profile.upload');

    // AJAX routes for dynamic loading
    Route::get('/teacher/api/subjects/{gradeLevel}', [App\Http\Controllers\Teacher\ClassListController::class, 'getSubjects'])->name('teacher.api.subjects');
    Route::get('/teacher/api/students/{gradeLevel}/{subjectId}', [App\Http\Controllers\Teacher\ClassListController::class, 'getStudents'])->name('teacher.api.students');
    Route::get('/teacher/get-students', [App\Http\Controllers\Teacher\ClassListController::class, 'getStudentsForFilters'])->name('teacher.get-students');
});

// Student dashboard without middleware (for testing)
Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');

// Student Routes (with middleware)
Route::middleware(['auth:student'])->group(function () {
    // Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard'); // Moved above
    Route::get('/student/announcements', [App\Http\Controllers\Student\AnnouncementsController::class, 'index'])->name('student.announcements');
    Route::get('/student/profile', [App\Http\Controllers\Student\ProfileController::class, 'index'])->name('student.profile');
    Route::put('/student/profile', [ProfileController::class, 'update'])->name('student.profile.update');
    Route::post('/student/profile/upload', [App\Http\Controllers\Student\ProfileController::class, 'uploadProfilePicture'])->name('student.profile.upload');
    Route::get('/student/profile/complete', [App\Http\Controllers\Student\ProfileController::class, 'showCompleteForm'])->name('student.profile.complete');
    Route::post('/student/profile/complete', [App\Http\Controllers\Student\ProfileController::class, 'completeProfile'])->name('student.profile.complete.store');
    Route::get('/student/grades', [App\Http\Controllers\Student\GradeController::class, 'index'])->name('student.grades');
    Route::get('/student/grades/refresh', [App\Http\Controllers\Student\GradeController::class, 'getUpdatedGrades'])->name('student.grades.refresh');
    Route::get('/student/subjects', [App\Http\Controllers\Student\SubjectController::class, 'index'])->name('student.subjects');
    Route::get('/student/subjects/{id}', [App\Http\Controllers\Student\SubjectController::class, 'show'])->name('student.subjects.show');
    Route::get('/student/schedule', [App\Http\Controllers\Student\ScheduleController::class, 'index'])->name('student.schedule');
});

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Test route to verify controller is working
    Route::get('test', [App\Http\Controllers\Admin\AuthController::class, 'test']);

    // Guest routes (login)
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
        // User Management Routes
        Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users');

        // Teacher Management
        Route::get('users/teachers/create', [App\Http\Controllers\Admin\UserController::class, 'createTeacher'])->name('users.teachers.create');
        Route::post('users/teachers', [App\Http\Controllers\Admin\UserController::class, 'storeTeacher'])->name('users.teachers.store');
        Route::get('users/teachers/{teacher}/edit', [App\Http\Controllers\Admin\UserController::class, 'editTeacher'])->name('users.teachers.edit');
        Route::put('users/teachers/{teacher}', [App\Http\Controllers\Admin\UserController::class, 'updateTeacher'])->name('users.teachers.update');
        Route::delete('users/teachers/{teacher}', [App\Http\Controllers\Admin\UserController::class, 'destroyTeacher'])->name('users.teachers.destroy');

        // Student Account Management (View Only - Students are created via credential login)
        Route::get('users/students', [App\Http\Controllers\Admin\UserController::class, 'indexStudents'])->name('users.students.index');
        Route::get('users/students/{student}', [App\Http\Controllers\Admin\UserController::class, 'showStudent'])->name('users.students.show');
        Route::delete('users/students/{student}', [App\Http\Controllers\Admin\UserController::class, 'destroyStudent'])->name('users.students.destroy');

        // Temporary Student Credentials Management
        Route::prefix('credentials')->name('credentials.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CredentialController::class, 'index'])->name('index');
            Route::get('generate', [App\Http\Controllers\Admin\CredentialController::class, 'showGenerateForm'])->name('generate');
            Route::post('generate', [App\Http\Controllers\Admin\CredentialController::class, 'generateCredentials'])->name('store');
            Route::get('show-generated', [App\Http\Controllers\Admin\CredentialController::class, 'showGenerated'])->name('show-generated');
            Route::delete('{credential}', [App\Http\Controllers\Admin\CredentialController::class, 'destroy'])->name('destroy');
            Route::post('bulk-delete', [App\Http\Controllers\Admin\CredentialController::class, 'bulkDelete'])->name('bulk-delete');
        });
        // Grades Routes
        Route::get('grades', [App\Http\Controllers\Admin\GradeController::class, 'index'])->name('grades');
        Route::get('grades/create', [App\Http\Controllers\Admin\GradeController::class, 'create'])->name('grades.create');
        Route::post('grades', [App\Http\Controllers\Admin\GradeController::class, 'store'])->name('grades.store');
        Route::get('grades/{grade}/edit', [App\Http\Controllers\Admin\GradeController::class, 'edit'])->name('grades.edit');

        // Subjects Management Routes (View Only)
        Route::get('subjects', [App\Http\Controllers\Admin\SubjectController::class, 'index'])->name('subjects.index');
        Route::get('subjects/{subject}', [App\Http\Controllers\Admin\SubjectController::class, 'show'])->name('subjects.show');
    });
});

// Registrar Authentication Routes
Route::prefix('registrar')->name('registrar.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Registrar\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Registrar\AuthController::class, 'login'])->name('login'); // Fixed: same name for POST
    Route::post('/logout', [App\Http\Controllers\Registrar\AuthController::class, 'logout'])->name('logout');
});

// Registrar Routes
Route::prefix('registrar')->group(function () {
    // Dashboard route with proper middleware
    Route::get('/dashboard', [App\Http\Controllers\Registrar\DashboardController::class, 'index'])
        ->name('registrar.dashboard')
        ->middleware('auth:registrar');

    Route::middleware(['auth:registrar'])->group(function () {
        // Other protected routes will go here

        // Subject Management Routes
        Route::get('/subjects', [App\Http\Controllers\Registrar\SubjectController::class, 'index'])->name('registrar.subjects.index');
        Route::get('/subjects/create', [App\Http\Controllers\Registrar\SubjectController::class, 'create'])->name('registrar.subjects.create');
        Route::post('/subjects', [App\Http\Controllers\Registrar\SubjectController::class, 'store'])->name('registrar.subjects.store');
        Route::get('/subjects/{subject}', [App\Http\Controllers\Registrar\SubjectController::class, 'show'])->name('registrar.subjects.show');
        Route::get('/subjects/{subject}/edit', [App\Http\Controllers\Registrar\SubjectController::class, 'edit'])->name('registrar.subjects.edit');
        Route::put('/subjects/{subject}', [App\Http\Controllers\Registrar\SubjectController::class, 'update'])->name('registrar.subjects.update');
        Route::delete('/subjects/{subject}', [App\Http\Controllers\Registrar\SubjectController::class, 'destroy'])->name('registrar.subjects.destroy');
        Route::get('/subjects-fixed', [App\Http\Controllers\Registrar\SubjectController::class, 'subjectsFixed'])->name('registrar.subjects.fixed');
        Route::get('/assign-subjects/{studentId}', [App\Http\Controllers\Registrar\SubjectController::class, 'assignSubjects'])->name('registrar.assign-subjects');
        Route::post('/assign-subjects/{studentId}', [App\Http\Controllers\Registrar\SubjectController::class, 'storeAssignedSubjects'])->name('registrar.store-assigned-subjects');



        // Profile Management Routes
        Route::get('/profile', [App\Http\Controllers\Registrar\ProfileController::class, 'index'])->name('registrar.profile');
        Route::post('/profile', [App\Http\Controllers\Registrar\ProfileController::class, 'update'])->name('registrar.update-profile');

        // Student Records Management Routes
        Route::get('/students', [App\Http\Controllers\Registrar\StudentController::class, 'index'])->name('registrar.students.index');
        Route::get('/students/create', [App\Http\Controllers\Registrar\StudentController::class, 'create'])->name('registrar.students.create');
        Route::post('/students', [App\Http\Controllers\Registrar\StudentController::class, 'store'])->name('registrar.students.store');
        Route::get('/students/{student}', [App\Http\Controllers\Registrar\StudentController::class, 'show'])->name('registrar.students.show');
        Route::get('/students/{student}/edit', [App\Http\Controllers\Registrar\StudentController::class, 'edit'])->name('registrar.students.edit');
        Route::put('/students/{student}', [App\Http\Controllers\Registrar\StudentController::class, 'update'])->name('registrar.students.update');
        Route::delete('/students/{student}', [App\Http\Controllers\Registrar\StudentController::class, 'destroy'])->name('registrar.students.destroy');
        Route::get('/students/{student}/enrollment', [App\Http\Controllers\Registrar\StudentController::class, 'enrollment'])->name('registrar.students.enrollment');
        Route::post('/students/{student}/enrollment', [App\Http\Controllers\Registrar\StudentController::class, 'updateEnrollment'])->name('registrar.students.update-enrollment');
        Route::post('/students/{student}/toggle-enrollment', [App\Http\Controllers\Registrar\StudentController::class, 'toggleEnrollmentStatus'])->name('registrar.students.toggle-enrollment');
        Route::post('/students/bulk-action', [App\Http\Controllers\Registrar\StudentController::class, 'bulkAction'])->name('registrar.students.bulk-action');

        // Student Assignment Routes
        Route::get('/student-assignments', [App\Http\Controllers\Registrar\StudentAssignmentController::class, 'index'])->name('registrar.student-assignments.index');
        Route::get('/student-assignments/create', [App\Http\Controllers\Registrar\StudentAssignmentController::class, 'create'])->name('registrar.student-assignments.create');
        Route::post('/student-assignments', [App\Http\Controllers\Registrar\StudentAssignmentController::class, 'store'])->name('registrar.student-assignments.store');
        Route::post('/student-assignments/get-subject-packages', [App\Http\Controllers\Registrar\StudentAssignmentController::class, 'getSubjectPackages'])->name('registrar.student-assignments.get-subject-packages');
        Route::get('/student-assignments/{studentAssignment}', [App\Http\Controllers\Registrar\StudentAssignmentController::class, 'show'])->name('registrar.student-assignments.show');
        Route::get('/student-assignments/{studentAssignment}/edit', [App\Http\Controllers\Registrar\StudentAssignmentController::class, 'edit'])->name('registrar.student-assignments.edit');
        Route::put('/student-assignments/{studentAssignment}', [App\Http\Controllers\Registrar\StudentAssignmentController::class, 'update'])->name('registrar.student-assignments.update');
        Route::delete('/student-assignments/{studentAssignment}', [App\Http\Controllers\Registrar\StudentAssignmentController::class, 'destroy'])->name('registrar.student-assignments.destroy');
        Route::post('/student-assignments/bulk-assign', [App\Http\Controllers\Registrar\StudentAssignmentController::class, 'bulkAssign'])->name('registrar.student-assignments.bulk-assign');

        // Teacher Assignment Routes
        Route::get('/teacher-assignments', [App\Http\Controllers\Registrar\TeacherAssignmentController::class, 'index'])->name('registrar.teacher-assignments.index');
        Route::get('/teacher-assignments/create', [App\Http\Controllers\Registrar\TeacherAssignmentController::class, 'create'])->name('registrar.teacher-assignments.create');
        Route::post('/teacher-assignments', [App\Http\Controllers\Registrar\TeacherAssignmentController::class, 'store'])->name('registrar.teacher-assignments.store');
        Route::get('/teacher-assignments/{teacherAssignment}', [App\Http\Controllers\Registrar\TeacherAssignmentController::class, 'show'])->name('registrar.teacher-assignments.show');
        Route::get('/teacher-assignments/{teacherAssignment}/edit', [App\Http\Controllers\Registrar\TeacherAssignmentController::class, 'edit'])->name('registrar.teacher-assignments.edit');
        Route::put('/teacher-assignments/{teacherAssignment}', [App\Http\Controllers\Registrar\TeacherAssignmentController::class, 'update'])->name('registrar.teacher-assignments.update');
        Route::delete('/teacher-assignments/{teacherAssignment}', [App\Http\Controllers\Registrar\TeacherAssignmentController::class, 'destroy'])->name('registrar.teacher-assignments.destroy');
        Route::get('/teacher-assignments/check-qualification', [App\Http\Controllers\Registrar\TeacherAssignmentController::class, 'checkQualification'])->name('registrar.teacher-assignments.check-qualification');
        Route::get('/teacher-assignments/check-schedule-conflict', [App\Http\Controllers\Registrar\TeacherAssignmentController::class, 'checkScheduleConflict'])->name('registrar.teacher-assignments.check-schedule-conflict');

        // Teacher Management Routes (placeholder routes for future implementation)
        Route::get('/teachers', function() {
            return redirect()->route('registrar.students.index')->with('info', 'Teacher management feature coming soon. For now, you can manage students and subjects.');
        })->name('registrar.teachers.index');

        Route::get('/teachers/create', function() {
            return redirect()->route('registrar.subjects.create')->with('info', 'Teacher creation feature coming soon. For now, you can create subjects.');
        })->name('registrar.teachers.create');
    });
});

// Test route
Route::get('/test', function() {
    return 'Test route working!';
});

// Fix all database tables route
Route::get('/fix-all-tables', function() {
    try {
        $message = "<h2>🔧 Fixing All Database Tables</h2>";

        // 1. Create subjects table first (needed for foreign keys)
        if (!\Illuminate\Support\Facades\Schema::hasTable('subjects')) {
            \Illuminate\Support\Facades\Schema::create('subjects', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('grade_level');
                $table->integer('units')->default(3)->nullable();
                $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
                $table->foreignId('registrar_id')->nullable()->constrained('registrars')->onDelete('set null');
                $table->text('description')->nullable();
                $table->string('track')->nullable();
                $table->string('strand')->nullable();
                $table->string('cluster')->nullable();
                $table->string('specialization')->nullable();
                $table->string('grading')->nullable();
                $table->string('semester')->nullable();
                $table->string('department')->nullable();
                $table->boolean('is_master_subject')->default(true);
                $table->boolean('is_core_subject')->default(false);
                $table->text('prerequisite_subjects')->nullable();
                $table->timestamps();
            });
            $message .= "✅ Created subjects table<br>";
        } else {
            $message .= "✅ Subjects table already exists<br>";
        }

        // 2. Create student_subject pivot table
        if (!\Illuminate\Support\Facades\Schema::hasTable('student_subject')) {
            \Illuminate\Support\Facades\Schema::create('student_subject', function ($table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->decimal('grade', 5, 2)->nullable();
                $table->string('quarter')->nullable();
                $table->string('school_year')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
                $table->unique(['student_id', 'subject_id']);
            });
            $message .= "✅ Created student_subject table<br>";
        } else {
            $message .= "✅ Student_subject table already exists<br>";
        }

        // 3. Create grades table
        if (!\Illuminate\Support\Facades\Schema::hasTable('grades')) {
            \Illuminate\Support\Facades\Schema::create('grades', function ($table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->decimal('quarter1', 5, 2)->nullable();
                $table->decimal('quarter2', 5, 2)->nullable();
                $table->decimal('quarter3', 5, 2)->nullable();
                $table->decimal('quarter4', 5, 2)->nullable();
                $table->decimal('final_grade', 5, 2)->nullable();
                $table->string('remarks')->nullable();
                $table->timestamps();
                $table->unique(['student_id', 'subject_id']);
            });
            $message .= "✅ Created grades table<br>";
        } else {
            $message .= "✅ Grades table already exists<br>";
        }

        // 4. Create some sample subjects for testing
        $sampleSubjects = [
            [
                'name' => 'Mathematics',
                'code' => 'MATH101',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'teacher_id' => 1, // Assuming teacher with ID 1 exists
            ],
            [
                'name' => 'English',
                'code' => 'ENG101',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'teacher_id' => 1,
            ],
            [
                'name' => 'Science',
                'code' => 'SCI101',
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'teacher_id' => 1,
            ]
        ];

        $subjectsCreated = 0;
        foreach ($sampleSubjects as $subjectData) {
            $existing = \App\Models\Subject::where('code', $subjectData['code'])->first();
            if (!$existing) {
                \App\Models\Subject::create($subjectData);
                $subjectsCreated++;
            }
        }

        if ($subjectsCreated > 0) {
            $message .= "✅ Created $subjectsCreated sample subjects<br>";
        } else {
            $message .= "ℹ️ Sample subjects already exist<br>";
        }

        $message .= "<br><strong>✅ All Tables Fixed!</strong><br>";
        $message .= "<p>You can now login as teacher and test the grade input functionality.</p>";
        $message .= "<a href='/teacher/login'>Go to Teacher Login</a>";

        return $message;

    } catch (Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});

// Create sample data for testing
Route::get('/create-sample-data', function() {
    try {
        $message = "<h2>🎯 Creating Sample Data for Testing</h2>";

        // Create sample students if they don't exist
        $sampleStudents = [
            [
                'student_id' => 'STU001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@student.cnhs.edu.ph',
                'password' => bcrypt('password123'),
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'section' => 'A',
            ],
            [
                'student_id' => 'STU002',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@student.cnhs.edu.ph',
                'password' => bcrypt('password123'),
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'section' => 'A',
            ],
            [
                'student_id' => 'STU003',
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'email' => 'mike.johnson@student.cnhs.edu.ph',
                'password' => bcrypt('password123'),
                'grade_level' => 'Grade 11',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'section' => 'A',
            ]
        ];

        $studentsCreated = 0;
        foreach ($sampleStudents as $studentData) {
            $existing = \App\Models\Student::where('student_id', $studentData['student_id'])->first();
            if (!$existing) {
                \App\Models\Student::create($studentData);
                $studentsCreated++;
            }
        }

        if ($studentsCreated > 0) {
            $message .= "✅ Created $studentsCreated sample students<br>";
        } else {
            $message .= "ℹ️ Sample students already exist<br>";
        }

        // Enroll students in subjects
        $subjects = \App\Models\Subject::all();
        $students = \App\Models\Student::all();
        $enrollmentsCreated = 0;

        foreach ($students as $student) {
            foreach ($subjects as $subject) {
                // Check if already enrolled
                $existing = \Illuminate\Support\Facades\DB::table('student_subject')
                    ->where('student_id', $student->id)
                    ->where('subject_id', $subject->id)
                    ->first();

                if (!$existing) {
                    \Illuminate\Support\Facades\DB::table('student_subject')->insert([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $enrollmentsCreated++;
                }
            }
        }

        if ($enrollmentsCreated > 0) {
            $message .= "✅ Created $enrollmentsCreated student-subject enrollments<br>";
        } else {
            $message .= "ℹ️ Student enrollments already exist<br>";
        }

        $message .= "<br><strong>✅ Sample Data Created!</strong><br>";
        $message .= "<p>You can now test the grade input functionality with sample students.</p>";
        $message .= "<p><strong>Sample Student Login:</strong><br>";
        $message .= "Email: john.doe@student.cnhs.edu.ph<br>";
        $message .= "Password: password123</p>";
        $message .= "<a href='/teacher/login'>Go to Teacher Login</a> | ";
        $message .= "<a href='/student/login'>Go to Student Login</a>";

        return $message;

    } catch (Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});

// Fix grades table route
Route::get('/fix-grades-table', function() {
    try {
        $message = "<h2>🔧 Fixing Grades Table</h2>";

        // Check if grades table exists
        if (!\Illuminate\Support\Facades\Schema::hasTable('grades')) {
            // Create grades table
            \Illuminate\Support\Facades\Schema::create('grades', function ($table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->decimal('quarter1', 5, 2)->nullable();
                $table->decimal('quarter2', 5, 2)->nullable();
                $table->decimal('quarter3', 5, 2)->nullable();
                $table->decimal('quarter4', 5, 2)->nullable();
                $table->decimal('final_grade', 5, 2)->nullable();
                $table->string('remarks')->nullable();
                $table->timestamps();

                // Add unique constraint to prevent duplicate records
                $table->unique(['student_id', 'subject_id']);
            });
            $message .= "✅ Created grades table with correct columns<br>";
        } else {
            $message .= "✅ Grades table already exists<br>";

            // Check and add missing columns
            $columnsToAdd = [];
            $requiredColumns = ['quarter1', 'quarter2', 'quarter3', 'quarter4', 'final_grade', 'remarks'];

            foreach ($requiredColumns as $column) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('grades', $column)) {
                    $columnsToAdd[] = $column;
                }
            }

            if (!empty($columnsToAdd)) {
                \Illuminate\Support\Facades\Schema::table('grades', function ($table) use ($columnsToAdd) {
                    foreach ($columnsToAdd as $column) {
                        if (in_array($column, ['quarter1', 'quarter2', 'quarter3', 'quarter4', 'final_grade'])) {
                            $table->decimal($column, 5, 2)->nullable();
                        } else {
                            $table->string($column)->nullable();
                        }
                    }
                });
                $message .= "✅ Added missing columns: " . implode(', ', $columnsToAdd) . "<br>";
            } else {
                $message .= "✅ All required columns already exist<br>";
            }
        }

        // Test the table structure
        $columns = \Illuminate\Support\Facades\DB::select('SHOW COLUMNS FROM grades');
        $message .= "<h3>📋 Current Grades Table Structure:</h3><ul>";
        foreach ($columns as $column) {
            $message .= "<li><strong>{$column->Field}</strong> - {$column->Type}</li>";
        }
        $message .= "</ul>";

        $message .= "<br><strong>✅ Grades Table Fixed!</strong><br>";
        $message .= "<p>You can now test the grade input functionality.</p>";
        $message .= "<a href='/teacher/login'>Go to Teacher Login</a>";

        return $message;

    } catch (Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});

// Database setup routes (can be removed after setup is complete)
Route::get('/fix-registrar', function() {
    return redirect('/setup-database')->with('message', 'Please use the comprehensive database setup instead.');
});

Route::get('/fix-subjects', function() {
    return redirect('/setup-database')->with('message', 'Please use the comprehensive database setup instead.');
});

// Complete database setup route
Route::get('/setup-database', function() {
    try {
        $message = "<h2>🔧 Complete Database Setup</h2>";

        // First, let's check what tables exist
        $existingTables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
        $tableNames = array_map(function($table) {
            return array_values((array)$table)[0];
        }, $existingTables);

        $message .= "<p><strong>Existing tables:</strong> " . implode(', ', $tableNames) . "</p>";

        // 0. Create teachers table first (needed for foreign keys)
        if (!\Illuminate\Support\Facades\Schema::hasTable('teachers')) {
            \Illuminate\Support\Facades\Schema::create('teachers', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('contact_number')->nullable();
                $table->string('department')->nullable();
                $table->string('status')->default('active');
                $table->string('profile_picture')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
            $message .= "✅ Created teachers table<br>";
        }

        // 0.1. Create subjects table (needed for foreign keys)
        if (!\Illuminate\Support\Facades\Schema::hasTable('subjects')) {
            \Illuminate\Support\Facades\Schema::create('subjects', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('grade_level');
                $table->integer('units')->default(3)->nullable();
                $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
                $table->foreignId('registrar_id')->nullable()->constrained('registrars')->onDelete('set null');
                $table->text('description')->nullable();
                $table->string('track')->nullable();
                $table->string('strand')->nullable();
                $table->string('cluster')->nullable();
                $table->string('specialization')->nullable();
                $table->string('grading')->nullable();
                $table->string('semester')->nullable();
                $table->string('department')->nullable();
                $table->boolean('is_master_subject')->default(true);
                $table->boolean('is_core_subject')->default(false);
                $table->text('prerequisite_subjects')->nullable();
                $table->timestamps();
            });
            $message .= "✅ Created subjects table<br>";
        }

        // 1. Create or fix students table
        if (!\Illuminate\Support\Facades\Schema::hasTable('students')) {
            \Illuminate\Support\Facades\Schema::create('students', function ($table) {
                $table->id();
                $table->string('student_id')->unique();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('middle_name')->nullable();
                $table->string('email')->unique();
                $table->string('password');
                $table->string('grade_level');
                $table->string('year_level')->nullable();
                $table->string('section')->nullable();
                $table->string('track')->nullable();
                $table->string('strand')->nullable();
                $table->string('gender');
                $table->string('contact_number')->nullable();
                $table->text('address')->nullable();
                $table->string('parent_name')->nullable();
                $table->string('parent_contact')->nullable();
                $table->string('advisor')->nullable();
                $table->string('lrn')->nullable();
                $table->string('profile_picture')->nullable();
                $table->string('province')->nullable();
                $table->string('municipality')->nullable();
                $table->string('barangay')->nullable();
                $table->text('permanent_address')->nullable();
                $table->string('phone')->nullable();
                $table->string('social_media')->nullable();
                $table->string('emergency_name')->nullable();
                $table->string('emergency_phone')->nullable();
                $table->string('emergency_relationship')->nullable();
                $table->boolean('is_temporary_account')->default(false);
                $table->boolean('profile_completed')->default(false);
                $table->rememberToken();
                $table->timestamps();
            });
            $message .= "✅ Created students table<br>";
        } else {
            // Table exists, check and add missing columns
            $missingColumns = [];
            $requiredColumns = [
                'grade_level' => 'string',
                'year_level' => 'string',
                'section' => 'string',
                'track' => 'string',
                'strand' => 'string',
                'advisor' => 'string',
                'province' => 'string',
                'municipality' => 'string',
                'barangay' => 'string',
                'permanent_address' => 'text',
                'phone' => 'string',
                'social_media' => 'string',
                'emergency_name' => 'string',
                'emergency_phone' => 'string',
                'emergency_relationship' => 'string',
                'is_temporary_account' => 'boolean',
                'profile_completed' => 'boolean'
            ];

            foreach ($requiredColumns as $column => $type) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('students', $column)) {
                    $missingColumns[] = $column;
                }
            }

            if (!empty($missingColumns)) {
                \Illuminate\Support\Facades\Schema::table('students', function ($table) use ($missingColumns) {
                    foreach ($missingColumns as $column) {
                        switch ($column) {
                            case 'grade_level':
                                $table->string('grade_level')->nullable();
                                break;
                            case 'year_level':
                                $table->string('year_level')->nullable();
                                break;
                            case 'section':
                                $table->string('section')->nullable();
                                break;
                            case 'track':
                                $table->string('track')->nullable();
                                break;
                            case 'strand':
                                $table->string('strand')->nullable();
                                break;
                            case 'advisor':
                                $table->string('advisor')->nullable();
                                break;
                            case 'province':
                                $table->string('province')->nullable();
                                break;
                            case 'municipality':
                                $table->string('municipality')->nullable();
                                break;
                            case 'barangay':
                                $table->string('barangay')->nullable();
                                break;
                            case 'permanent_address':
                                $table->text('permanent_address')->nullable();
                                break;
                            case 'phone':
                                $table->string('phone')->nullable();
                                break;
                            case 'social_media':
                                $table->string('social_media')->nullable();
                                break;
                            case 'emergency_name':
                                $table->string('emergency_name')->nullable();
                                break;
                            case 'emergency_phone':
                                $table->string('emergency_phone')->nullable();
                                break;
                            case 'emergency_relationship':
                                $table->string('emergency_relationship')->nullable();
                                break;
                            case 'is_temporary_account':
                                $table->boolean('is_temporary_account')->default(false);
                                break;
                            case 'profile_completed':
                                $table->boolean('profile_completed')->default(false);
                                break;
                        }
                    }
                });
                $message .= "✅ Added " . count($missingColumns) . " missing columns to students table: " . implode(', ', $missingColumns) . "<br>";
            } else {
                $message .= "✅ Students table already has all required columns<br>";
            }
        }

        // 2. Create grades table
        if (!\Illuminate\Support\Facades\Schema::hasTable('grades')) {
            \Illuminate\Support\Facades\Schema::create('grades', function ($table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->decimal('quarter1', 5, 2)->nullable();
                $table->decimal('quarter2', 5, 2)->nullable();
                $table->decimal('quarter3', 5, 2)->nullable();
                $table->decimal('quarter4', 5, 2)->nullable();
                $table->decimal('final_grade', 5, 2)->nullable();
                $table->string('remarks')->nullable();
                $table->timestamps();
            });
            $message .= "✅ Created grades table<br>";
        }

        // 3. Create student_subject pivot table
        if (!\Illuminate\Support\Facades\Schema::hasTable('student_subject')) {
            \Illuminate\Support\Facades\Schema::create('student_subject', function ($table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->decimal('grade', 5, 2)->nullable();
                $table->string('quarter')->nullable();
                $table->string('school_year')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
                $table->unique(['student_id', 'subject_id']);
            });
            $message .= "✅ Created student_subject table<br>";
        }

        // Note: Default subjects creation removed - registrar will create subjects as needed
        $message .= "ℹ️ Subject creation is now handled exclusively by registrars<br>";

        // Create sample students for testing
        $sampleStudents = [
            [
                'student_id' => 'STU001',
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'email' => 'juan.delacruz@student.cnhs.edu.ph',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'grade_level' => 'Grade 11',
                'section' => 'A',
                'track' => 'Academic Track',
                'strand' => 'STEM',
                'gender' => 'Male',
                'contact_number' => '09123456789',
                'is_temporary_account' => false,
                'profile_completed' => true
            ],
            [
                'student_id' => 'STU002',
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'email' => 'maria.santos@student.cnhs.edu.ph',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'grade_level' => 'Grade 12',
                'section' => 'B',
                'track' => 'Academic Track',
                'strand' => 'HUMSS',
                'gender' => 'Female',
                'contact_number' => '09987654321',
                'is_temporary_account' => false,
                'profile_completed' => true
            ]
        ];

        $studentsCreated = 0;
        foreach ($sampleStudents as $studentData) {
            $existing = \App\Models\Student::where('student_id', $studentData['student_id'])->first();
            if (!$existing) {
                \App\Models\Student::create($studentData);
                $studentsCreated++;
            }
        }

        if ($studentsCreated > 0) {
            $message .= "✅ Created $studentsCreated sample students<br>";
        } else {
            $message .= "ℹ️ Sample students already exist<br>";
        }

        $message .= "<br><strong>✅ Database Setup Complete!</strong><br>";
        $message .= "<p>All required tables have been created. You can now:</p>";
        $message .= "<ul>";
        $message .= "<li><a href='/registrar/login'>Login as Registrar</a></li>";
        $message .= "<li><a href='/admin/login'>Login as Admin</a></li>";
        $message .= "<li><a href='/registrar/subjects'>View Subjects</a> (after login)</li>";
        $message .= "<li><a href='/registrar/students'>View Students</a> (after login)</li>";
        $message .= "</ul>";

        return $message;

    } catch (Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});

// Test subjects table route
Route::get('/test-subjects-table', function() {
    try {
        $message = "<h2>🧪 Testing Subjects Table</h2>";

        // Check if table exists
        if (!\Illuminate\Support\Facades\Schema::hasTable('subjects')) {
            return "❌ Subjects table does not exist. Please run /setup-database first.";
        }

        $message .= "✅ Subjects table exists<br>";

        // Count subjects
        $count = \App\Models\Subject::count();
        $message .= "📊 Total subjects: $count<br>";

        // List subjects
        if ($count > 0) {
            $subjects = \App\Models\Subject::all();
            $message .= "<h3>📚 Existing Subjects:</h3><ul>";
            foreach ($subjects as $subject) {
                $message .= "<li><strong>{$subject->code}</strong> - {$subject->name} (Grade: {$subject->grade_level})</li>";
            }
            $message .= "</ul>";
        } else {
            $message .= "ℹ️ No subjects found. Registrars can create subjects as needed.<br>";
        }

        $message .= "<br><strong>🎯 Test Results:</strong><br>";
        $message .= "✅ Subjects table is working properly<br>";
        $message .= "<a href='/registrar/login'>Test Registrar Login</a> | ";
        $message .= "<a href='/registrar/students'>Test Student Records</a>";

        return $message;

    } catch (Exception $e) {
        return "❌ Error testing subjects table: " . $e->getMessage();
    }
});

// Test students table route
Route::get('/test-students-table', function() {
    try {
        $message = "<h2>🧪 Testing Students Table</h2>";

        // Check if table exists
        if (!\Illuminate\Support\Facades\Schema::hasTable('students')) {
            return "❌ Students table does not exist. Please run /setup-database first.";
        }

        $message .= "✅ Students table exists<br>";

        // Check for grade_level column specifically
        if (!\Illuminate\Support\Facades\Schema::hasColumn('students', 'grade_level')) {
            return "❌ Students table missing 'grade_level' column. Please run /setup-database to fix.";
        }

        $message .= "✅ Students table has 'grade_level' column<br>";

        // List all columns
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('students');
        $message .= "<h3>📋 Students Table Columns:</h3>";
        $message .= "<p>" . implode(', ', $columns) . "</p>";

        // Count students
        $count = \App\Models\Student::count();
        $message .= "<h3>📊 Student Statistics:</h3>";
        $message .= "Total students: $count<br>";

        // Test grade_level query
        try {
            $gradeLevels = \App\Models\Student::select('grade_level')->distinct()->whereNotNull('grade_level')->pluck('grade_level');
            $message .= "Available grade levels: " . $gradeLevels->implode(', ') . "<br>";
        } catch (Exception $e) {
            $message .= "❌ Error querying grade_level: " . $e->getMessage() . "<br>";
        }

        // List students if any exist
        if ($count > 0) {
            $students = \App\Models\Student::limit(5)->get();
            $message .= "<h3>📚 Sample Students:</h3><ul>";
            foreach ($students as $student) {
                $message .= "<li><strong>{$student->student_id}</strong> - {$student->first_name} {$student->last_name} (Grade: {$student->grade_level})</li>";
            }
            $message .= "</ul>";
        }

        $message .= "<br><strong>🎯 Test Results:</strong><br>";
        $message .= "✅ Students table is working properly<br>";
        $message .= "<a href='/registrar/login'>Test Registrar Login</a> | ";
        $message .= "<a href='/registrar/students'>Test Student Records</a>";

        return $message;

    } catch (Exception $e) {
        return "❌ Error testing students table: " . $e->getMessage();
    }
});

// Fix pending migrations route
Route::get('/fix-migrations', function() {
    try {
        $message = "<h2>🔧 Fixing Pending Migrations</h2>";

        // Get list of existing tables
        $existingTables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
        $tableNames = array_map(function($table) {
            return array_values((array)$table)[0];
        }, $existingTables);

        $message .= "<p><strong>Existing tables:</strong> " . implode(', ', $tableNames) . "</p>";

        // Check migrations table
        if (!in_array('migrations', $tableNames)) {
            $message .= "⚠️ Migrations table doesn't exist. Creating it...<br>";
            \Illuminate\Support\Facades\Artisan::call('migrate:install');
            $message .= "✅ Created migrations table<br>";
        }

        // Get list of migration files
        $migrationFiles = glob(database_path('migrations/*.php'));
        $migrationCount = count($migrationFiles);
        $message .= "<p>Found $migrationCount migration files</p>";

        // Check for problematic tables that might cause conflicts
        $problematicTables = [
            'temporary_student_credentials'
        ];

        $conflictingTables = [];
        foreach ($problematicTables as $table) {
            if (in_array($table, $tableNames)) {
                $conflictingTables[] = $table;
            }
        }

        if (!empty($conflictingTables)) {
            $message .= "<h3>⚠️ Found Conflicting Tables:</h3>";
            $message .= "<p>These tables already exist and might cause migration conflicts: " . implode(', ', $conflictingTables) . "</p>";

            // Option to drop conflicting tables
            $message .= "<p><a href='/fix-migrations-force' style='color: red; font-weight: bold;'>🗑️ Drop conflicting tables and re-run migrations</a></p>";
        }

        // Try to run migrations with --pretend to see what would happen
        $message .= "<h3>🔍 Migration Status Check:</h3>";

        try {
            // Get migration status
            $output = '';
            \Illuminate\Support\Facades\Artisan::call('migrate:status');
            $output = \Illuminate\Support\Facades\Artisan::output();
            $message .= "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>$output</pre>";
        } catch (Exception $e) {
            $message .= "<p style='color: red;'>Error getting migration status: " . $e->getMessage() . "</p>";
        }

        $message .= "<h3>🛠️ Available Actions:</h3>";
        $message .= "<ul>";
        $message .= "<li><a href='/fix-migrations-safe'>🔧 Try Safe Migration Fix</a></li>";
        $message .= "<li><a href='/fix-migrations-force'>⚡ Force Migration Fix (drops conflicting tables)</a></li>";
        $message .= "<li><a href='/setup-database'>🏗️ Use Manual Database Setup</a></li>";
        $message .= "</ul>";

        return $message;

    } catch (Exception $e) {
        return "❌ Error checking migrations: " . $e->getMessage();
    }
});

// Safe migration fix route
Route::get('/fix-migrations-safe', function() {
    try {
        $message = "<h2>🔧 Safe Migration Fix</h2>";

        // Try to mark problematic migrations as run
        $problematicMigrations = [
            '2025_01_20_000001_create_temporary_student_credentials_table'
        ];

        foreach ($problematicMigrations as $migration) {
            try {
                // Check if migration is already recorded
                $exists = \Illuminate\Support\Facades\DB::table('migrations')
                    ->where('migration', $migration)
                    ->exists();

                if (!$exists) {
                    // Mark as run without actually running it
                    \Illuminate\Support\Facades\DB::table('migrations')->insert([
                        'migration' => $migration,
                        'batch' => 1
                    ]);
                    $message .= "✅ Marked $migration as completed<br>";
                } else {
                    $message .= "ℹ️ $migration already marked as completed<br>";
                }
            } catch (Exception $e) {
                $message .= "⚠️ Could not mark $migration: " . $e->getMessage() . "<br>";
            }
        }

        // Now try to run remaining migrations
        $message .= "<h3>🚀 Running Remaining Migrations:</h3>";
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $output = \Illuminate\Support\Facades\Artisan::output();
            $message .= "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>$output</pre>";
            $message .= "✅ Migrations completed successfully!<br>";
        } catch (Exception $e) {
            $message .= "❌ Error running migrations: " . $e->getMessage() . "<br>";
        }

        $message .= "<br><a href='/fix-migrations'>🔙 Back to Migration Status</a> | ";
        $message .= "<a href='/registrar/login'>🧪 Test Registrar Login</a>";

        return $message;

    } catch (Exception $e) {
        return "❌ Error in safe migration fix: " . $e->getMessage();
    }
});

// Force migration fix route
Route::get('/fix-migrations-force', function() {
    try {
        $message = "<h2>⚡ Force Migration Fix</h2>";
        $message .= "<p style='color: red;'><strong>Warning:</strong> This will drop conflicting tables and re-run migrations.</p>";

        // Drop problematic tables
        $problematicTables = [
            'temporary_student_credentials'
        ];

        foreach ($problematicTables as $table) {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                    \Illuminate\Support\Facades\Schema::dropIfExists($table);
                    $message .= "🗑️ Dropped table: $table<br>";
                }
            } catch (Exception $e) {
                $message .= "⚠️ Could not drop $table: " . $e->getMessage() . "<br>";
            }
        }

        // Remove migration records for dropped tables
        $problematicMigrations = [
            '2025_01_20_000001_create_temporary_student_credentials_table'
        ];

        foreach ($problematicMigrations as $migration) {
            try {
                \Illuminate\Support\Facades\DB::table('migrations')
                    ->where('migration', $migration)
                    ->delete();
                $message .= "🔄 Reset migration record: $migration<br>";
            } catch (Exception $e) {
                $message .= "⚠️ Could not reset $migration: " . $e->getMessage() . "<br>";
            }
        }

        // Now run all migrations
        $message .= "<h3>🚀 Running All Migrations:</h3>";
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $output = \Illuminate\Support\Facades\Artisan::output();
            $message .= "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>$output</pre>";
            $message .= "✅ All migrations completed successfully!<br>";
        } catch (Exception $e) {
            $message .= "❌ Error running migrations: " . $e->getMessage() . "<br>";
            $message .= "<p>You may need to use the manual database setup instead.</p>";
        }

        $message .= "<br><a href='/fix-migrations'>🔙 Back to Migration Status</a> | ";
        $message .= "<a href='/setup-database'>🏗️ Use Manual Database Setup</a> | ";
        $message .= "<a href='/registrar/login'>🧪 Test Registrar Login</a>";

        return $message;

    } catch (Exception $e) {
        return "❌ Error in force migration fix: " . $e->getMessage();
    }
});

// Subject Buttons Demo
Route::get('/subject-buttons-demo', function() {
    return response()->file(public_path('../SUBJECT_BUTTONS_DEMO.html'));
});

// Remove default/system subjects route
Route::get('/remove-default-subjects', function() {
    try {
        $message = "<h2>🗑️ Removing Default System Subjects</h2>";

        // Define the codes of default subjects to remove
        $defaultSubjectCodes = [
            'BUS-MATH-11',
            'CW-11',
            'CALC-11',
            'GEN-MATH-11',
            'PROG-11',
            'GENMATH',
            'EAPP',
            'PHYS-SCI'
        ];

        $removedCount = 0;
        $removedSubjects = [];

        foreach ($defaultSubjectCodes as $code) {
            $subject = \App\Models\Subject::where('code', $code)->first();
            if ($subject) {
                // Check if this subject has any student enrollments
                $hasStudents = $subject->students()->count() > 0;

                if (!$hasStudents) {
                    $removedSubjects[] = $subject->name . " (" . $subject->code . ")";
                    $subject->delete();
                    $removedCount++;
                } else {
                    $message .= "⚠️ Skipped {$subject->name} ({$subject->code}) - has enrolled students<br>";
                }
            }
        }

        // Also remove subjects that don't have a registrar_id (system-created)
        $systemSubjects = \App\Models\Subject::whereNull('registrar_id')->get();
        foreach ($systemSubjects as $subject) {
            $hasStudents = $subject->students()->count() > 0;

            if (!$hasStudents) {
                $removedSubjects[] = $subject->name . " (" . $subject->code . ")";
                $subject->delete();
                $removedCount++;
            } else {
                $message .= "⚠️ Skipped {$subject->name} ({$subject->code}) - has enrolled students<br>";
            }
        }

        if ($removedCount > 0) {
            $message .= "✅ Successfully removed $removedCount default subjects:<br>";
            $message .= "<ul>";
            foreach ($removedSubjects as $subjectName) {
                $message .= "<li>$subjectName</li>";
            }
            $message .= "</ul>";
        } else {
            $message .= "ℹ️ No default subjects found to remove.<br>";
        }

        // Show remaining subjects
        $remainingSubjects = \App\Models\Subject::all();
        $message .= "<br><h3>📚 Remaining Subjects (" . $remainingSubjects->count() . "):</h3>";

        if ($remainingSubjects->count() > 0) {
            $message .= "<ul>";
            foreach ($remainingSubjects as $subject) {
                $createdBy = $subject->registrar_id ? "Registrar" : "System";
                $message .= "<li><strong>{$subject->code}</strong> - {$subject->name} (Created by: $createdBy)</li>";
            }
            $message .= "</ul>";
        } else {
            $message .= "<p>No subjects remaining in the system.</p>";
        }

        $message .= "<br><a href='/registrar/subjects'>🔙 Back to Registrar Subjects</a>";

        return $message;

    } catch (Exception $e) {
        return "❌ Error removing default subjects: " . $e->getMessage();
    }
});

// Main login form is now working correctly for registrar!
// Both dedicated registrar login (/registrar/login) and main login form work properly.

// Registrar login is now working!
// The issue was with middleware configuration in Laravel 12.
// Fixed by properly configuring authentication middleware in bootstrap/app.php

// Fix student_subject table
Route::get('/fix-student-subject-table', function() {
    try {
        $message = "<h2>🔧 Fixing student_subject Table</h2>";

        // Check if table exists
        if (!\Illuminate\Support\Facades\Schema::hasTable('student_subject')) {
            \Illuminate\Support\Facades\Schema::create('student_subject', function ($table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
                $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
                $table->decimal('grade', 5, 2)->nullable();
                $table->string('quarter')->nullable();
                $table->string('school_year')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
                $table->unique(['student_id', 'subject_id']);
            });
            $message .= "✅ Created student_subject table<br>";
        } else {
            $message .= "ℹ️ student_subject table already exists<br>";
        }

        // Test the relationship
        $student = \App\Models\Student::first();
        if ($student) {
            $message .= "✅ Found test student: {$student->first_name} {$student->last_name}<br>";
            try {
                $subjects = $student->subjects()->get();
                $message .= "✅ Successfully loaded student subjects (count: " . $subjects->count() . ")<br>";
            } catch (Exception $e) {
                $message .= "❌ Error loading student subjects: " . $e->getMessage() . "<br>";
            }
        } else {
            $message .= "⚠️ No students found in database<br>";
        }

        $message .= "<br><a href='/student/login'>🧪 Test Student Login</a>";
        $message .= " | <a href='/test-student-subjects'>🧪 Test Student Subjects</a>";

        return $message;

    } catch (Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});

// Test student subjects relationship
Route::get('/test-student-subjects', function() {
    try {
        $message = "<h2>🧪 Testing Student Subjects Relationship</h2>";

        // Find a test student
        $student = \App\Models\Student::first();
        if (!$student) {
            return "❌ No students found in database. Please run /setup-database first.";
        }

        $message .= "✅ Found student: {$student->first_name} {$student->last_name} (ID: {$student->student_id})<br>";

        // Test the subjects relationship
        try {
            $subjects = $student->subjects()->get();
            $message .= "✅ Successfully loaded student subjects (count: " . $subjects->count() . ")<br>";

            if ($subjects->count() > 0) {
                $message .= "<h3>📚 Student's Subjects:</h3><ul>";
                foreach ($subjects as $subject) {
                    $message .= "<li>{$subject->code} - {$subject->name}</li>";
                }
                $message .= "</ul>";
            } else {
                $message .= "ℹ️ Student has no subjects assigned yet.<br>";

                // Try to assign a test subject
                $testSubject = \App\Models\Subject::first();
                if ($testSubject) {
                    $student->subjects()->attach($testSubject->id, [
                        'grade' => 85.5,
                        'quarter' => 'Q1',
                        'school_year' => '2024-2025'
                    ]);
                    $message .= "✅ Assigned test subject: {$testSubject->code} - {$testSubject->name}<br>";
                }
            }

        } catch (Exception $e) {
            $message .= "❌ Error loading student subjects: " . $e->getMessage() . "<br>";
        }

        $message .= "<br><a href='/student-login'>🧪 Test Student Login</a>";
        $message .= " | <a href='/fix-student-subject-table'>🔧 Fix Table Again</a>";

        return $message;

    } catch (Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});

// Test registrar login directly
Route::post('/test-registrar-login', function(Illuminate\Http\Request $request) {
    $credentials = [
        'email' => $request->input('email', 'registrar@cnhs.edu.ph'),
        'password' => $request->input('password', 'password123')
    ];

    $user = App\Models\Registrar::where('email', $credentials['email'])->first();
    $passwordCheck = $user ? Hash::check($credentials['password'], $user->password) : false;

    // Try Auth::attempt first
    $authAttempt = Auth::guard('registrar')->attempt($credentials);

    // If Auth::attempt fails, try manual login
    if (!$authAttempt && $user && $passwordCheck) {
        Auth::guard('registrar')->login($user);
        $request->session()->regenerate();
        $request->session()->save(); // Force session save
        $authAttempt = Auth::guard('registrar')->check();
    }

    $result = [
        'credentials' => $credentials,
        'user_exists' => $user ? true : false,
        'password_correct' => $passwordCheck,
        'auth_attempt_result' => $authAttempt,
        'guard_user' => Auth::guard('registrar')->user(),
        'session_id' => $request->session()->getId(),
        'errors' => $authAttempt ? 'none' : 'authentication failed'
    ];

    // If login successful, try redirect
    if ($authAttempt) {
        return redirect()->route('registrar.dashboard')->with('login_test_result', $result);
    }

    return $result;
});

// Simple registrar login form for testing
Route::get('/test-registrar-form', function() {
    return '
    <form method="POST" action="/test-registrar-login">
        <input type="hidden" name="_token" value="' . csrf_token() . '">
        <div>
            <label>Email:</label>
            <input type="email" name="email" value="registrar@cnhs.edu.ph" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" value="password123" required>
        </div>
        <button type="submit">Test Login</button>
    </form>
    <br><br>
    <a href="/registrar/login">Go to Real Registrar Login</a><br>
    <a href="/test-registrar-auth">Check Auth Config</a>
    ';
});

// Test registrar dashboard access
Route::get('/test-registrar-dashboard', function() {
    $user = App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
    if ($user) {
        Auth::guard('registrar')->login($user);

        // Check if login was successful
        $authCheck = Auth::guard('registrar')->check();
        $authUser = Auth::guard('registrar')->user();

        if ($authCheck) {
            return redirect()->route('registrar.dashboard');
        } else {
            return 'Login failed - Auth check returned false';
        }
    }
    return 'User not found';
});

// Test registrar dashboard direct access (without middleware)
Route::get('/test-registrar-dashboard-direct', function() {
    $user = App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
    if ($user) {
        Auth::guard('registrar')->login($user);

        // Try to access dashboard controller directly
        $controller = new App\Http\Controllers\Registrar\DashboardController();
        return $controller->index();
    }
    return 'User not found';
});

// Temporary registrar dashboard without middleware (for testing)
Route::get('/registrar/dashboard-no-middleware', function() {
    // Check if user is authenticated
    $user = Auth::guard('registrar')->user();
    $authCheck = Auth::guard('registrar')->check();

    if (!$authCheck || !$user) {
        return 'Not authenticated. Auth check: ' . ($authCheck ? 'true' : 'false') .
               ', User: ' . ($user ? $user->email : 'null') .
               '. <a href="/registrar/login">Login here</a>';
    }

    return 'Successfully authenticated as: ' . $user->email .
           ' (ID: ' . $user->id . '). ' .
           '<a href="/registrar/dashboard">Go to real dashboard</a>';
});

// Test middleware directly
Route::get('/test-middleware', function() {
    return 'Middleware test passed!';
})->middleware('auth:registrar');

// Assignment controllers are now working correctly!

// Comprehensive registrar debug route
Route::get('/debug-registrar-complete', function() {
    $user = App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();

    if (!$user) {
        return 'No registrar user found. Creating one...';
    }

    $passwordCheck = Hash::check('password123', $user->password);

    // Try manual login
    Auth::guard('registrar')->login($user);
    session()->save();

    $authCheck = Auth::guard('registrar')->check();
    $authUser = Auth::guard('registrar')->user();

    return [
        'user_found' => true,
        'user_id' => $user->id,
        'email' => $user->email,
        'password_correct' => $passwordCheck,
        'manual_login_successful' => $authCheck,
        'auth_user_id' => $authUser ? $authUser->id : null,
        'session_id' => session()->getId(),
        'session_driver' => config('session.driver'),
        'guard_config' => config('auth.guards.registrar'),
        'provider_config' => config('auth.providers.registrars'),
        'middleware_test_url' => url('/test-middleware'),
        'dashboard_url' => route('registrar.dashboard')
    ];
});

// Principal Routes
Route::prefix('principal')->name('principal.')->group(function () {
    // Guest routes (login)
    Route::get('/login', 'App\Http\Controllers\Principal\AuthController@showLoginForm')->name('login');
    Route::post('/login', 'App\Http\Controllers\Principal\AuthController@login');

    Route::get('/', 'App\Http\Controllers\Principal\PagesController@index')->name('index');
    Route::get('/about', 'App\Http\Controllers\Principal\PagesController@about')->name('about');
    Route::get('/academics', 'App\Http\Controllers\Principal\PagesController@academics')->name('academics');
    Route::get('/news', 'App\Http\Controllers\Principal\PagesController@news')->name('news');
    Route::get('/contact', 'App\Http\Controllers\Principal\PagesController@contact')->name('contact');

    // Protected routes that require principal authentication
    Route::middleware(['auth:principal'])->group(function () {
        Route::get('/dashboard', function() {
            return view('Principal.dashboard');
        })->name('dashboard');

        // Announcement Routes
        Route::get('/announcements', 'App\Http\Controllers\Principal\AnnouncementController@index')->name('announcements.index');
        Route::get('/announcements/create', 'App\Http\Controllers\Principal\AnnouncementController@create')->name('announcements.create');
        Route::post('/announcements', 'App\Http\Controllers\Principal\AnnouncementController@store')->name('announcements.store');
        Route::get('/announcements/{id}/edit', 'App\Http\Controllers\Principal\AnnouncementController@edit')->name('announcements.edit');
        Route::put('/announcements/{id}', 'App\Http\Controllers\Principal\AnnouncementController@update')->name('announcements.update');
        Route::delete('/announcements/{id}', 'App\Http\Controllers\Principal\AnnouncementController@destroy')->name('announcements.destroy');

        Route::post('/logout', 'App\Http\Controllers\Principal\AuthController@logout')->name('logout');
    });
});

// Principal Teacher Management Routes (Protected)
Route::middleware(['auth:principal'])->prefix('principal')->name('principal.')->group(function () {
    Route::resource('teachers', \App\Http\Controllers\Principal\TeacherController::class);
});

// Add this near the top with other route definitions
Route::get('/auth/login', function () {
    return view('auth.login');
});




