<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Registrar\AuthController as RegistrarAuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Subject Management Module Demo
Route::get('/subject-management-demo', function () {
    return view('subject-management-module-demo');
})->name('subject-management.demo');

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

// Create test subjects for registrar
Route::post('/create-test-subjects-for-registrar', function () {
    try {
        $testSubjects = [
            [
                'name' => 'Test Mathematics',
                'code' => 'MATH' . rand(100, 999),
                'grade_level' => 'Grade 11',
                'units' => 3,
                'strand' => 'HUMSS',
                'track' => 'Academic Track',
                'description' => 'Test subject created for registrar visibility testing'
            ],
            [
                'name' => 'Test English',
                'code' => 'ENG' . rand(100, 999),
                'grade_level' => 'Grade 12',
                'units' => 3,
                'strand' => 'HUMSS',
                'track' => 'Academic Track',
                'description' => 'Test subject created for registrar visibility testing'
            ],
            [
                'name' => 'Test Science',
                'code' => 'SCI' . rand(100, 999),
                'grade_level' => 'Grade 11',
                'units' => 4,
                'strand' => 'AFA',
                'track' => 'TVL Track',
                'description' => 'Test subject created for registrar visibility testing'
            ]
        ];

        foreach ($testSubjects as $subjectData) {
            \App\Models\Subject::create($subjectData);
        }

        return redirect()->back()->with('test_subjects_created', 'Successfully created ' . count($testSubjects) . ' test subjects for registrar testing!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error creating test subjects: ' . $e->getMessage());
    }
});

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
    Route::get('/teacher/grades/{student}/{subject}/edit', [App\Http\Controllers\Teacher\GradeController::class, 'editGrade'])->name('teacher.grades.edit');
    Route::get('/teacher/profile', [App\Http\Controllers\Teacher\ProfileController::class, 'index'])->name('teacher.profile');
    Route::post('/teacher/profile', [App\Http\Controllers\Teacher\ProfileController::class, 'update'])->name('teacher.update-profile');
    Route::post('/teacher/profile/upload', [App\Http\Controllers\Teacher\ProfileController::class, 'uploadProfilePicture'])->name('teacher.profile.upload');

    // AJAX routes for dynamic loading
    Route::get('/teacher/api/subjects/{gradeLevel}', [App\Http\Controllers\Teacher\ClassListController::class, 'getSubjects'])->name('teacher.api.subjects');
    Route::get('/teacher/api/students/{gradeLevel}/{subjectId}', [App\Http\Controllers\Teacher\ClassListController::class, 'getStudents'])->name('teacher.api.students');
    Route::get('/teacher/get-students', [App\Http\Controllers\Teacher\ClassListController::class, 'getStudentsForFilters'])->name('teacher.get-students');
});

// Student Routes
Route::middleware(['auth:student'])->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/student/announcements', [App\Http\Controllers\Student\AnnouncementsController::class, 'index'])->name('student.announcements');
    Route::get('/student/profile', [App\Http\Controllers\Student\ProfileController::class, 'index'])->name('student.profile');
    Route::put('/student/profile', [ProfileController::class, 'update'])->name('student.profile.update');
    Route::post('/student/profile/upload', [App\Http\Controllers\Student\ProfileController::class, 'uploadProfilePicture'])->name('student.profile.upload');
    Route::get('/student/grades', [App\Http\Controllers\Student\GradeController::class, 'index'])->name('student.grades');
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

        // Student Management
        Route::get('users/students/create', [App\Http\Controllers\Admin\UserController::class, 'createStudent'])->name('users.students.create');
        Route::post('users/students', [App\Http\Controllers\Admin\UserController::class, 'storeStudent'])->name('users.students.store');
        Route::get('users/students/{student}', [App\Http\Controllers\Admin\UserController::class, 'showStudent'])->name('users.students.show');
        Route::get('users/students/{student}/edit', [App\Http\Controllers\Admin\UserController::class, 'editStudent'])->name('users.students.edit');
        Route::put('users/students/{student}', [App\Http\Controllers\Admin\UserController::class, 'updateStudent'])->name('users.students.update');
        Route::delete('users/students/{student}', [App\Http\Controllers\Admin\UserController::class, 'destroyStudent'])->name('users.students.destroy');
        // Grades Routes
        Route::get('grades', [App\Http\Controllers\Admin\GradeController::class, 'index'])->name('grades');
        Route::get('grades/create', [App\Http\Controllers\Admin\GradeController::class, 'create'])->name('grades.create');
        Route::post('grades', [App\Http\Controllers\Admin\GradeController::class, 'store'])->name('grades.store');
        Route::get('grades/{grade}/edit', [App\Http\Controllers\Admin\GradeController::class, 'edit'])->name('grades.edit');

        // Subjects Management Routes
        Route::get('subjects', [App\Http\Controllers\Admin\SubjectController::class, 'index'])->name('subjects');
        Route::get('subjects/create', [App\Http\Controllers\Admin\SubjectController::class, 'create'])->name('subjects.create');
        Route::post('subjects', [App\Http\Controllers\Admin\SubjectController::class, 'store'])->name('subjects.store');
        Route::get('subjects/{subject}', [App\Http\Controllers\Admin\SubjectController::class, 'show'])->name('subjects.show');
        Route::get('subjects/{subject}/edit', [App\Http\Controllers\Admin\SubjectController::class, 'edit'])->name('subjects.edit');
        Route::put('subjects/{subject}', [App\Http\Controllers\Admin\SubjectController::class, 'update'])->name('subjects.update');
        Route::delete('subjects/{subject}', [App\Http\Controllers\Admin\SubjectController::class, 'destroy'])->name('subjects.destroy');

        // Reports Routes
        Route::get('reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
        Route::get('reports/generate', [App\Http\Controllers\Admin\ReportController::class, 'generate'])->name('reports.generate');
        Route::get('reports/download', [App\Http\Controllers\Admin\ReportController::class, 'download'])->name('reports.download');
    });
});

// Registrar Routes
Route::prefix('registrar')->group(function () {
    Route::get('/login', [RegistrarAuthController::class, 'showLoginForm'])->name('registrar.login');
    Route::post('/login', [RegistrarAuthController::class, 'login'])->name('registrar.login.post');
    Route::post('/logout', [RegistrarAuthController::class, 'logout'])->name('registrar.logout');

    Route::middleware(['auth:registrar'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Registrar\DashboardController::class, 'index'])->name('registrar.dashboard');

        // Subject Management Routes
        Route::get('/subjects', [App\Http\Controllers\Registrar\SubjectController::class, 'index'])->name('registrar.subjects');
        Route::get('/subjects/create', [App\Http\Controllers\Registrar\SubjectController::class, 'create'])->name('registrar.subjects.create');
        Route::post('/subjects', [App\Http\Controllers\Registrar\SubjectController::class, 'store'])->name('registrar.subjects.store');
        Route::get('/subjects/{subject}', [App\Http\Controllers\Registrar\SubjectController::class, 'show'])->name('registrar.subjects.show');
        Route::get('/subjects/{subject}/edit', [App\Http\Controllers\Registrar\SubjectController::class, 'edit'])->name('registrar.subjects.edit');
        Route::put('/subjects/{subject}', [App\Http\Controllers\Registrar\SubjectController::class, 'update'])->name('registrar.subjects.update');
        Route::delete('/subjects/{subject}', [App\Http\Controllers\Registrar\SubjectController::class, 'destroy'])->name('registrar.subjects.destroy');
        Route::get('/subjects-fixed', [App\Http\Controllers\Registrar\SubjectController::class, 'subjectsFixed'])->name('registrar.subjects.fixed');
        Route::get('/assign-subjects/{studentId}', [App\Http\Controllers\Registrar\SubjectController::class, 'assignSubjects'])->name('registrar.assign-subjects');
        Route::post('/assign-subjects/{studentId}', [App\Http\Controllers\Registrar\SubjectController::class, 'storeAssignedSubjects'])->name('registrar.store-assigned-subjects');

        // Subject Offerings Management Routes
        Route::get('/subject-offerings', [App\Http\Controllers\Registrar\SubjectOfferingController::class, 'index'])->name('registrar.subject-offerings.index');
        Route::get('/subject-offerings/create', [App\Http\Controllers\Registrar\SubjectOfferingController::class, 'create'])->name('registrar.subject-offerings.create');
        Route::post('/subject-offerings', [App\Http\Controllers\Registrar\SubjectOfferingController::class, 'store'])->name('registrar.subject-offerings.store');
        Route::get('/subject-offerings/{subjectOffering}', [App\Http\Controllers\Registrar\SubjectOfferingController::class, 'show'])->name('registrar.subject-offerings.show');
        Route::get('/subject-offerings/{subjectOffering}/edit', [App\Http\Controllers\Registrar\SubjectOfferingController::class, 'edit'])->name('registrar.subject-offerings.edit');
        Route::put('/subject-offerings/{subjectOffering}', [App\Http\Controllers\Registrar\SubjectOfferingController::class, 'update'])->name('registrar.subject-offerings.update');
        Route::delete('/subject-offerings/{subjectOffering}', [App\Http\Controllers\Registrar\SubjectOfferingController::class, 'destroy'])->name('registrar.subject-offerings.destroy');

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
    });
});


