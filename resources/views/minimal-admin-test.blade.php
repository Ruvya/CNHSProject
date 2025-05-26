<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimal Admin Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h4>🚨 MINIMAL ADMIN LOGIN TEST</h4>
                        <small>No JavaScript - Pure HTML Form</small>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <strong>Errors:</strong>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                <strong>Session Error:</strong> {{ session('error') }}
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success">
                                <strong>Success:</strong> {{ session('success') }}
                            </div>
                        @endif

                        <!-- MINIMAL FORM - NO JAVASCRIPT -->
                        <form method="POST" action="/admin/login" style="border: 2px solid red; padding: 20px;">
                            @csrf

                            <div class="alert alert-info">
                                <strong>Form Details:</strong><br>
                                Action: /admin/login<br>
                                Method: POST<br>
                                CSRF: {{ csrf_token() }}
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Role (Hidden)</label>
                                <input type="text" class="form-control" value="admin" disabled>
                                <small class="text-muted">Hidden field: role = admin</small>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" value="admin" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" value="admin123" required>
                            </div>

                            <button type="submit" class="btn btn-danger w-100" style="font-size: 18px; padding: 15px;">
                                🚨 SUBMIT ADMIN LOGIN 🚨
                            </button>
                        </form>

                        <hr>

                        <div class="mt-3">
                            <h6>Alternative Tests:</h6>
                            <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Main Login</a>
                            <a href="/admin-login-test" class="btn btn-info btn-sm">Diagnostic Test</a>
                            <a href="/test-all-logins" class="btn btn-warning btn-sm">All Logins</a>
                        </div>

                        <div class="mt-3 alert alert-warning">
                            <strong>What This Tests:</strong><br>
                            • Pure HTML form submission (no JavaScript interference)<br>
                            • Direct POST to /login route<br>
                            • Pre-filled with correct admin credentials<br>
                            • Should show server response or redirect
                        </div>

                        <hr>

                        <!-- TEST POST FUNCTIONALITY -->
                        <div class="mt-3">
                            <h6>🔧 Test POST Functionality</h6>
                            <form method="POST" action="/test-post" style="border: 1px solid blue; padding: 10px;">
                                @csrf
                                <input type="hidden" name="test" value="post-working">
                                <button type="submit" class="btn btn-info btn-sm">Test POST Request</button>
                                <small class="text-muted">This should return JSON response</small>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Minimal logging only
        document.querySelector('form').addEventListener('submit', function(e) {
            console.log('🚨 MINIMAL FORM SUBMITTING');
            console.log('Action:', this.action);
            console.log('Method:', this.method);
            console.log('Username:', this.querySelector('[name="username"]').value);
            console.log('Role:', this.querySelector('[name="role"]').value);
        });
    </script>
</body>
</html>
