@extends('layouts.app')

@section('content')
<style>
    body {
        background: #f8fafc;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }
    
    .registration-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .registration-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 480px;
        overflow: hidden;
    }
    
    .registration-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        text-align: center;
        position: relative;
    }
    
    .registration-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    
    .registration-header h1 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        position: relative;
        z-index: 1;
    }
    
    .registration-header p {
        font-size: 0.95rem;
        opacity: 0.9;
        margin: 0;
        position: relative;
        z-index: 1;
    }
    
    .registration-body {
        padding: 2rem;
    }
    
    .form-section {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        background: #f9fafb;
        transition: all 0.2s ease;
        color: #1f2937;
        box-sizing: border-box;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }
    
    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    .invalid-feedback {
        display: block;
        font-size: 0.875rem;
        color: #ef4444;
        margin-top: 0.5rem;
        font-weight: 500;
    }
    
    .password-requirements {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.5rem;
        line-height: 1.4;
    }
    
    .btn-register {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 1rem;
    }
    
    .btn-register:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    
    .btn-register:active {
        transform: translateY(0);
    }
    
    .terms-section {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
        text-align: center;
    }
    
    .terms-text {
        font-size: 0.875rem;
        color: #6b7280;
        line-height: 1.5;
    }
    
    .login-link {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }
    
    .login-link p {
        margin: 0;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .login-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }
    
    .login-link a:hover {
        color: #5a67d8;
        text-decoration: underline;
    }
    
    .input-group {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1.1rem;
        transition: color 0.2s ease;
    }
    
    .form-control:focus + .input-icon {
        color: #667eea;
    }
    
    .input-group .form-control {
        padding-left: 3rem;
    }
    
    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #9ca3af;
        cursor: pointer;
        font-size: 1.1rem;
        transition: color 0.2s ease;
        padding: 0;
    }
    
    .password-toggle:hover {
        color: #667eea;
    }
    
    .input-group.has-toggle .form-control {
        padding-right: 3rem;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    @media (max-width: 480px) {
        .registration-card {
            margin: 10px;
            border-radius: 12px;
        }
        
        .registration-header {
            padding: 1.5rem;
        }
        
        .registration-body {
            padding: 1.5rem;
        }
        
        .registration-header h1 {
            font-size: 1.5rem;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="registration-wrapper">
    <div class="registration-card">
        <div class="registration-header">
            <h1><i class="fas fa-chalkboard-teacher me-2"></i>Teacher Registration</h1>
            <p>Join our educational community and start making a difference</p>
        </div>
        
        <div class="registration-body">
            <form method="POST" action="{{ route('register.teacher.submit') }}">
                @csrf

                <div class="form-section">
                    <label for="name" class="form-label">
                        <i class="fas fa-user me-1"></i>Full Name
                    </label>
                    <div class="input-group">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus 
                               placeholder="Enter your full name">
                        <i class="fas fa-user input-icon"></i>
                    </div>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-section">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope me-1"></i>Email Address
                    </label>
                    <div class="input-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email" 
                               placeholder="Enter your email address">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-section">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock me-1"></i>Password
                    </label>
                    <div class="input-group has-toggle">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="new-password" 
                               placeholder="Create a strong password">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-requirements">
                        Password must be at least 8 characters long and contain letters and numbers.
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-section">
                    <label for="password-confirm" class="form-label">
                        <i class="fas fa-lock me-1"></i>Confirm Password
                    </label>
                    <div class="input-group has-toggle">
                        <input id="password-confirm" type="password" class="form-control" 
                               name="password_confirmation" required autocomplete="new-password" 
                               placeholder="Confirm your password">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus me-2"></i>Create Teacher Account
                </button>
            </form>
            
            <div class="terms-section">
                <p class="terms-text">
                    By creating an account, you agree to our 
                    <a href="#" style="color: #667eea; text-decoration: none;">Terms of Service</a> 
                    and <a href="#" style="color: #667eea; text-decoration: none;">Privacy Policy</a>.
                </p>
            </div>
            
            <div class="login-link">
                <p>Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const toggle = field.nextElementSibling.nextElementSibling;
    const icon = toggle.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection 