<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - CNHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 400px;
            margin: 40px auto;
            padding: 2.5rem 2rem;
            background: rgba(255,255,255,0.97);
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }
        .form-control {
            padding: 14px;
            border-radius: 7px;
            border: 1px solid #d1d5db;
            font-size: 1rem;
            background: #f9fafb;
            margin-bottom: 1rem;
        }
        .btn-login {
            padding: 14px;
            border-radius: 7px;
            background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
            border: none;
            width: 100%;
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="text-center mb-4">
                <h1>Student Login</h1>
                <p class="text-muted">Enter your credentials to continue</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mb-3">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="studentLoginForm">
                @csrf
                
                <!-- Hidden role field -->
                <input type="hidden" name="role" value="student">
                
                <div class="mb-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" 
                           class="form-control @error('student_id') is-invalid @enderror" 
                           name="student_id" 
                           id="student_id" 
                           placeholder="Enter your Student ID"
                           value="{{ old('student_id') }}"
                           required>
                    @error('student_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           name="password" 
                           id="password" 
                           placeholder="Enter your password"
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    Login as Student
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-decoration-none">← Back to main login</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('studentLoginForm').addEventListener('submit', function(e) {
            console.log('=== STUDENT LOGIN FORM SUBMITTING ===');
            const formData = new FormData(this);
            console.log('Form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + (key === 'password' ? '***' : value));
            }
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
        });
    </script>
</body>
</html>
