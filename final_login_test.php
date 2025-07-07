<?php
// Final comprehensive login test

echo "Content-Type: text/html\n\n";
echo "<!DOCTYPE html><html><head><title>Final Login Test</title>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .info{color:blue;} .box{border:1px solid #ccc;padding:15px;margin:10px 0;}</style></head><body>";
echo "<h1>🧪 Final Registrar Login Test</h1>";

try {
    // Bootstrap Laravel
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "<div class='box'><h2>Step 1: Database Check</h2>";
    
    // Check registrar in database
    $registrar = \App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
    
    if ($registrar) {
        echo "<p class='success'>✅ Registrar found in database</p>";
        echo "<p>ID: {$registrar->id}</p>";
        echo "<p>Email: {$registrar->email}</p>";
        echo "<p>Name: " . ($registrar->name ?? $registrar->first_name . ' ' . $registrar->last_name) . "</p>";
        echo "<p>Password hash: " . substr($registrar->password, 0, 30) . "...</p>";
    } else {
        echo "<p class='error'>❌ No registrar found in database</p>";
        echo "</div></body></html>";
        exit;
    }
    echo "</div>";

    echo "<div class='box'><h2>Step 2: Password Verification Test</h2>";
    
    $testPassword = '123456';
    
    // Test Laravel Hash
    $laravelCheck = \Illuminate\Support\Facades\Hash::check($testPassword, $registrar->password);
    echo "<p>Laravel Hash::check: " . ($laravelCheck ? "<span class='success'>✅ PASS</span>" : "<span class='error'>❌ FAIL</span>") . "</p>";
    
    // Test PHP password_verify
    $phpCheck = password_verify($testPassword, $registrar->password);
    echo "<p>PHP password_verify: " . ($phpCheck ? "<span class='success'>✅ PASS</span>" : "<span class='error'>❌ FAIL</span>") . "</p>";
    
    $passwordWorks = $laravelCheck || $phpCheck;
    echo "<p>Overall password check: " . ($passwordWorks ? "<span class='success'>✅ WORKS</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
    echo "</div>";

    echo "<div class='box'><h2>Step 3: Laravel Auth Test</h2>";
    
    // Test Laravel authentication
    try {
        $authResult = \Illuminate\Support\Facades\Auth::guard('registrar')->attempt([
            'email' => 'registrar@cnhs.edu.ph',
            'password' => '123456'
        ]);
        
        echo "<p>Auth::attempt result: " . ($authResult ? "<span class='success'>✅ SUCCESS</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
        
        if ($authResult) {
            $authUser = \Illuminate\Support\Facades\Auth::guard('registrar')->user();
            echo "<p>Authenticated user: {$authUser->email} (ID: {$authUser->id})</p>";
            \Illuminate\Support\Facades\Auth::guard('registrar')->logout();
            echo "<p class='info'>Logged out after test</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>Auth error: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    echo "<div class='box'><h2>Step 4: Manual Login Simulation</h2>";
    
    // Simulate the exact same logic as LoginController
    $credentials = [
        'email' => 'registrar@cnhs.edu.ph',
        'password' => '123456',
        'role' => 'registrar'
    ];
    
    $user = \App\Models\Registrar::where('email', $credentials['email'])->first();
    $laravelHashCheck = $user ? \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password) : false;
    $phpPasswordCheck = $user ? password_verify($credentials['password'], $user->password) : false;
    $passwordCorrect = $laravelHashCheck || $phpPasswordCheck;
    
    echo "<p>User lookup: " . ($user ? "<span class='success'>✅ Found</span>" : "<span class='error'>❌ Not found</span>") . "</p>";
    echo "<p>Laravel hash check: " . ($laravelHashCheck ? "<span class='success'>✅ Pass</span>" : "<span class='error'>❌ Fail</span>") . "</p>";
    echo "<p>PHP password check: " . ($phpPasswordCheck ? "<span class='success'>✅ Pass</span>" : "<span class='error'>❌ Fail</span>") . "</p>";
    echo "<p>Final result: " . ($passwordCorrect ? "<span class='success'>✅ Should login successfully</span>" : "<span class='error'>❌ Login will fail</span>") . "</p>";
    echo "</div>";

    if ($passwordWorks && $user) {
        echo "<div class='box' style='background:#d4edda; border-color:#c3e6cb;'>";
        echo "<h2 class='success'>✅ LOGIN SHOULD WORK!</h2>";
        echo "<p><strong>The registrar account is properly configured and authentication is working.</strong></p>";
        echo "<p>If you're still having issues, it might be:</p>";
        echo "<ul>";
        echo "<li>Browser cache - try clearing cache or incognito mode</li>";
        echo "<li>Session issues - try restarting the server</li>";
        echo "<li>Form submission issues - check browser console for JavaScript errors</li>";
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div class='box' style='background:#f8d7da; border-color:#f5c6cb;'>";
        echo "<h2 class='error'>❌ LOGIN WILL NOT WORK</h2>";
        echo "<p>There's still an issue with the registrar account or password.</p>";
        echo "</div>";
    }

    echo "<div class='box'><h2>🔑 Final Credentials</h2>";
    echo "<p><strong>Email:</strong> registrar@cnhs.edu.ph</p>";
    echo "<p><strong>Password:</strong> 123456</p>";
    echo "<p><strong>Role:</strong> Select 'Registrar' from dropdown</p>";
    echo "<p><a href='/login' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Try Login Now</a></p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='box'><p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre></div>";
}

echo "</body></html>";
?>
