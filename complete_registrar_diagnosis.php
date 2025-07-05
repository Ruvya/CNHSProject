<?php
// Complete registrar login diagnosis - checks ALL possible issues

echo "Content-Type: text/html\n\n";
echo "<!DOCTYPE html><html><head><title>Complete Registrar Diagnosis</title>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} .box{border:1px solid #ccc;padding:15px;margin:10px 0;} .critical{background:#f8d7da;border-color:#f5c6cb;} .good{background:#d4edda;border-color:#c3e6cb;}</style></head><body>";
echo "<h1>🔍 Complete Registrar Login Diagnosis</h1>";

try {
    // Bootstrap Laravel
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "<div class='box'><h2>1. Environment & Configuration Check</h2>";
    
    // Check environment
    echo "<p><strong>Environment:</strong> " . app()->environment() . "</p>";
    echo "<p><strong>Debug Mode:</strong> " . (config('app.debug') ? 'ON' : 'OFF') . "</p>";
    echo "<p><strong>App Key:</strong> " . (config('app.key') ? 'SET' : 'NOT SET') . "</p>";
    
    // Check session configuration
    echo "<p><strong>Session Driver:</strong> " . config('session.driver') . "</p>";
    echo "<p><strong>Session Lifetime:</strong> " . config('session.lifetime') . " minutes</p>";
    
    // Check database connection
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        echo "<p class='success'>✅ Database connection: WORKING</p>";
    } catch (Exception $e) {
        echo "<p class='error'>❌ Database connection: FAILED - " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    echo "<div class='box'><h2>2. Authentication Configuration Check</h2>";
    
    // Check auth guards
    $guards = config('auth.guards');
    echo "<p><strong>Registrar Guard:</strong> " . json_encode($guards['registrar'] ?? 'NOT FOUND') . "</p>";
    
    // Check auth providers
    $providers = config('auth.providers');
    echo "<p><strong>Registrar Provider:</strong> " . json_encode($providers['registrars'] ?? 'NOT FOUND') . "</p>";
    
    // Check if Registrar model exists
    try {
        $modelExists = class_exists(\App\Models\Registrar::class);
        echo "<p class='success'>✅ Registrar Model: EXISTS</p>";
    } catch (Exception $e) {
        echo "<p class='error'>❌ Registrar Model: MISSING</p>";
    }
    echo "</div>";

    echo "<div class='box'><h2>3. Database Table Check</h2>";
    
    // Check if registrars table exists
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('registrars');
    echo "<p>Registrars table exists: " . ($tableExists ? "<span class='success'>✅ YES</span>" : "<span class='error'>❌ NO</span>") . "</p>";
    
    if ($tableExists) {
        // Check table structure
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('registrars');
        echo "<p>Table columns: " . implode(', ', $columns) . "</p>";
        
        // Check registrar count
        $count = \Illuminate\Support\Facades\DB::table('registrars')->count();
        echo "<p>Registrar count: {$count}</p>";
        
        // Show existing registrars
        $registrars = \Illuminate\Support\Facades\DB::table('registrars')->get();
        if ($registrars->count() > 0) {
            echo "<p>Existing registrars:</p><ul>";
            foreach ($registrars as $reg) {
                echo "<li>ID: {$reg->id}, Email: {$reg->email}</li>";
            }
            echo "</ul>";
        }
    }
    echo "</div>";

    echo "<div class='box'><h2>4. Session Table Check</h2>";
    
    // Check if sessions table exists (needed for database sessions)
    $sessionTableExists = \Illuminate\Support\Facades\Schema::hasTable('sessions');
    echo "<p>Sessions table exists: " . ($sessionTableExists ? "<span class='success'>✅ YES</span>" : "<span class='error'>❌ NO</span>") . "</p>";
    
    if (!$sessionTableExists && config('session.driver') === 'database') {
        echo "<p class='error'>❌ CRITICAL: Session driver is 'database' but sessions table doesn't exist!</p>";
        echo "<p class='warning'>⚠️ This will cause login failures. Run: php artisan session:table && php artisan migrate</p>";
    }
    echo "</div>";

    echo "<div class='box'><h2>5. Create Fresh Registrar Account</h2>";
    
    // Delete all existing registrars
    \Illuminate\Support\Facades\DB::table('registrars')->truncate();
    echo "<p class='warning'>🗑️ Deleted all existing registrar accounts</p>";
    
    // Create new registrar using Eloquent
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

    echo "<div class='box'><h2>6. Password Verification Test</h2>";
    
    // Test password verification
    $freshRegistrar = \App\Models\Registrar::find($registrar->id);
    $laravelHashCheck = \Illuminate\Support\Facades\Hash::check('123456', $freshRegistrar->password);
    $phpPasswordCheck = password_verify('123456', $freshRegistrar->password);
    
    echo "<p>Laravel Hash::check: " . ($laravelHashCheck ? "<span class='success'>✅ PASS</span>" : "<span class='error'>❌ FAIL</span>") . "</p>";
    echo "<p>PHP password_verify: " . ($phpPasswordCheck ? "<span class='success'>✅ PASS</span>" : "<span class='error'>❌ FAIL</span>") . "</p>";
    echo "<p>Password hash: " . substr($freshRegistrar->password, 0, 60) . "</p>";
    echo "</div>";

    echo "<div class='box'><h2>7. Laravel Auth::attempt Test</h2>";
    
    // Clear any existing authentication
    \Illuminate\Support\Facades\Auth::guard('registrar')->logout();
    
    // Test Auth::attempt
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
            echo "<p class='success'>✅ Authentication system is working!</p>";
        } else {
            echo "<p class='error'>❌ Authentication failed - this is the root cause!</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Auth::attempt error: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    echo "<div class='box'><h2>8. Session Test</h2>";
    
    // Test session functionality
    try {
        session(['test_key' => 'test_value']);
        $sessionValue = session('test_key');
        echo "<p>Session test: " . ($sessionValue === 'test_value' ? "<span class='success'>✅ WORKING</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
        session()->forget('test_key');
    } catch (Exception $e) {
        echo "<p class='error'>❌ Session error: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    // Final diagnosis
    $allGood = $tableExists && ($laravelHashCheck || $phpPasswordCheck) && $authResult;
    
    if ($allGood) {
        echo "<div class='box good'>";
        echo "<h2 class='success'>✅ DIAGNOSIS: EVERYTHING SHOULD WORK!</h2>";
        echo "<p>All systems are functioning correctly. If you're still having login issues, try:</p>";
        echo "<ul>";
        echo "<li>Clear browser cache and cookies</li>";
        echo "<li>Use incognito/private browsing mode</li>";
        echo "<li>Check browser console for JavaScript errors</li>";
        echo "<li>Restart the Laravel development server</li>";
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div class='box critical'>";
        echo "<h2 class='error'>❌ DIAGNOSIS: ISSUES FOUND!</h2>";
        echo "<p>Problems detected that will prevent login:</p>";
        echo "<ul>";
        if (!$tableExists) echo "<li>Registrars table missing</li>";
        if (!$laravelHashCheck && !$phpPasswordCheck) echo "<li>Password verification failing</li>";
        if (!$authResult) echo "<li>Laravel authentication not working</li>";
        echo "</ul>";
        echo "</div>";
    }

    echo "<div class='box'>";
    echo "<h2>🔑 Login Credentials</h2>";
    echo "<p><strong>Email:</strong> registrar@cnhs.edu.ph</p>";
    echo "<p><strong>Password:</strong> 123456</p>";
    echo "<p><strong>Role:</strong> Select 'Registrar' from dropdown</p>";
    echo "<p><a href='/login' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Try Login Now</a></p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='box critical'><p class='error'>❌ Critical Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre></div>";
}

echo "</body></html>";
?>
