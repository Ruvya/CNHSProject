<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Admin Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>🧪 Test Admin Login Form</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5>🔍 Testing Admin Login</h5>
                            <p>This form will help us debug the admin login issue.</p>
                            <p><strong>Credentials:</strong> admin / admin123</p>
                        </div>

                        <!-- Test Form 1: Direct to /admin/login -->
                        <h5>Test 1: Direct to /admin/login</h5>
                        <form method="POST" action="/admin/login" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label for="username1" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username1" name="username" value="admin" required>
                            </div>
                            <div class="mb-3">
                                <label for="password1" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password1" name="password" value="admin123" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Test Direct Admin Login</button>
                        </form>

                        <hr>

                        <!-- Test Form 2: To debug route -->
                        <h5>Test 2: To Debug Route</h5>
                        <form method="POST" action="/debug-admin-login-post" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label for="username2" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username2" name="username" value="admin" required>
                            </div>
                            <div class="mb-3">
                                <label for="password2" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password2" name="password" value="admin123" required>
                            </div>
                            <button type="submit" class="btn btn-warning">Test Debug Route</button>
                        </form>

                        <hr>

                        <!-- Test Form 3: Main login with admin role -->
                        <h5>Test 3: Main Login with Admin Role</h5>
                        <form method="POST" action="/login" class="mb-4">
                            @csrf
                            <input type="hidden" name="role" value="admin">
                            <div class="mb-3">
                                <label for="username3" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username3" name="username" value="admin" required>
                            </div>
                            <div class="mb-3">
                                <label for="password3" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password3" name="password" value="admin123" required>
                            </div>
                            <button type="submit" class="btn btn-success">Test Main Login (Admin Role)</button>
                        </form>

                        <hr>

                        <!-- Test Form 4: Debug main login -->
                        <h5>Test 4: Debug Main Login</h5>
                        <form method="POST" action="/debug-main-login" class="mb-4">
                            @csrf
                            <input type="hidden" name="role" value="admin">
                            <div class="mb-3">
                                <label for="username4" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username4" name="username" value="admin" required>
                            </div>
                            <div class="mb-3">
                                <label for="password4" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password4" name="password" value="admin123" required>
                            </div>
                            <button type="submit" class="btn btn-info">Debug Main Login</button>
                        </form>

                        <hr>

                        <div class="alert alert-secondary">
                            <h6>🔗 Quick Links:</h6>
                            <a href="/login" class="btn btn-sm btn-outline-primary me-2">Main Login Page</a>
                            <a href="/admin/login" class="btn btn-sm btn-outline-success me-2">Direct Admin Login</a>
                            <a href="/test-admin-login-direct" class="btn btn-sm btn-outline-info">Test Admin Credentials</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
