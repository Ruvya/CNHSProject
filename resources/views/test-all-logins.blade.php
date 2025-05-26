<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test All Logins - CNHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4">Test All Login Types</h1>
            </div>
        </div>
        
        <div class="row">
            <!-- Admin Login Test -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5>Admin Login Test</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="role" value="admin">
                            
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" value="admin" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" value="admin123" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Login as Admin</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Teacher Login Test -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5>Teacher Login Test</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="role" value="teacher">
                            
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="teacher@example.com" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" value="teacher123" required>
                            </div>
                            
                            <button type="submit" class="btn btn-success">Login as Teacher</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Student Login Test -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5>Student Login Test</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="role" value="student">
                            
                            <div class="mb-3">
                                <label class="form-label">Student ID</label>
                                <input type="text" class="form-control" name="student_id" value="123456789" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" value="student123" required>
                            </div>
                            
                            <button type="submit" class="btn btn-info">Login as Student</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Registrar Login Test -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5>Registrar Login Test</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('registrar.login.post') }}">
                            @csrf
                            <input type="hidden" name="role" value="registrar">
                            
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="registrar@example.com" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" value="registrar123" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Registrar Secret Code</label>
                                <input type="text" class="form-control" name="registrar_secret" value="letmein" required>
                            </div>
                            
                            <button type="submit" class="btn btn-warning">Login as Registrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h5>Test Credentials:</h5>
                    <ul>
                        <li><strong>Admin:</strong> Username: admin, Password: admin123</li>
                        <li><strong>Teacher:</strong> Email: teacher@example.com, Password: teacher123</li>
                        <li><strong>Student:</strong> Student ID: 123456789, Password: student123</li>
                        <li><strong>Registrar:</strong> Email: registrar@example.com, Password: registrar123, Code: letmein</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12 text-center">
                <a href="{{ route('login') }}" class="btn btn-secondary">Go to Main Login Page</a>
            </div>
        </div>
    </div>
</body>
</html>
