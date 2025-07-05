<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - CNHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .password-change-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .school-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px auto;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .form-container {
            padding: 40px;
        }
        .welcome-message {
            background: #e0f2fe;
            border-left: 4px solid #0288d1;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
        }
        .security-info {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1e40af, #2563eb);
            transform: translateY(-1px);
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }
        .password-requirements {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 8px;
        }
        .password-requirements ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="password-change-container">
        <div class="header">
            <div class="school-logo">
                CNHS
            </div>
            <h3>Calingcaging National High School</h3>
            <p class="mb-0">Change Your Password</p>
        </div>
        
        <div class="form-container">
            <div class="welcome-message">
                <h6><i class="fas fa-user-check text-primary"></i> Welcome, {{ $teacher->name }}!</h6>
                <p class="mb-0 small">For security reasons, you must change your password before accessing the teacher portal.</p>
            </div>

            <div class="security-info">
                <h6><i class="fas fa-shield-alt text-warning"></i> Security Requirements</h6>
                <p class="mb-0 small">Please create a strong password that you can remember. This will be your permanent login password.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.password.change') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="current_password" class="form-label">
                        <i class="fas fa-key text-muted"></i> Current Password
                    </label>
                    <input type="password" 
                           class="form-control @error('current_password') is-invalid @enderror" 
                           id="current_password" 
                           name="current_password" 
                           required 
                           placeholder="Enter the password from your email">
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">This is the password sent to your email</small>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock text-muted"></i> New Password
                    </label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           required 
                           placeholder="Create a new secure password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="password-requirements">
                        <strong>Password Requirements:</strong>
                        <ul>
                            <li>At least 8 characters long</li>
                            <li>Mix of letters, numbers, and symbols recommended</li>
                            <li>Should be unique and memorable to you</li>
                        </ul>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-lock text-muted"></i> Confirm New Password
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required 
                           placeholder="Re-enter your new password">
                    <small class="text-muted">Must match the password above</small>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check-circle"></i> Change Password & Continue
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    After changing your password, you'll be redirected to the teacher dashboard.
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const requirements = document.querySelector('.password-requirements ul');
            
            // Simple password strength check
            if (password.length >= 8) {
                requirements.children[0].style.color = '#059669';
                requirements.children[0].innerHTML = '✓ At least 8 characters long';
            } else {
                requirements.children[0].style.color = '#dc2626';
                requirements.children[0].innerHTML = '✗ At least 8 characters long';
            }
        });

        // Confirm password match check
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password === confirmPassword && confirmPassword.length > 0) {
                this.style.borderColor = '#059669';
            } else if (confirmPassword.length > 0) {
                this.style.borderColor = '#dc2626';
            }
        });
    </script>
</body>
</html>
