<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Admin Login Test</div>
                    <div class="card-body">
                        <form id="directLoginForm">
                            @csrf
                            <div class="mb-3">
                                <label for="test-username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="test-username" value="Admin">
                            </div>
                            <div class="mb-3">
                                <label for="test-password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="test-password" value="admin123">
                            </div>
                            <button type="button" class="btn btn-primary" id="testLoginBtn">Test Login</button>
                        </form>
                        
                        <div class="mt-4">
                            <h5>Results:</h5>
                            <pre id="results" class="bg-light p-3">No results yet</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('testLoginBtn').addEventListener('click', function() {
            const username = document.getElementById('test-username').value;
            const password = document.getElementById('test-password').value;
            const csrfToken = document.querySelector('input[name="_token"]').value;
            
            document.getElementById('results').textContent = 'Testing login...';
            
            fetch('/admin/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    username: username,
                    password: password
                })
            })
            .then(response => {
                document.getElementById('results').textContent += '\nStatus: ' + response.status;
                return response.json();
            })
            .then(data => {
                document.getElementById('results').textContent += '\nResponse: ' + JSON.stringify(data, null, 2);
                if (data.success && data.redirect) {
                    document.getElementById('results').textContent += '\nRedirecting to: ' + data.redirect;
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                }
            })
            .catch(error => {
                document.getElementById('results').textContent += '\nError: ' + error;
            });
        });
    </script>
</body>
</html>
