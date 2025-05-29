<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Student Dashboard...</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .redirect-container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 4px solid white;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .manual-link {
            margin-top: 1rem;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        .manual-link:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>
    <div class="redirect-container">
        <div class="spinner"></div>
        <h2>Login Successful!</h2>
        <p>Welcome, {{ $student->first_name ?? 'Student' }}!</p>
        <p>Redirecting to your dashboard...</p>
        
        <div id="countdown" style="margin: 1rem 0;">
            Redirecting in <span id="timer">3</span> seconds...
        </div>
        
        <a href="{{ $dashboardUrl }}" class="manual-link" id="manualLink">
            Click here if not redirected automatically
        </a>
    </div>

    <script>
        console.log('Student login successful, redirecting to:', '{{ $dashboardUrl }}');
        
        // Multiple redirect methods to ensure it works
        let redirected = false;
        
        // Method 1: Immediate redirect
        setTimeout(function() {
            if (!redirected) {
                console.log('Method 1: window.location.href redirect');
                redirected = true;
                window.location.href = '{{ $dashboardUrl }}';
            }
        }, 1000);
        
        // Method 2: Replace location
        setTimeout(function() {
            if (!redirected) {
                console.log('Method 2: window.location.replace redirect');
                redirected = true;
                window.location.replace('{{ $dashboardUrl }}');
            }
        }, 2000);
        
        // Method 3: Force redirect
        setTimeout(function() {
            if (!redirected) {
                console.log('Method 3: Force redirect');
                redirected = true;
                window.top.location = '{{ $dashboardUrl }}';
            }
        }, 3000);
        
        // Countdown timer
        let timeLeft = 3;
        const timer = document.getElementById('timer');
        const countdown = setInterval(function() {
            timeLeft--;
            timer.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(countdown);
                document.getElementById('countdown').textContent = 'Redirecting now...';
            }
        }, 1000);
        
        // Manual link click handler
        document.getElementById('manualLink').addEventListener('click', function(e) {
            console.log('Manual link clicked');
            redirected = true;
        });
        
        // Fallback: if page is still visible after 5 seconds, show error
        setTimeout(function() {
            if (!redirected) {
                document.querySelector('.redirect-container').innerHTML = `
                    <h2>Redirect Failed</h2>
                    <p>Please click the link below to access your dashboard:</p>
                    <a href="{{ $dashboardUrl }}" class="manual-link" style="font-size: 1.2em; padding: 15px 30px;">
                        Go to Student Dashboard
                    </a>
                `;
            }
        }, 5000);
    </script>
</body>
</html>
