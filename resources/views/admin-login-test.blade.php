<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Test - CNHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Admin Login Diagnostic Test</h4>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <h6>Validation Errors:</h6>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Test Admin Login</h5>
                                <form method="POST" action="/admin/login" id="adminTestForm">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Admin Username</label>
                                        <input type="text" class="form-control" name="username" id="username" value="admin" required>
                                        <small class="text-muted">Pre-filled with: admin</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" id="password" value="admin123" required>
                                        <small class="text-muted">Pre-filled with: admin123</small>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100" id="testLoginBtn">
                                        Test Admin Login
                                    </button>
                                </form>
                            </div>

                            <div class="col-md-6">
                                <h5>Diagnostic Information</h5>
                                <div class="alert alert-info">
                                    <strong>Form Details:</strong><br>
                                    Action: /admin/login<br>
                                    Method: POST<br>
                                    CSRF Token: {{ csrf_token() }}
                                </div>

                                <div class="alert alert-secondary">
                                    <strong>Test Credentials:</strong><br>
                                    Username: admin<br>
                                    Password: admin123<br>
                                    Role: admin
                                </div>

                                <div id="diagnosticResults" class="alert alert-light">
                                    <strong>Diagnostic Results:</strong><br>
                                    <span id="diagnosticText">Click the login button to test...</span>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Navigation Links:</h6>
                                <a href="{{ route('login') }}" class="btn btn-secondary me-2">Main Login Page</a>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-info me-2">Admin Dashboard (Direct)</a>
                                <a href="/test-all-logins" class="btn btn-warning">Test All Logins</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('adminTestForm');
            const testBtn = document.getElementById('testLoginBtn');
            const diagnosticText = document.getElementById('diagnosticText');

            // Form submit event
            form.addEventListener('submit', function(e) {
                console.log('Admin test form submitted!');
                console.log('Form action:', this.action);
                console.log('Username:', this.querySelector('[name="username"]').value);
                console.log('Password:', this.querySelector('[name="password"]').value);
                console.log('Role:', this.querySelector('[name="role"]').value);

                diagnosticText.innerHTML = `
                    <strong>Form Submitted!</strong><br>
                    Action: ${this.action}<br>
                    Username: ${this.querySelector('[name="username"]').value}<br>
                    Password: [PROVIDED]<br>
                    Role: ${this.querySelector('[name="role"]').value}<br>
                    <em>Check server response...</em>
                `;
            });

            // Button click event
            testBtn.addEventListener('click', function(e) {
                console.log('Test login button clicked!');
                diagnosticText.innerHTML = '<strong>Button clicked!</strong> Submitting form...';
            });
        });
    </script>
</body>
</html>
