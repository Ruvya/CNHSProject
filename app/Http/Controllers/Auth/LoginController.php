<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // EMERGENCY DEBUG - Log EVERYTHING
        \Log::emergency('=== EMERGENCY LOGIN DEBUG ===', [
            'timestamp' => now(),
            'role' => $request->input('role'),
            'student_id' => $request->input('student_id'),
            'has_student_id' => $request->has('student_id'),
            'password_length' => strlen($request->input('password', '')),
            'all_data' => $request->all(),
            'request_method' => $request->method(),
            'url' => $request->url(),
            'ip' => $request->ip()
        ]);

        // If it's a student login, handle it immediately
        if ($request->input('role') === 'student') {
            \Log::emergency('STUDENT LOGIN DETECTED', [
                'student_id' => $request->input('student_id'),
                'has_password' => !empty($request->input('password'))
            ]);

            // Simple validation
            if (empty($request->input('student_id'))) {
                \Log::emergency('STUDENT ID MISSING');
                return back()->withErrors(['student_id' => 'Student ID is required'])->withInput();
            }

            if (empty($request->input('password'))) {
                \Log::emergency('PASSWORD MISSING');
                return back()->withErrors(['password' => 'Password is required'])->withInput();
            }

            // Find student
            $student = \App\Models\Student::where('student_id', $request->input('student_id'))->first();
            \Log::emergency('STUDENT LOOKUP', [
                'found' => $student ? 'yes' : 'no',
                'student_id_searched' => $request->input('student_id')
            ]);

            if (!$student) {
                return back()->withErrors(['student_id' => 'Student not found'])->withInput();
            }

            // Check password
            $passwordMatch = \Hash::check($request->input('password'), $student->password);
            \Log::emergency('PASSWORD CHECK', [
                'match' => $passwordMatch ? 'yes' : 'no'
            ]);

            if (!$passwordMatch) {
                return back()->withErrors(['password' => 'Invalid password'])->withInput();
            }

            // Login student with proper session handling
            \Auth::guard('student')->login($student, true); // Remember the user

            // Force session save
            $request->session()->save();

            \Log::emergency('STUDENT LOGIN COMPLETED', [
                'student_id' => $student->student_id,
                'student_db_id' => $student->id,
                'auth_check' => \Auth::guard('student')->check(),
                'session_id' => $request->session()->getId(),
                'attempting_redirect' => true
            ]);

            // Use the simplest possible redirect
            return redirect()->to('/student/dashboard');
        }

        // Log login attempts for debugging
        \Log::info('=== LOGIN ATTEMPT RECEIVED ===', [
            'timestamp' => now(),
            'role' => $request->input('role'),
            'all_data' => $request->all(),
            'has_email' => $request->has('email'),
            'email_value' => $request->input('email'),
            'request_method' => $request->method(),
            'content_type' => $request->header('Content-Type')
        ]);

        $role = $request->input('role');

        // Special handling for registrar to debug the issue
        if ($role === 'registrar') {
            \Log::info('REGISTRAR LOGIN DEBUG', [
                'email_from_request' => $request->input('email'),
                'email_exists' => $request->has('email'),
                'all_inputs' => $request->all(),
                'form_data_keys' => array_keys($request->all())
            ]);

            // Check if email is actually empty
            if (!$request->has('email') || empty($request->input('email'))) {
                return back()->withErrors([
                    'email' => 'Email field is missing or empty. Please make sure you enter your email address.',
                ])->withInput();
            }
        }

        $rules = [
            'role' => 'required|in:admin,teacher,student,registrar',
            'password' => 'required',
        ];

        if ($role === 'admin') {
            $rules['username'] = 'required';
        } elseif ($role === 'teacher') {
            $rules['email'] = 'required|email';
        } elseif ($role === 'student') {
            $rules['student_id'] = 'required';
        } elseif ($role === 'registrar') {
            $rules['email'] = 'required|email';
        }

        $credentials = $request->validate($rules);

        // Note: Admin login now uses dedicated /admin/login route and Admin\AuthController
        if ($role === 'teacher') {
            if (Auth::guard('teacher')->attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
                $request->session()->regenerate();
                return redirect()->intended(route('teacher.dashboard'));
            }
        } elseif ($role === 'student') {
            $credentials = $request->only('student_id', 'password');

            \Log::info('Student Login Attempt', [
                'student_id' => $credentials['student_id'],
                'has_password' => !empty($credentials['password'])
            ]);

            // Check if student exists
            $student = \App\Models\Student::where('student_id', $credentials['student_id'])->first();

            if (!$student) {
                \Log::info('Student not found', ['student_id' => $credentials['student_id']]);

                // If regular student not found, check temporary credentials
                $tempCredential = \App\Models\TemporaryStudentCredential::where('student_id', $credentials['student_id'])
                    ->where('is_used', false)
                    ->first();

                if ($tempCredential && \Hash::check($credentials['password'], $tempCredential->password)) {
                    // Create a new student record with temporary account flag
                    $student = \App\Models\Student::create([
                        'student_id' => $tempCredential->student_id,
                        'password' => $tempCredential->password, // Already hashed
                        'is_temporary_account' => true,
                        'profile_completed' => false,
                        'first_name' => 'Student', // Placeholder
                        'last_name' => $tempCredential->student_id, // Use student ID as placeholder
                        'email' => $tempCredential->student_id . '@temp.cnhs.edu.ph', // Temporary email
                        'grade_level' => 'Not Set', // Temporary placeholder for grade level
                        'gender' => 'Not Set' // Temporary placeholder for gender
                    ]);

                    // Mark the temporary credential as used
                    $tempCredential->markAsUsed($student);

                    // Log in the new student
                    Auth::guard('student')->login($student);
                    $request->session()->regenerate();

                    // Redirect to profile completion page
                    return redirect()->route('student.profile.complete')
                        ->with('message', 'Welcome! Please complete your profile information.');
                }

                return back()->withErrors([
                    'student_id' => 'No student account found with this ID.',
                ])->onlyInput('student_id');
            }

            // Student exists, check password
            if (\Hash::check($credentials['password'], $student->password)) {
                // Manual login since Auth::attempt might have issues
                Auth::guard('student')->login($student);
                $request->session()->regenerate();

                \Log::info('Student login successful', [
                    'student_id' => $credentials['student_id'],
                    'student_db_id' => $student->id
                ]);

                return redirect()->intended(route('student.dashboard'));
            }

            \Log::info('Student login failed - invalid password', ['student_id' => $credentials['student_id']]);

            return back()->withErrors([
                'password' => 'The provided password is incorrect.',
            ])->onlyInput('student_id');
        } elseif ($role === 'registrar') {
            // Use same manual authentication approach that works in dedicated login
            $user = \App\Models\Registrar::where('email', $credentials['email'])->first();
            $passwordCorrect = $user ? \Hash::check($credentials['password'], $user->password) : false;

            \Log::info('Main Login - Registrar Attempt', [
                'email' => $credentials['email'],
                'user_exists' => $user ? 'yes' : 'no',
                'password_correct' => $passwordCorrect,
                'credentials_received' => $credentials,
                'session_id' => $request->session()->getId()
            ]);

            if ($user && $passwordCorrect) {
                // Manual login since Auth::attempt might have issues
                Auth::guard('registrar')->login($user);
                $request->session()->regenerate();
                $request->session()->save(); // Force session save

                // Verify login was successful
                $authCheck = Auth::guard('registrar')->check();
                $authUser = Auth::guard('registrar')->user();

                \Log::info('Main Login - Registrar Success', [
                    'email' => $credentials['email'],
                    'auth_check' => $authCheck,
                    'auth_user_id' => $authUser ? $authUser->id : null,
                    'session_id' => $request->session()->getId()
                ]);

                if (!$authCheck) {
                    \Log::error('Main Login - Registrar Auth check failed after manual login', [
                        'email' => $credentials['email']
                    ]);
                    return back()->withErrors(['email' => 'Authentication failed. Please try again.']);
                }

                return redirect()->intended(route('registrar.dashboard'));
            }

            \Log::info('Main Login - Registrar Failed', [
                'email' => $credentials['email'],
                'user_exists' => $user ? 'yes' : 'no',
                'password_correct' => $passwordCorrect
            ]);

            return back()->withErrors([
                'email' => 'Invalid email or password. Please check your credentials.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Auth::guard('teacher')->logout();
        Auth::guard('student')->logout();
        Auth::guard('registrar')->logout();
        Auth::guard('Principal')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}