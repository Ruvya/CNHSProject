<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Admin Login Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Simple Admin Login Test</h4>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

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

                        <form method="POST" action="{{ route('login') }}" id="simpleAdminForm">
                            @csrf
                            
                            <input type="hidden" name="role" value="admin">
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Admin Username</label>
                                <input type="text" class="form-control" name="username" id="username" value="admin" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" value="admin123" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100" id="loginBtn">Login as Admin</button>
                        </form>
                        
                        <hr>
                        
                        <div class="mt-3">
                            <h6>Debug Info:</h6>
                            <p><strong>Form Action:</strong> <span id="formAction">{{ route('login') }}</span></p>
                            <p><strong>CSRF Token:</strong> {{ csrf_token() }}</p>
                        </div>
                        
                        <div class="mt-3">
                            <h6>Test Results:</h6>
                            <div id="testResults" class="alert alert-info">
                                Click the login button to test...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('simpleAdminForm');
            const loginBtn = document.getElementById('loginBtn');
            const testResults = document.getElementById('testResults');
            
            // Update form action display
            document.getElementById('formAction').textContent = form.action;
            
            // Add form submit listener
            form.addEventListener('submit', function(e) {
                console.log('Form submitted!');
                console.log('Form action:', this.action);
                console.log('Form data:', new FormData(this));
                
                testResults.innerHTML = `
                    <strong>Form Submission Detected!</strong><br>
                    Action: ${this.action}<br>
                    Method: ${this.method}<br>
                    Role: ${this.querySelector('[name="role"]').value}<br>
                    Username: ${this.querySelector('[name="username"]').value}<br>
                    Password: ${this.querySelector('[name="password"]').value ? '[PROVIDED]' : '[MISSING]'}
                `;
                testResults.className = 'alert alert-success';
            });
            
            // Add button click listener
            loginBtn.addEventListener('click', function(e) {
                console.log('Login button clicked!');
                testResults.innerHTML = '<strong>Button clicked!</strong> Form should submit...';
                testResults.className = 'alert alert-warning';
            });
        });
    </script>
</body>
</html>
