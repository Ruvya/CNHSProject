<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Login - CNHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Test Admin Login</h4>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <input type="hidden" name="role" value="admin">

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" value="admin" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" value="admin123" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Login via Auth Controller</button>
                        </form>

                        <hr>

                        <form method="POST" action="/debug-admin-login" id="debugForm">
                            @csrf

                            <div class="mb-3">
                                <label for="debug_username" class="form-label">Debug Username</label>
                                <input type="text" class="form-control" name="username" id="debug_username" value="admin" required>
                            </div>

                            <div class="mb-3">
                                <label for="debug_password" class="form-label">Debug Password</label>
                                <input type="password" class="form-control" name="password" id="debug_password" value="admin123" required>
                            </div>

                            <button type="submit" class="btn btn-secondary">Debug Login Test</button>
                        </form>

                        <div id="debugResult" class="mt-3"></div>

                        <hr>

                        <h5>Debug Info:</h5>
                        <p><strong>Route:</strong> {{ route('login') }}</p>
                        <p><strong>Method:</strong> POST</p>
                        <p><strong>CSRF Token:</strong> {{ csrf_token() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('debugForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const debugResult = document.getElementById('debugResult');

            debugResult.innerHTML = '<div class="alert alert-info">Testing...</div>';

            fetch('/debug-admin-login', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    debugResult.innerHTML = '<div class="alert alert-success">' + data.success + ' - Admin: ' + data.admin + '</div>';
                } else {
                    debugResult.innerHTML = '<div class="alert alert-danger">' + data.error + '</div>';
                }
            })
            .catch(error => {
                debugResult.innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
            });
        });
    </script>
</body>
</html>
