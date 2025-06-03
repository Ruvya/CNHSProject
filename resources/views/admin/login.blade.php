<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CNHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            transition: box-shadow 0.3s;
        }
        .login-container:hover {
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.18);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2.2rem;
        }
        .login-header h1 {
            font-size: 2rem;
            color: #2d3a4a;
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }
        .login-header p {
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
            border-color: #667eea;
            box-shadow: 0 0 0 2px #667eea33;
            background: #fff;
        }
        .input-group-text {
            background: transparent;
            border: none;
            color: #667eea;
            font-size: 1.2rem;
        }
        .input-group {
            border-radius: 7px;
            overflow: hidden;
            border: 1px solid #d1d5db;
            margin-bottom: 1rem;
            background: #f9fafb;
        }
        .btn-login {
            padding: 14px;
            border-radius: 7px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border: none;
            width: 100%;
            color: #fff;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px 0 #667eea22;
            transition: background 0.2s, box-shadow 0.2s;
            margin-top: 10px;
        }
        .btn-login:hover {
            background: linear-gradient(90deg, #5a67d8 0%, #6b46c1 100%);
            box-shadow: 0 4px 16px 0 #667eea33;
        }
        .alert {
            font-size: 0.98rem;
            border-radius: 7px;
        }
        .admin-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <div class="admin-badge">
                    <i class='bx bxs-shield'></i> Administrator Access
                </div>
                <h1>Admin Login</h1>
                <p class="text-muted">Enter your admin credentials</p>
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

            <form method="POST" action="{{ route('admin.login') }}" id="adminLoginForm">
                @csrf

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class='bx bxs-user'></i>
                        </span>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                               name="username"
                               placeholder="Admin Username"
                               value="{{ old('username') }}"
                               required
                               autofocus>
                    </div>
                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class='bx bxs-lock-alt'></i>
                        </span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password"
                               placeholder="Password"
                               required>
                        <span class="input-group-text" style="cursor: pointer" onclick="togglePassword(this)">
                            <i class='bx bxs-show'></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-login">
                    <i class='bx bxs-log-in'></i> Login
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-muted">
                        <i class='bx bx-arrow-back'></i> Back to Main Login
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(element) {
            const passwordInput = element.parentElement.querySelector('input[type="password"]');
            const icon = element.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bxs-show');
                icon.classList.add('bxs-hide');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bxs-hide');
                icon.classList.add('bxs-show');
            }
        }

        // Add form submission debugging
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('adminLoginForm');
            const submitBtn = form.querySelector('button[type="submit"]');

            form.addEventListener('submit', function(e) {
                console.log('Admin login form submitted');
                console.log('Form action:', form.action);
                console.log('Form method:', form.method);

                // Change button text to show it's processing
                submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Logging in...';
                submitBtn.disabled = true;

                // Re-enable after 5 seconds in case something goes wrong
                setTimeout(function() {
                    submitBtn.innerHTML = '<i class="bx bxs-log-in"></i> Login';
                    submitBtn.disabled = false;
                }, 5000);
            });
        });
    </script>
</body>
</html>
