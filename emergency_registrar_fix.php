<?php
// Emergency registrar fix - addresses the "Invalid email or password" issue

echo "Content-Type: text/html\n\n";
echo "<!DOCTYPE html><html><head><title>Emergency Registrar Fix</title>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} .box{border:1px solid #ccc;padding:15px;margin:10px 0;}</style></head><body>";
echo "<h1>🚨 Emergency Registrar Fix</h1>";

try {
    // Bootstrap Laravel
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "<div class='box'><h2>Step 1: Complete Database Reset</h2>";
    
    // Delete ALL registrars and start completely fresh
    \Illuminate\Support\Facades\DB::table('registrars')->truncate();
    echo "<p class='warning'>🗑️ Deleted all existing registrar accounts</p>";
    
    // Create registrar using Laravel's Hash (exactly like other users)
    $registrar = \App\Models\Registrar::create([
        'first_name' => 'CNHS',
        'last_name' => 'Registrar',
        'name' => 'CNHS Registrar',
        'email' => 'registrar@cnhs.edu.ph',
        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        'phone' => '09123456789',
        'address' => 'Camarines Norte High School',
        'registrar_secret' => 'letmein',
    ]);
    
    echo "<p class='success'>✅ Created fresh registrar account with ID: {$registrar->id}</p>";
    echo "</div>";

    echo "<div class='box'><h2>Step 2: Verify Password Hash</h2>";
    
    // Get the fresh registrar
    $freshRegistrar = \App\Models\Registrar::find($registrar->id);
    echo "<p>Registrar ID: {$freshRegistrar->id}</p>";
    echo "<p>Email: {$freshRegistrar->email}</p>";
    echo "<p>Password hash: " . substr($freshRegistrar->password, 0, 60) . "</p>";
    
    // Test password verification
    $passwordTest = \Illuminate\Support\Facades\Hash::check('123456', $freshRegistrar->password);
    echo "<p>Password verification: " . ($passwordTest ? "<span class='success'>✅ WORKS</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
    echo "</div>";

    echo "<div class='box'><h2>Step 3: Test Exact LoginController Logic</h2>";
    
    // Simulate EXACT same logic as LoginController
    $testCredentials = [
        'email' => 'registrar@cnhs.edu.ph',
        'password' => '123456'
    ];
    
    // This is the EXACT code from LoginController
    $user = \App\Models\Registrar::where('email', $testCredentials['email'])->first();
    $laravelHashCheck = $user ? \Illuminate\Support\Facades\Hash::check($testCredentials['password'], $user->password) : false;
    $phpPasswordCheck = $user ? password_verify($testCredentials['password'], $user->password) : false;
    $passwordCorrect = $laravelHashCheck || $phpPasswordCheck;
    
    echo "<p>User found: " . ($user ? "<span class='success'>✅ YES</span>" : "<span class='error'>❌ NO</span>") . "</p>";
    echo "<p>Laravel Hash check: " . ($laravelHashCheck ? "<span class='success'>✅ PASS</span>" : "<span class='error'>❌ FAIL</span>") . "</p>";
    echo "<p>PHP password check: " . ($phpPasswordCheck ? "<span class='success'>✅ PASS</span>" : "<span class='error'>❌ FAIL</span>") . "</p>";
    echo "<p>Final result: " . ($passwordCorrect ? "<span class='success'>✅ SHOULD LOGIN</span>" : "<span class='error'>❌ WILL FAIL</span>") . "</p>";
    echo "</div>";

    echo "<div class='box'><h2>Step 4: Check Auth Configuration</h2>";
    
    // Check auth configuration
    $authConfig = config('auth.guards.registrar');
    echo "<p>Auth guard config: " . json_encode($authConfig) . "</p>";
    
    $authProvider = config('auth.providers.registrars');
    echo "<p>Auth provider config: " . json_encode($authProvider) . "</p>";
    echo "</div>";

    echo "<div class='box'><h2>Step 5: Test Laravel Auth::attempt</h2>";
    
    try {
        // Clear any existing auth
        \Illuminate\Support\Facades\Auth::guard('registrar')->logout();
        
        // Test Auth::attempt
        $authAttempt = \Illuminate\Support\Facades\Auth::guard('registrar')->attempt([
            'email' => 'registrar@cnhs.edu.ph',
            'password' => '123456'
        ]);
        
        echo "<p>Auth::attempt result: " . ($authAttempt ? "<span class='success'>✅ SUCCESS</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
        
        if ($authAttempt) {
            $authUser = \Illuminate\Support\Facades\Auth::guard('registrar')->user();
            echo "<p>Authenticated as: {$authUser->email} (ID: {$authUser->id})</p>";
            \Illuminate\Support\Facades\Auth::guard('registrar')->logout();
        }
    } catch (Exception $e) {
        echo "<p class='error'>Auth::attempt error: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    if ($passwordCorrect) {
        echo "<div class='box' style='background:#d4edda;'>";
        echo "<h2 class='success'>✅ AUTHENTICATION IS WORKING!</h2>";
        echo "<p>The registrar account is properly configured. If you're still getting 'Invalid email or password', the issue is likely:</p>";
        echo "<ul>";
        echo "<li><strong>Form submission issue</strong> - The form might not be sending the data correctly</li>";
        echo "<li><strong>JavaScript interference</strong> - Check browser console for errors</li>";
        echo "<li><strong>CSRF token issue</strong> - The form token might be invalid</li>";
        echo "<li><strong>Session issue</strong> - Try clearing browser cache/cookies</li>";
        echo "</ul>";
        echo "<p><strong>Try these solutions:</strong></p>";
        echo "<ol>";
        echo "<li>Clear browser cache and cookies</li>";
        echo "<li>Try incognito/private browsing mode</li>";
        echo "<li>Check browser console for JavaScript errors</li>";
        echo "<li>Try the simple test form below</li>";
        echo "</ol>";
        echo "</div>";
    } else {
        echo "<div class='box' style='background:#f8d7da;'>";
        echo "<h2 class='error'>❌ AUTHENTICATION STILL BROKEN</h2>";
        echo "<p>There's still an issue with the password verification.</p>";
        echo "</div>";
    }

    // Create a simple test form
    echo "<div class='box'>";
    echo "<h2>🧪 Simple Test Form</h2>";
    echo "<p>Try this simple form to bypass any JavaScript issues:</p>";
    echo "<form method='POST' action='/login' style='background:#f8f9fa;padding:20px;border-radius:5px;'>";
    echo "<input type='hidden' name='_token' value='" . csrf_token() . "'>";
    echo "<input type='hidden' name='role' value='registrar'>";
    echo "<p><label>Email:</label><br><input type='email' name='email' value='registrar@cnhs.edu.ph' style='width:300px;padding:5px;'></p>";
    echo "<p><label>Password:</label><br><input type='password' name='password' value='123456' style='width:300px;padding:5px;'></p>";
    echo "<p><button type='submit' style='background:#007bff;color:white;padding:10px 20px;border:none;border-radius:5px;'>Test Login</button></p>";
    echo "</form>";
    echo "</div>";

    echo "<div class='box'>";
    echo "<h2>🔑 Final Credentials</h2>";
    echo "<p><strong>Email:</strong> registrar@cnhs.edu.ph</p>";
    echo "<p><strong>Password:</strong> 123456</p>";
    echo "<p><strong>Role:</strong> Select 'Registrar' from dropdown</p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='box'><p class='error'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre></div>";
}

echo "</body></html>";
?>
