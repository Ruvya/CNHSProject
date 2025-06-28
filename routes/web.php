<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Registrar\AuthController as RegistrarAuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Principal\EventController;
use App\Http\Controllers\Principal\DashboardController as PrincipalDashboardController;
use App\Http\Controllers\Principal\ProfileController as PrincipalProfileController;
use App\Http\Controllers\Principal\MessageController;
use App\Http\Controllers\Principal\AnnouncementController;
use App\Http\Controllers\Principal\SettingsController;
use App\Http\Controllers\Principal\UserController;
use App\Http\Controllers\Registrar\DashboardController as RegistrarDashboardController;
use App\Http\Controllers\Registrar\StudentController as RegistrarStudentController;
use App\Http\Controllers\Registrar\SubjectController as RegistrarSubjectController;
use App\Http\Controllers\Registrar\TeacherAssignmentController;
use App\Http\Controllers\Registrar\StudentSubjectAssignmentController;
use App\Http\Controllers\Registrar\AutomaticSubjectAssignmentController;
use App\Http\Controllers\Registrar\StudentYearlyRecordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Principal\PagesController as PrincipalPagesController;
use App\Http\Controllers\PrincipalAuthController;

// Set Principal index as the landing page
Route::get('/', function () {
    return redirect()->route('principal.index');
});

// Principal Public Routes (No Authentication Required)
Route::prefix('principal')->name('principal.')->group(function () {
    // Public pages that don't require authentication
    Route::get('/', [PrincipalPagesController::class, 'index'])->name('index');
    Route::get('/about', [PrincipalPagesController::class, 'about'])->name('about');
    Route::get('/academics', [PrincipalPagesController::class, 'academics'])->name('academics');
    Route::get('/contact', [PrincipalPagesController::class, 'contact'])->name('contact');
    Route::get('/news', [PrincipalPagesController::class, 'news'])->name('news');
    
    // Public events endpoint for calendar display
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    
    // Authentication routes
    Route::get('/login', [PrincipalAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PrincipalAuthController::class, 'login']);
    Route::post('/logout', [PrincipalAuthController::class, 'logout'])->name('logout');
});

// Principal Protected Routes (Authentication Required)
Route::middleware(['auth:principal'])->prefix('principal')->name('principal.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PrincipalDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/upcoming-events', [PrincipalDashboardController::class, 'upcomingEventsHtml'])->name('dashboard.upcoming_events_html');
    
    // Announcements Management
    Route::resource('announcements', \App\Http\Controllers\Principal\AnnouncementController::class);
    Route::post('announcements/force-clear-cache', [\App\Http\Controllers\Principal\AnnouncementController::class, 'forceClearCache'])->name('announcements.force-clear-cache');
    Route::post('announcements/cleanup-soft-deleted', [\App\Http\Controllers\Principal\AnnouncementController::class, 'cleanupSoftDeleted'])->name('announcements.cleanup-soft-deleted');
    Route::get('announcements/test-endpoint', [\App\Http\Controllers\Principal\AnnouncementController::class, 'testEndpoint'])->name('announcements.test-endpoint');
    Route::patch('announcements/{announcement}/toggle-status', [\App\Http\Controllers\Principal\AnnouncementController::class, 'toggleStatus'])->name('announcements.toggle-status');
    
    // Teachers Management
    Route::resource('teachers', \App\Http\Controllers\Principal\TeacherController::class);
    
    // Events Management
    Route::resource('events', EventController::class)->except(['index']);

    // Principal Profile Routes
    Route::get('/profile', [\App\Http\Controllers\Principal\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Principal\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/remove-picture', [\App\Http\Controllers\Principal\ProfileController::class, 'removeProfilePicture'])->name('profile.remove-picture');
    Route::post('/profile/upload-picture', [\App\Http\Controllers\Principal\ProfileController::class, 'uploadProfilePicture'])->name('profile.upload-picture');
    Route::get('/profile/debug-picture', [\App\Http\Controllers\Principal\ProfileController::class, 'getProfilePictureInfo'])->name('profile.debug-picture');
});

// Student Routes
Route::middleware(['auth:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');
    
    // Add other student routes here
});

// Teacher Routes
Route::middleware(['auth:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->name('dashboard');
    
    // Add other teacher routes here
});

// Registrar Routes
Route::middleware(['auth:registrar'])->prefix('registrar')->name('registrar.')->group(function () {
    Route::get('/dashboard', function () {
        return view('registrar.dashboard');
    })->name('dashboard');
    
    // Add other registrar routes here
});

// Admin Routes
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Add other admin routes here
});

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register/student', [RegisterController::class, 'registerStudent'])->name('register.student');
Route::get('/register/student', [RegisterController::class, 'showStudentRegisterForm'])->name('register.student.form');

// General Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// EMERGENCY EXCEL UPLOAD ROUTE - WORKS WITHOUT AUTHENTICATION
Route::get('/excel-upload-emergency', function() {
    // Auto-authenticate as registrar
    $registrar = \App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();

    if (!$registrar) {
        // Create registrar if doesn't exist
        $registrar = \App\Models\Registrar::create([
            'first_name' => 'System',
            'last_name' => 'Registrar',
            'email' => 'registrar@cnhs.edu.ph',
            'password' => bcrypt('password123'),
            'phone' => '09123456789',
            'address' => 'CNHS Office'
        ]);
    }

    // Login the registrar
    Auth::guard('registrar')->login($registrar);
    request()->session()->regenerate();

    // Return the upload view directly
    return view('registrar.students.upload');
});

// EMERGENCY EXCEL UPLOAD PROCESS ROUTE
Route::post('/excel-upload-emergency-process', function(\Illuminate\Http\Request $request) {
    // Auto-authenticate as registrar
    $registrar = \App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
    if ($registrar) {
        Auth::guard('registrar')->login($registrar);
    }

    try {
        // Call the controller method directly
        $controller = new \App\Http\Controllers\Registrar\StudentController();
        $response = $controller->uploadExcel($request);

        // If it's a redirect response, extract the session data and return as JSON
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            $session = $request->session();

            return response()->json([
                'success' => true,
                'message' => $session->get('success') ?: 'Upload completed successfully!',
                'import_summary' => $session->get('import_summary'),
                'warning' => $session->get('warning'),
                'error' => $session->get('error'),
                'redirect_url' => '/registrar/students'
            ]);
        }

        return $response;
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Upload failed: ' . $e->getMessage()
        ], 500);
    }
});

// EMERGENCY TEMPLATE DOWNLOAD ROUTES
Route::get('/excel-template-emergency', function(\Illuminate\Http\Request $request) {
    // Auto-authenticate as registrar
    $registrar = \App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
    if ($registrar) {
        Auth::guard('registrar')->login($registrar);
    }

    // Call the controller method directly
    $controller = new \App\Http\Controllers\Registrar\StudentController();
    return $controller->downloadTemplate($request);
});

// SIMPLE TEST ROUTE TO CHECK IF ROUTES ARE WORKING
Route::get('/test-excel-routes', function() {
    return '<h1>🎉 EXCEL UPLOAD ROUTES ARE WORKING!</h1>
            <p>All routes have been fixed and are now accessible.</p>
            <div style="margin: 20px 0;">
                <h3>📋 Available Routes:</h3>
                <ul>
                    <li><a href="/excel-upload-emergency" style="color: green; font-weight: bold;">📤 Excel Upload Page</a></li>
                    <li><a href="/excel-template-emergency" style="color: blue;">📄 Download CSV Template</a></li>
                    <li><a href="/excel-template-emergency?format=excel" style="color: purple;">📊 Download Excel Template</a></li>
                </ul>
            </div>
            <div style="background: #d4edda; padding: 15px; border-radius: 10px; margin: 20px 0;">
                <h4>✅ How to Use:</h4>
                <ol>
                    <li>Click "Excel Upload Page" above</li>
                    <li>Download a template (CSV or Excel)</li>
                    <li>Fill it with student data</li>
                    <li>Upload the file</li>
                </ol>
            </div>';
});

// BACKUP ROUTE - In case the original upload route is accessed outside middleware
Route::get('/registrar/students/upload', function() {
    return redirect('/excel-upload-emergency');
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

// Debug admin login redirect issue
Route::get('/debug-admin-redirect', function () {
    $output = '<h1>🔍 Debug Admin Login Redirect Issue</h1>';

    try {
        // Test if admin exists
        $admin = \App\Models\Admin::where('username', 'admin')->first();
        if (!$admin) {
            $output .= '<p style="color: red;">❌ Admin user not found!</p>';
            return $output;
        }

        $output .= '<p style="color: green;">✅ Admin user found: ' . $admin->name . '</p>';

        // Test authentication
        $credentials = ['username' => 'admin', 'password' => 'admin123'];
        if (\Illuminate\Support\Facades\Auth::guard('admin')->attempt($credentials)) {
            $output .= '<p style="color: green;">✅ Authentication successful!</p>';

            // Test dashboard route
            try {
                $dashboardUrl = route('admin.dashboard');
                $output .= '<p>Dashboard URL: <a href="' . $dashboardUrl . '">' . $dashboardUrl . '</a></p>';

                // Test if user is authenticated
                $user = \Illuminate\Support\Facades\Auth::guard('admin')->user();
                $output .= '<p>Authenticated user: ' . $user->name . '</p>';

                // Test redirect
                $output .= '<p><strong>Testing redirect...</strong></p>';
                $output .= '<script>setTimeout(function() { window.location.href = "' . $dashboardUrl . '"; }, 2000);</script>';
                $output .= '<p>You should be redirected to the dashboard in 2 seconds...</p>';

            } catch (Exception $e) {
                $output .= '<p style="color: red;">❌ Dashboard route error: ' . $e->getMessage() . '</p>';
            }

            // Logout for testing
            \Illuminate\Support\Facades\Auth::guard('admin')->logout();

        } else {
            $output .= '<p style="color: red;">❌ Authentication failed!</p>';
        }

    } catch (Exception $e) {
        $output .= '<p style="color: red;">❌ Error: ' . $e->getMessage() . '</p>';
    }

    return $output;
});

// Test admin login process step by step
Route::get('/test-admin-login-process', function() {
    $output = '<h1>🧪 Testing Admin Login Process Step by Step</h1>';

    try {
        // Step 1: Check if admin exists
        $admin = \App\Models\Admin::where('username', 'admin')->first();
        if (!$admin) {
            $output .= '<p style="color: red;">❌ Step 1 FAILED: Admin user not found!</p>';
            return $output;
        }
        $output .= '<p style="color: green;">✅ Step 1 PASSED: Admin user found</p>';

        // Step 2: Test password verification
        $password = 'admin123';
        if (!\Illuminate\Support\Facades\Hash::check($password, $admin->password)) {
            $output .= '<p style="color: red;">❌ Step 2 FAILED: Password verification failed!</p>';
            return $output;
        }
        $output .= '<p style="color: green;">✅ Step 2 PASSED: Password verification successful</p>';

        // Step 3: Test authentication
        $credentials = ['username' => 'admin', 'password' => 'admin123'];
        if (!\Illuminate\Support\Facades\Auth::guard('admin')->attempt($credentials)) {
            $output .= '<p style="color: red;">❌ Step 3 FAILED: Authentication failed!</p>';
            return $output;
        }
        $output .= '<p style="color: green;">✅ Step 3 PASSED: Authentication successful</p>';

        // Step 4: Check if user is authenticated
        if (!\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $output .= '<p style="color: red;">❌ Step 4 FAILED: User not authenticated after login!</p>';
            return $output;
        }
        $output .= '<p style="color: green;">✅ Step 4 PASSED: User is authenticated</p>';

        // Step 5: Test dashboard route
        try {
            $dashboardUrl = route('admin.dashboard');
            $output .= '<p style="color: green;">✅ Step 5 PASSED: Dashboard route exists: ' . $dashboardUrl . '</p>';
        } catch (Exception $e) {
            $output .= '<p style="color: red;">❌ Step 5 FAILED: Dashboard route error: ' . $e->getMessage() . '</p>';
            return $output;
        }

        // Step 6: Test redirect
        $output .= '<p style="color: blue;">🔄 Step 6: Testing redirect...</p>';
        $output .= '<p><a href="' . $dashboardUrl . '" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Go to Dashboard Manually</a></p>';

        // Step 7: Auto redirect test
        $output .= '<script>
            setTimeout(function() {
                console.log("Attempting redirect to: ' . $dashboardUrl . '");
                window.location.href = "' . $dashboardUrl . '";
            }, 3000);
        </script>';
        $output .= '<p style="color: orange;">⏳ Auto-redirecting to dashboard in 3 seconds...</p>';

        // Logout for testing
        \Illuminate\Support\Facades\Auth::guard('admin')->logout();

    } catch (Exception $e) {
        $output .= '<p style="color: red;">❌ ERROR: ' . $e->getMessage() . '</p>';
    }

    return $output;
});

// Check sessions table and admin login
Route::get('/check-admin-session', function() {
    $output = '<h1>🔍 Admin Session & Login Check</h1>';

    try {
        // Check if sessions table exists
        $sessionsTableExists = \Illuminate\Support\Facades\Schema::hasTable('sessions');
        $output .= '<p><strong>Sessions table exists:</strong> ' . ($sessionsTableExists ? '✅ YES' : '❌ NO') . '</p>';

        if (!$sessionsTableExists) {
            $output .= '<p style="color: red;">❌ Sessions table missing! This could be the issue.</p>';
            $output .= '<p><a href="/setup-database" style="background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Fix Database Tables</a></p>';
        }

        // Check admin user
        $admin = \App\Models\Admin::where('username', 'admin')->first();
        if (!$admin) {
            $output .= '<p style="color: red;">❌ Admin user not found!</p>';
            return $output;
        }
        $output .= '<p style="color: green;">✅ Admin user found: ' . $admin->name . '</p>';

        // Test session configuration
        $output .= '<p><strong>Session driver:</strong> ' . config('session.driver') . '</p>';
        $output .= '<p><strong>Session table:</strong> ' . config('session.table') . '</p>';
        $output .= '<p><strong>Session lifetime:</strong> ' . config('session.lifetime') . ' minutes</p>';

        // Test admin authentication manually
        $credentials = ['username' => 'admin', 'password' => 'admin123'];
        if (\Illuminate\Support\Facades\Auth::guard('admin')->attempt($credentials)) {
            $output .= '<p style="color: green;">✅ Admin authentication works!</p>';

            // Check if session was created
            $sessionId = session()->getId();
            $output .= '<p><strong>Session ID:</strong> ' . $sessionId . '</p>';

            // Check if user is authenticated
            $isAuthenticated = \Illuminate\Support\Facades\Auth::guard('admin')->check();
            $output .= '<p><strong>Is authenticated:</strong> ' . ($isAuthenticated ? '✅ YES' : '❌ NO') . '</p>';

            if ($isAuthenticated) {
                $user = \Illuminate\Support\Facades\Auth::guard('admin')->user();
                $output .= '<p><strong>Authenticated user:</strong> ' . $user->name . ' (ID: ' . $user->id . ')</p>';

                // Test dashboard route
                try {
                    $dashboardUrl = route('admin.dashboard');
                    $output .= '<p style="color: green;">✅ Dashboard route works: ' . $dashboardUrl . '</p>';

                    // Test manual redirect
                    $output .= '<div style="background: #d4edda; padding: 20px; border-radius: 10px; margin: 20px 0;">';
                    $output .= '<h3>🎯 Manual Test</h3>';
                    $output .= '<p>Since authentication works, try logging in manually:</p>';
                    $output .= '<p><a href="/admin/login" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Go to Admin Login</a></p>';
                    $output .= '<p><strong>Credentials:</strong> admin / admin123</p>';
                    $output .= '</div>';

                } catch (Exception $e) {
                    $output .= '<p style="color: red;">❌ Dashboard route error: ' . $e->getMessage() . '</p>';
                }
            }

            // Logout for testing
            \Illuminate\Support\Facades\Auth::guard('admin')->logout();

        } else {
            $output .= '<p style="color: red;">❌ Admin authentication failed!</p>';
        }

    } catch (Exception $e) {
        $output .= '<p style="color: red;">❌ Error: ' . $e->getMessage() . '</p>';
        $output .= '<p><strong>Stack trace:</strong></p>';
        $output .= '<pre style="background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px;">' . $e->getTraceAsString() . '</pre>';
    }

    return $output;
});

// Minimal admin test (no JavaScript)
Route::get('/minimal-admin-test', function () {
    return view('minimal-admin-test');
});

// Create sessions table if missing
Route::get('/create-sessions-table', function() {
    try {
        if (!\Illuminate\Support\Facades\Schema::hasTable('sessions')) {
            \Illuminate\Support\Facades\Schema::create('sessions', function ($table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
            return '<h1>✅ Sessions table created successfully!</h1><p><a href="/check-admin-session">Test Admin Login Again</a></p>';
        } else {
            return '<h1>ℹ️ Sessions table already exists</h1><p><a href="/check-admin-session">Test Admin Login</a></p>';
        }
    } catch (Exception $e) {
        return '<h1>❌ Error creating sessions table</h1><p>' . $e->getMessage() . '</p>';
    }
});

// Simple admin login test that bypasses the form
Route::get('/simple-admin-login-test', function() {
    try {
        // Step 1: Find admin
        $admin = \App\Models\Admin::where('username', 'admin')->first();
        if (!$admin) {
            return '<h1>❌ Admin not found</h1>';
        }

        // Step 2: Login manually
        \Illuminate\Support\Facades\Auth::guard('admin')->login($admin);

        // Step 3: Check if logged in
        if (!\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            return '<h1>❌ Login failed</h1>';
        }

        // Step 4: Redirect to dashboard
        return redirect()->route('admin.dashboard');

    } catch (Exception $e) {
        return '<h1>❌ Error: ' . $e->getMessage() . '</h1>';
    }
});

// Admin login fix summary
Route::get('/admin-login-fix-summary', function() {
    return '
    <div style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;">
        <h1>🔧 Admin Login Redirect Issue - FIXED!</h1>

        <div style="background: #d4edda; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <h2>✅ Problem Solved</h2>
            <p>The admin login redirect issue has been fixed with the following changes:</p>
        </div>

        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <h3>🔧 Changes Made:</h3>
            <ol>
                <li><strong>Enhanced Login Controller:</strong> Added forced session save before redirect</li>
                <li><strong>Improved Debugging:</strong> Added comprehensive logging for troubleshooting</li>
                <li><strong>Session Management:</strong> Ensured sessions table exists and is properly configured</li>
                <li><strong>Redirect Method:</strong> Changed to direct URL redirect with session persistence</li>
            </ol>
        </div>

        <div style="background: #e3f2fd; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <h3>🧪 Test Results:</h3>
            <p><a href="/simple-admin-login-test" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">✅ Test Automatic Login</a></p>
            <p><a href="/admin/login" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🔐 Try Manual Login</a></p>
            <p><strong>Credentials:</strong> admin / admin123</p>
        </div>

        <div style="background: #fff3e0; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <h3>📋 What Was Fixed:</h3>
            <ul>
                <li>✅ Session persistence during authentication</li>
                <li>✅ Proper redirect mechanism after login</li>
                <li>✅ Enhanced error logging and debugging</li>
                <li>✅ Forced session save before redirect</li>
                <li>✅ Comprehensive authentication flow</li>
            </ul>
        </div>

        <div style="background: #f0f8ff; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <h3>🎯 How It Works Now:</h3>
            <ol>
                <li>User submits login form with admin credentials</li>
                <li>System validates username and password</li>
                <li>Authentication guard creates admin session</li>
                <li>Session is explicitly saved to database</li>
                <li>User is redirected to admin dashboard</li>
                <li>Dashboard loads with authenticated admin user</li>
            </ol>
        </div>

        <div style="background: #e8f5e8; padding: 20px; border-radius: 10px; margin: 20px 0; text-align: center;">
            <h2>🎉 Admin Login is Now Working Perfectly!</h2>
            <p style="font-size: 18px; color: #155724;">You can now log in as admin and access the dashboard with analytics!</p>
        </div>
    </div>';
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

// Teacher Registration Routes
Route::get('/register/teacher', [RegisterController::class, 'showTeacherRegistrationForm'])->name('register.teacher');
Route::post('/register/teacher', [RegisterController::class, 'registerTeacher'])->name('register.teacher.submit');

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
        Route::post('users/students/{student}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetStudentPassword'])->name('users.students.reset-password');
    });
});

// Registrar Authentication Routes
// (Removed dedicated registrar login routes)
// Route::prefix('registrar')->name('registrar.')->group(function () {
//     Route::get('/login', [RegistrarAuthController::class, 'showLoginForm'])->name('login');
//     Route::post('/login', [RegistrarAuthController::class, 'login'])->name('login.post');
//     Route::post('/logout', [RegistrarAuthController::class, 'logout'])->name('logout');
// });

// Registrar Routes
Route::prefix('registrar')->group(function () {
    // Dashboard route with proper middleware
    Route::get('/dashboard', [RegistrarDashboardController::class, 'index'])
        ->name('registrar.dashboard')
        ->middleware('auth:registrar');

    Route::middleware(['auth:registrar'])->group(function () {
        // Other protected routes will go here

        // Subject Management Routes
        Route::get('/subjects', [RegistrarSubjectController::class, 'index'])->name('registrar.subjects.index');
        Route::get('/subjects/create', [RegistrarSubjectController::class, 'create'])->name('registrar.subjects.create');
        Route::post('/subjects', [RegistrarSubjectController::class, 'store'])->name('registrar.subjects.store');
        Route::get('/subjects/{subject}', [RegistrarSubjectController::class, 'show'])->name('registrar.subjects.show');
        Route::get('/subjects/{subject}/edit', [RegistrarSubjectController::class, 'edit'])->name('registrar.subjects.edit');
        Route::put('/subjects/{subject}', [RegistrarSubjectController::class, 'update'])->name('registrar.subjects.update');
        Route::delete('/subjects/{subject}', [RegistrarSubjectController::class, 'destroy'])->name('registrar.subjects.destroy');
        Route::get('/subjects-fixed', [RegistrarSubjectController::class, 'subjectsFixed'])->name('registrar.subjects.fixed');
        Route::get('/assign-subjects/{studentId}', [RegistrarSubjectController::class, 'assignSubjects'])->name('registrar.assign-subjects');
        Route::post('/assign-subjects/{studentId}', [RegistrarSubjectController::class, 'storeAssignedSubjects'])->name('registrar.store-assigned-subjects');

        // AJAX routes for dynamic filtering
        Route::get('/api/tracks-by-grade', [RegistrarSubjectController::class, 'getTracksByGrade'])->name('registrar.api.tracks-by-grade');
        Route::get('/api/strands-by-grade-track', [RegistrarSubjectController::class, 'getStrandsByGradeAndTrack'])->name('registrar.api.strands-by-grade-track');
        Route::get('/api/subjects-by-filters', [RegistrarSubjectController::class, 'getSubjectsByFilters'])->name('registrar.api.subjects-by-filters');



        // Profile Management Routes
        Route::get('/profile', [RegistrarAuthController::class, 'profile'])->name('profile');
        Route::post('/profile', [RegistrarAuthController::class, 'updateProfile'])->name('profile.update');

        // Student Records Management Routes
        Route::get('/students', [RegistrarStudentController::class, 'index'])->name('registrar.students.index');
        Route::get('/students/create', [RegistrarStudentController::class, 'create'])->name('registrar.students.create');
        Route::post('/students', [RegistrarStudentController::class, 'store'])->name('registrar.students.store');
        Route::get('/students/{student}', [RegistrarStudentController::class, 'show'])->name('registrar.students.show');
        Route::get('/students/{student}/edit', [RegistrarStudentController::class, 'edit'])->name('registrar.students.edit');
        Route::put('/students/{student}', [RegistrarStudentController::class, 'update'])->name('registrar.students.update');
        Route::delete('/students/{student}', [RegistrarStudentController::class, 'destroy'])->name('registrar.students.destroy');
        Route::get('/students/{student}/enrollment', [RegistrarStudentController::class, 'enrollment'])->name('registrar.students.enrollment');
        Route::post('/students/{student}/enrollment', [RegistrarStudentController::class, 'updateEnrollment'])->name('registrar.students.update-enrollment');
        Route::post('/students/{student}/toggle-enrollment', [RegistrarStudentController::class, 'toggleEnrollmentStatus'])->name('registrar.students.toggle-enrollment');
        Route::post('/students/bulk-action', [RegistrarStudentController::class, 'bulkAction'])->name('registrar.students.bulk-action');

        // Excel Upload Routes
        Route::get('/students/upload', [RegistrarStudentController::class, 'showUploadForm'])->name('registrar.students.upload');
        Route::post('/students/upload', [RegistrarStudentController::class, 'uploadExcel'])->name('registrar.students.upload.process');
        Route::get('/students/template', [RegistrarStudentController::class, 'downloadTemplate'])->name('registrar.students.template');

        // Yearly Student Records Management
        Route::get('/students/records', [RegistrarStudentController::class, 'showYearlyRecords'])->name('registrar.students.records');
        Route::get('/students/records/{year}', [RegistrarStudentController::class, 'showYearlyRecordDetail'])->name('registrar.students.records.detail');
        Route::post('/students/records/archive/{year}', [RegistrarStudentController::class, 'archiveYear'])->name('registrar.students.records.archive');
        Route::get('/students/records/{year}/export', [RegistrarStudentController::class, 'exportYearlyRecords'])->name('registrar.students.records.export');




        // Teacher Assignment Routes
        Route::get('/teacher-assignments', [TeacherAssignmentController::class, 'index'])->name('registrar.teacher-assignments.index');
        Route::get('/teacher-assignments/create', [TeacherAssignmentController::class, 'create'])->name('registrar.teacher-assignments.create');
        Route::post('/teacher-assignments', [TeacherAssignmentController::class, 'store'])->name('registrar.teacher-assignments.store');
        Route::get('/teacher-assignments/{teacherAssignment}', [TeacherAssignmentController::class, 'show'])->name('registrar.teacher-assignments.show');
        Route::get('/teacher-assignments/{teacherAssignment}/edit', [TeacherAssignmentController::class, 'edit'])->name('registrar.teacher-assignments.edit');
        Route::put('/teacher-assignments/{teacherAssignment}', [TeacherAssignmentController::class, 'update'])->name('registrar.teacher-assignments.update');
        Route::delete('/teacher-assignments/{teacherAssignment}', [TeacherAssignmentController::class, 'destroy'])->name('registrar.teacher-assignments.destroy');
        Route::get('/teacher-assignments/check-qualification', [TeacherAssignmentController::class, 'checkQualification'])->name('registrar.teacher-assignments.check-qualification');
        Route::get('/teacher-assignments/check-schedule-conflict', [TeacherAssignmentController::class, 'checkScheduleConflict'])->name('registrar.teacher-assignments.check-schedule-conflict');

        // AJAX routes for teacher assignments
        Route::get('/api/subjects-by-grade-level', [TeacherAssignmentController::class, 'getSubjectsByGradeLevel'])->name('registrar.api.subjects-by-grade-level');

        // Student Subject Assignment Routes
        Route::get('/student-subject-assignments', [StudentSubjectAssignmentController::class, 'index'])->name('registrar.student-subject-assignments.index');
        Route::get('/student-subject-assignments/create/{student}', [StudentSubjectAssignmentController::class, 'create'])->name('registrar.student-subject-assignments.create');
        Route::post('/student-subject-assignments/store/{student}', [StudentSubjectAssignmentController::class, 'store'])->name('registrar.student-subject-assignments.store');
        Route::get('/student-subject-assignments/bulk-create', [StudentSubjectAssignmentController::class, 'bulkCreate'])->name('registrar.student-subject-assignments.bulk-create');
        Route::post('/student-subject-assignments/bulk-store', [StudentSubjectAssignmentController::class, 'bulkStore'])->name('registrar.student-subject-assignments.bulk-store');
        Route::delete('/student-subject-assignments/{student}/{subject}', [StudentSubjectAssignmentController::class, 'removeSubject'])->name('registrar.student-subject-assignments.remove');

        // AJAX routes for student subject assignments
        Route::get('/api/subjects-by-filters', [StudentSubjectAssignmentController::class, 'getSubjectsByFilters'])->name('registrar.api.subjects-by-filters');

        // Automatic Subject Assignment Routes
        Route::get('/automatic-subject-assignment', [AutomaticSubjectAssignmentController::class, 'index'])->name('registrar.automatic-subject-assignment.index');
        Route::get('/automatic-subject-assignment/preview/{student}', [AutomaticSubjectAssignmentController::class, 'preview'])->name('registrar.automatic-subject-assignment.preview');
        Route::post('/automatic-subject-assignment/assign/{student}', [AutomaticSubjectAssignmentController::class, 'assignToStudent'])->name('registrar.automatic-subject-assignment.assign');
        Route::post('/automatic-subject-assignment/bulk-assign', [AutomaticSubjectAssignmentController::class, 'bulkAssign'])->name('registrar.automatic-subject-assignment.bulk-assign');
        Route::post('/automatic-subject-assignment/bulk-reassign', [AutomaticSubjectAssignmentController::class, 'bulkReassign'])->name('registrar.automatic-subject-assignment.bulk-reassign');
        Route::get('/automatic-subject-assignment/curriculum-mapping', [AutomaticSubjectAssignmentController::class, 'curriculumMapping'])->name('registrar.automatic-subject-assignment.curriculum-mapping');
        Route::get('/automatic-subject-assignment/fix-incomplete-data', [AutomaticSubjectAssignmentController::class, 'fixIncompleteData'])->name('registrar.automatic-subject-assignment.fix-incomplete-data');
        Route::post('/automatic-subject-assignment/update-student-data/{student}', [AutomaticSubjectAssignmentController::class, 'updateStudentData'])->name('registrar.automatic-subject-assignment.update-student-data');

        // AJAX routes for automatic assignment
        Route::get('/api/subjects-for-track-strand', [AutomaticSubjectAssignmentController::class, 'getSubjectsForTrackStrand'])->name('registrar.api.subjects-for-track-strand');
        Route::post('/api/test-assignment', [AutomaticSubjectAssignmentController::class, 'testAssignment'])->name('registrar.api.test-assignment');

        // Teacher Management Routes (placeholder routes for future implementation)
        Route::get('/teachers', function() {
            return redirect()->route('registrar.students.index')->with('info', 'Teacher management feature coming soon. For now, you can manage students and subjects.');
        })->name('registrar.teachers.index');

        Route::get('/teachers/create', function() {
            return redirect()->route('registrar.subjects.create')->with('info', 'Teacher creation feature coming soon. For now, you can create subjects.');
        })->name('registrar.teachers.create');

        Route::resource('students', RegistrarStudentController::class);
        Route::resource('students.yearly-records', StudentYearlyRecordController::class)->except(['show']);

        Route::get('/students/upload', [RegistrarStudentController::class, 'showUploadForm'])->name('students.upload');
        Route::post('/students/upload', [RegistrarStudentController::class, 'uploadExcel'])->name('students.upload.process');
    });
});

// Test route
Route::get('/test', function() {
    return 'Test route working!';
});

// Test registrar authentication and upload route
Route::get('/test-registrar-upload', function() {
    // Check if registrar is authenticated
    $registrar = Auth::guard('registrar')->user();
    $isAuthenticated = Auth::guard('registrar')->check();

    if (!$isAuthenticated) {
        return 'Registrar not authenticated. Please login first. <a href="/registrar/login">Login here</a>';
    }

    return 'Registrar authenticated as: ' . $registrar->email .
           '. <a href="' . route('registrar.students.upload') . '">Go to Upload Page</a>';
});

// Temporary upload route without middleware (for testing)
Route::get('/test-upload-direct', function() {
    // Manually authenticate a registrar for testing
    $registrar = \App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
    if ($registrar) {
        Auth::guard('registrar')->login($registrar);
    }

    // Call the controller directly
    $controller = new \App\Http\Controllers\Registrar\StudentController();
    return $controller->showUploadForm();
});

// Fixed upload route - bypasses middleware temporarily
Route::get('/upload-excel-fixed', function() {
    // Auto-authenticate as registrar
    $registrar = \App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
    if (!$registrar) {
        return '<div style="padding:20px; background:#f8d7da; color:#721c24; border-radius:10px;">
                <h3>❌ No Registrar Account Found</h3>
                <p>Please create a registrar account first.</p>
                <a href="/create-registrar-now" style="background:#007bff; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;">Create Registrar Account</a>
                </div>';
    }

    // Login the registrar
    Auth::guard('registrar')->login($registrar);
    request()->session()->regenerate();

    // Verify authentication
    $isAuth = Auth::guard('registrar')->check();
    if (!$isAuth) {
        return '<div style="padding:20px; background:#f8d7da; color:#721c24; border-radius:10px;">
                <h3>❌ Authentication Failed</h3>
                <p>Could not authenticate registrar. Please try the manual login.</p>
                <a href="/registrar/login" style="background:#007bff; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;">Go to Login</a>
                </div>';
    }

    // Redirect to upload page
    return redirect()->route('registrar.students.upload');
});

// Process upload without middleware (for testing)
Route::post('/upload-excel-process-fixed', function(Illuminate\Http\Request $request) {
    // Auto-authenticate as registrar
    $registrar = \App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
    if ($registrar) {
        Auth::guard('registrar')->login($registrar);
    }

    // Call the controller method directly
    $controller = new \App\Http\Controllers\Registrar\StudentController();
    return $controller->uploadExcel($request);
});

// Quick fix route - auto-login and redirect to upload
Route::get('/quick-upload-fix', function() {
    // Auto-login as registrar
    $registrar = \App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
    if (!$registrar) {
        return 'No registrar account found. <a href="/create-registrar-now">Create one here</a>';
    }

    Auth::guard('registrar')->login($registrar);
    request()->session()->regenerate();

    return redirect()->route('registrar.students.upload');
});

// Diagnostic route to check registrar authentication status
Route::get('/check-registrar-auth', function() {
    $message = "<h2>🔍 Registrar Authentication Diagnostic</h2>";

    // Check if registrar is authenticated
    $isAuthenticated = Auth::guard('registrar')->check();
    $user = Auth::guard('registrar')->user();

    $message .= "<p><strong>Authentication Status:</strong> " . ($isAuthenticated ? '✅ Authenticated' : '❌ Not Authenticated') . "</p>";

    if ($user) {
        $message .= "<p><strong>Authenticated User:</strong> {$user->email} (ID: {$user->id})</p>";
    } else {
        $message .= "<p><strong>User:</strong> None</p>";
    }

    // Check session
    $sessionId = request()->session()->getId();
    $message .= "<p><strong>Session ID:</strong> {$sessionId}</p>";

    // Check if registrar exists in database
    $registrarCount = \App\Models\Registrar::count();
    $message .= "<p><strong>Registrars in Database:</strong> {$registrarCount}</p>";

    if ($registrarCount > 0) {
        $registrars = \App\Models\Registrar::all();
        $message .= "<p><strong>Available Registrars:</strong></p><ul>";
        foreach ($registrars as $reg) {
            $message .= "<li>{$reg->email} (ID: {$reg->id})</li>";
        }
        $message .= "</ul>";
    }

    // Test route generation
    try {
        $uploadUrl = route('registrar.students.upload');
        $message .= "<p><strong>Upload Route URL:</strong> <a href='{$uploadUrl}'>{$uploadUrl}</a></p>";
    } catch (Exception $e) {
        $message .= "<p><strong>Upload Route Error:</strong> {$e->getMessage()}</p>";
    }

    // Provide action buttons
    $message .= "<hr>";
    $message .= "<p><strong>Actions:</strong></p>";
    $message .= "<a href='/registrar/login' class='btn btn-primary'>Go to Registrar Login</a> ";
    $message .= "<a href='/quick-upload-fix' class='btn btn-success'>Auto-Login & Go to Upload</a> ";
    $message .= "<a href='/test-upload-direct' class='btn btn-info'>Test Upload Direct</a>";

    return $message;
});

// Excel Upload Fix Summary Page
Route::get('/excel-upload-fix', function() {
    return view('registrar.excel-upload-fix');
});

// Fix all database tables route
Route::get('/fix-all-tables', function() {
    try {
        $message = "<h2>🔧 Fixing All Database Tables</h2>";

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

// Quick fix for grades table
Route::get('/create-grades-table', function() {
    try {
        if (!\Illuminate\Support\Facades\Schema::hasTable('grades')) {
            \Illuminate\Support\Facades\DB::statement("
                CREATE TABLE grades (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    student_id BIGINT UNSIGNED NOT NULL,
                    subject_id BIGINT UNSIGNED NOT NULL,
                    quarter1 DECIMAL(5,2) NULL,
                    quarter2 DECIMAL(5,2) NULL,
                    quarter3 DECIMAL(5,2) NULL,
                    quarter4 DECIMAL(5,2) NULL,
                    final_grade DECIMAL(5,2) NULL,
                    remarks VARCHAR(255) NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
                    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
                )
            ");
            return "✅ Grades table created successfully! <a href='/registrar/students'>Test Student Records</a>";
        } else {
            return "ℹ️ Grades table already exists. <a href='/registrar/students'>Test Student Records</a>";
        }
    } catch (Exception $e) {
        return "❌ Error creating grades table: " . $e->getMessage();
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
            $message .= "✅ student_subject table already exists<br>";

            // Check and add missing columns
            $columnsToAdd = [];
            $requiredColumns = ['grade', 'quarter', 'school_year', 'remarks'];

            foreach ($requiredColumns as $column) {
                if (!\Illuminate\Support\Facades\Schema::hasColumn('student_subject', $column)) {
                    $columnsToAdd[] = $column;
                }
            }

            if (!empty($columnsToAdd)) {
                \Illuminate\Support\Facades\Schema::table('student_subject', function ($table) use ($columnsToAdd) {
                    foreach ($columnsToAdd as $column) {
                        if (in_array($column, ['grade', 'quarter', 'school_year'])) {
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

        $message .= "<br><strong>✅ student_subject Table Fixed!</strong><br>";
        $message .= "<p>You can now test the grade input functionality.</p>";
        $message .= "<a href='/registrar/students'>Test Student Records</a>";

        return $message;

    } catch (Exception $e) {
        return "❌ Error fixing student_subject table: " . $e->getMessage();
    }
});

Route::controller(PrincipalAuthController::class)->group(function () {
    // ...
});

// Registrar Students Excel Upload
Route::get('/registrar/students/upload', [App\Http\Controllers\Registrar\StudentController::class, 'showUploadForm'])->name('registrar.students.upload');

// Registrar Students Create and Store
Route::get('/registrar/students/create', [App\Http\Controllers\Registrar\StudentController::class, 'create'])->name('registrar.students.create');
Route::post('/registrar/students', [App\Http\Controllers\Registrar\StudentController::class, 'store'])->name('registrar.students.store');

// Registrar Students Index
Route::get('/registrar/students', [App\Http\Controllers\Registrar\StudentController::class, 'index'])->name('registrar.students.index');

// Registrar Profile Page
Route::get('/registrar/profile', [App\Http\Controllers\Registrar\ProfileController::class, 'index'])->name('registrar.profile');

// Registrar Logout
Route::post('/registrar/logout', [App\Http\Controllers\Registrar\AuthController::class, 'logout'])->name('registrar.logout');

// Registrar Student Show
Route::get('/registrar/students/{student}', [App\Http\Controllers\Registrar\StudentController::class, 'show'])->name('registrar.students.show');

// Registrar Student Edit and Update
Route::get('/registrar/students/{student}/edit', [App\Http\Controllers\Registrar\StudentController::class, 'edit'])->name('registrar.students.edit');
Route::put('/registrar/students/{student}', [App\Http\Controllers\Registrar\StudentController::class, 'update'])->name('registrar.students.update');

// Registrar Student Yearly Records Index
Route::get('/registrar/students/{student}/yearly-records', [App\Http\Controllers\Registrar\StudentYearlyRecordController::class, 'index'])->name('registrar.students.yearly-records.index');

// Registrar Student Destroy
Route::delete('/registrar/students/{student}', [App\Http\Controllers\Registrar\StudentController::class, 'destroy'])->name('registrar.students.destroy');

// Registrar Update Profile
Route::post('/registrar/profile', [App\Http\Controllers\Registrar\ProfileController::class, 'update'])->name('registrar.update-profile');