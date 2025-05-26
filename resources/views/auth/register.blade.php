@extends('layouts.app')

@section('content')
<div class="register-container">
    <div class="register-header">
        <h1>Register to CNHS</h1>
        <p class="text-muted">Create your account to get started</p>
    </div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card">
        <div class="card-header">{{ __('Registration') }}</div>
        <div class="card-body">
            <div class="role-selector mb-4">
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="role" id="student" value="student" checked>
                    <label class="btn btn-outline-primary" for="student">
                        <i class="fas fa-user-graduate"></i> Student
                    </label>

                    <input type="radio" class="btn-check" name="role" id="teacher" value="teacher">
                    <label class="btn btn-outline-primary" for="teacher">
                        <i class="fas fa-chalkboard-teacher"></i> Teacher
                    </label>
                </div>
            </div>

            <!-- Student Registration Form -->
            <form method="POST" action="{{ route('register.student') }}" id="studentForm" class="registration-form">
                @csrf
                <input type="hidden" name="role" value="student">
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row mb-3">
                    <label for="first_name" class="col-md-4 col-form-label text-md-end">{{ __('First Name') }}</label>
                    <div class="col-md-6">
                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>
                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                 <div class="row mb-3">
                    <label for="middle_name" class="col-md-4 col-form-label text-md-end">{{ __('Middle Name') }}</label>
                    <div class="col-md-6">
                        <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" name="middle_name" value="{{ old('middle_name') }}" required autocomplete="middle_name">
                        @error('middle_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="last_name" class="col-md-4 col-form-label text-md-end">{{ __('Last Name') }}</label>
                    <div class="col-md-6">
                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name">
                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="student_id" class="col-md-4 col-form-label text-md-end">Student ID</label>
                    <div class="col-md-6">
                        <input id="student_id" type="text" class="form-control" name="student_id" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="grade_level" class="col-md-4 col-form-label text-md-end">{{ __('Grade Level') }}</label>
                    <div class="col-md-6">
                        <select id="grade_level" class="form-control @error('grade_level') is-invalid @enderror" name="grade_level" required>
                            <option value="">Select Grade Level</option>
                            <option value="11" {{ old('grade_level') == '11' ? 'selected' : '' }}>Grade 11</option>
                            <option value="12" {{ old('grade_level') == '12' ? 'selected' : '' }}>Grade 12</option>
                        </select>
                        @error('grade_level')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>


           

           
                <div class="row mb-3">
                    <label for="gender" class="col-md-4 col-form-label text-md-end">{{ __('Gender') }}</label>
                    <div class="col-md-6">
                        <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('gender')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Register as Student') }}
                        </button>
                    </div>
                </div>
            </form>

            
            <!-- Teacher Registration Form -->
            <form method="POST" action="{{ route('register.teacher') }}" id="teacherForm" class="registration-form" style="display: none;">
                @csrf
                <input type="hidden" name="role" value="teacher">
                <div class="row mb-3">
                    <label for="teacher_name" class="col-md-4 col-form-label text-md-end">{{ __('Full Name') }}</label>
                    <div class="col-md-6">
                        <input id="teacher_name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="teacher_email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                    <div class="col-md-6">
                        <input id="teacher_email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="teacher_password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                    <div class="col-md-6">
                        <input id="teacher_password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="teacher_password_confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                    <div class="col-md-6">
                        <input id="teacher_password_confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Register as Teacher') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body {
        min-height: 100vh;
        background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
        font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
    }
    .login-container, .register-container {
        max-width:100%;
        margin: 40px auto 0 auto;
        padding: 2.5rem 2rem;
        background: rgba(255,255,255,0.97);
        border-radius: 18px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        transition: box-shadow 0.3s;
    }
    .login-container:hover, .register-container:hover {
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.18);
    }
    .login-header, .register-header {
        text-align: center;
        margin-bottom: 2.2rem;
    }
    .login-header h1, .register-header h1 {
        font-size: 2rem;
        color: #2d3a4a;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: 1px;
    }
    .login-header p, .register-header p {
        color: #6b7280;
        font-size: 1rem;
    }
    .form-control {
        padding: 14px;
        border-radius: 7px;
        border: 1px solid #d1d5db;
        font-size: 1rem;
        background: #f9fafb;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px #6366f133;
        background: #fff;
    }
    .input-group-text {
        background: transparent;
        border: none;
        color: #6366f1;
        font-size: 1.2rem;
    }
    .input-group {
        border-radius: 7px;
        overflow: hidden;
        border: 1px solid #d1d5db;
        margin-bottom: 1rem;
        background: #f9fafb;
    }
    .btn-login, .btn-primary {
        padding: 14px;
        border-radius: 7px;
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        border: none;
        width: 100%;
        color: #fff;
        font-weight: 600;
        font-size: 1.1rem;
        box-shadow: 0 2px 8px 0 #6366f122;
        transition: background 0.2s, box-shadow 0.2s;
        margin-top: 10px;
    }
    .btn-login:hover, .btn-primary:hover {
        background: linear-gradient(90deg, #4f46e5 0%, #2563eb 100%);
        box-shadow: 0 4px 16px 0 #6366f133;
    }
    .role-selector {
        display: flex;
        gap: 12px;
        margin-bottom: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    .role-option {
        flex: 1 1 110px;
        min-width: 110px;
        max-width: 140px;
        text-align: center;
        padding: 10px 0;
        border: 2px solid #e5e7eb;
        border-radius: 7px;
        cursor: pointer;
        transition: all 0.2s;
        background: #f3f4f6;
        color: #374151;
        font-weight: 500;
        font-size: 1rem;
    }
    .role-option:hover, .role-option.active {
        border-color: #6366f1;
        background: #eef2ff;
        color: #3730a3;
    }
    .role-option input[type="radio"] {
        display: none;
    }
    .register-link {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 1rem;
    }
    .register-link a {
        color: #6366f1;
        text-decoration: underline;
        font-weight: 500;
    }
    .alert {
        font-size: 0.98rem;
        border-radius: 7px;
    }
    @media (max-width: 500px) {
        .login-container, .register-container {
            padding: 1.2rem 0.5rem;
            max-width: 98vw;
        }
        .login-header h1, .register-header h1 {
            font-size: 1.3rem;
        }
    }
</style>

<script>
// Toggle between student and teacher registration forms
const roleInputs = document.querySelectorAll('input[name="role"]');
const studentForm = document.getElementById('studentForm');
const teacherForm = document.getElementById('teacherForm');

roleInputs.forEach(input => {
    input.addEventListener('change', function() {
        if (this.value === 'student') {
            studentForm.style.display = 'block';
            teacherForm.style.display = 'none';
        } else if (this.value === 'teacher') {
            studentForm.style.display = 'none';
            teacherForm.style.display = 'block';
        }
    });
});

document.getElementById('studentForm').addEventListener('submit', function() {
    console.log('Submitting student form');
});
document.getElementById('teacherForm').addEventListener('submit', function() {
    console.log('Submitting teacher form');
});
</script>
@endsection 