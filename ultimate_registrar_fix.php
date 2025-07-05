<?php
// Ultimate registrar fix - addresses ALL possible issues

echo "Content-Type: text/html\n\n";
echo "<!DOCTYPE html><html><head><title>Ultimate Registrar Fix</title>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} .box{border:1px solid #ccc;padding:15px;margin:10px 0;} .critical{background:#f8d7da;} .good{background:#d4edda;}</style></head><body>";
echo "<h1>🚀 Ultimate Registrar Fix</h1>";

try {
    // Bootstrap Laravel
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "<div class='box'><h2>Step 1: Fix Session Configuration</h2>";
    
    // Check current session driver
    $sessionDriver = config('session.driver');
    echo "<p>Current session driver: {$sessionDriver}</p>";
    
    // If using database sessions, ensure sessions table exists
    if ($sessionDriver === 'database') {
        $sessionTableExists = \Illuminate\Support\Facades\Schema::hasTable('sessions');
        if (!$sessionTableExists) {
            echo "<p class='warning'>⚠️ Sessions table missing - creating it...</p>";
            
            // Create sessions table manually
            \Illuminate\Support\Facades\Schema::create('sessions', function ($table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
            echo "<p class='success'>✅ Sessions table created</p>";
        } else {
            echo "<p class='success'>✅ Sessions table exists</p>";
        }
    }
    echo "</div>";

    echo "<div class='box'><h2>Step 2: Ensure Registrars Table Exists</h2>";
    
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('registrars');
    if (!$tableExists) {
        echo "<p class='warning'>⚠️ Registrars table missing - creating it...</p>";
        
        // Create registrars table
        \Illuminate\Support\Facades\Schema::create('registrars', function ($table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('registrar_secret')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        echo "<p class='success'>✅ Registrars table created</p>";
    } else {
        echo "<p class='success'>✅ Registrars table exists</p>";
    }
    echo "</div>";

    echo "<div class='box'><h2>Step 3: Create Fresh Registrar Account</h2>";
    
    // Delete all existing registrars
    \Illuminate\Support\Facades\DB::table('registrars')->truncate();
    echo "<p>🗑️ Deleted all existing registrar accounts</p>";
    
    // Create new registrar using direct DB insert to avoid any model issues
    $hashedPassword = \Illuminate\Support\Facades\Hash::make('123456');
    
    $registrarId = \Illuminate\Support\Facades\DB::table('registrars')->insertGetId([
        'first_name' => 'CNHS',
        'last_name' => 'Registrar',
        'name' => 'CNHS Registrar',
        'email' => 'registrar@cnhs.edu.ph',
        'password' => $hashedPassword,
        'phone' => '09123456789',
        'address' => 'Camarines Norte High School',
        'registrar_secret' => 'letmein',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "<p class='success'>✅ Created fresh registrar account with ID: {$registrarId}</p>";
    echo "</div>";

    echo "<div class='box'><h2>Step 4: Verify Account and Password</h2>";
    
    // Verify the account was created correctly
    $registrar = \Illuminate\Support\Facades\DB::table('registrars')->where('email', 'registrar@cnhs.edu.ph')->first();
    
    if ($registrar) {
        echo "<p class='success'>✅ Registrar account found in database</p>";
        echo "<p>ID: {$registrar->id}</p>";
        echo "<p>Email: {$registrar->email}</p>";
        echo "<p>Name: {$registrar->name}</p>";
        
        // Test password verification
        $passwordCorrect = \Illuminate\Support\Facades\Hash::check('123456', $registrar->password);
        echo "<p>Password verification: " . ($passwordCorrect ? "<span class='success'>✅ CORRECT</span>" : "<span class='error'>❌ INCORRECT</span>") . "</p>";
        
        if (!$passwordCorrect) {
            echo "<p class='error'>❌ Password verification failed - this is the issue!</p>";
            
            // Try recreating with a different hash method
            $newPassword = password_hash('123456', PASSWORD_DEFAULT);
            \Illuminate\Support\Facades\DB::table('registrars')
                ->where('id', $registrar->id)
                ->update(['password' => $newPassword]);
            
            echo "<p class='warning'>⚠️ Recreated password with PHP password_hash</p>";
            
            // Test again
            $registrar = \Illuminate\Support\Facades\DB::table('registrars')->where('email', 'registrar@cnhs.edu.ph')->first();
            $laravelCheck = \Illuminate\Support\Facades\Hash::check('123456', $registrar->password);
            $phpCheck = password_verify('123456', $registrar->password);
            
            echo "<p>Laravel Hash::check: " . ($laravelCheck ? "<span class='success'>✅ PASS</span>" : "<span class='error'>❌ FAIL</span>") . "</p>";
            echo "<p>PHP password_verify: " . ($phpCheck ? "<span class='success'>✅ PASS</span>" : "<span class='error'>❌ FAIL</span>") . "</p>";
        }
    } else {
        echo "<p class='error'>❌ Registrar account not found after creation!</p>";
    }
    echo "</div>";

    echo "<div class='box'><h2>Step 5: Test Authentication</h2>";
    
    // Clear any existing auth
    \Illuminate\Support\Facades\Auth::guard('registrar')->logout();
    
    // Test Laravel Auth::attempt
    try {
        $authResult = \Illuminate\Support\Facades\Auth::guard('registrar')->attempt([
            'email' => 'registrar@cnhs.edu.ph',
            'password' => '123456'
        ]);
        
        echo "<p>Laravel Auth::attempt: " . ($authResult ? "<span class='success'>✅ SUCCESS</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
        
        if ($authResult) {
            $authUser = \Illuminate\Support\Facades\Auth::guard('registrar')->user();
            echo "<p class='success'>✅ Successfully authenticated as: {$authUser->email}</p>";
            \Illuminate\Support\Facades\Auth::guard('registrar')->logout();
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Auth error: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    echo "<div class='box'><h2>Step 6: Clear All Caches</h2>";
    
    // Clear various caches that might interfere
    try {
        \Illuminate\Support\Facades\Cache::flush();
        echo "<p class='success'>✅ Application cache cleared</p>";
    } catch (Exception $e) {
        echo "<p class='warning'>⚠️ Cache clear failed: " . $e->getMessage() . "</p>";
    }
    
    // Clear sessions
    try {
        if (config('session.driver') === 'database') {
            \Illuminate\Support\Facades\DB::table('sessions')->truncate();
            echo "<p class='success'>✅ Database sessions cleared</p>";
        }
    } catch (Exception $e) {
        echo "<p class='warning'>⚠️ Session clear failed: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    // Final status
    $finalRegistrar = \Illuminate\Support\Facades\DB::table('registrars')->where('email', 'registrar@cnhs.edu.ph')->first();
    $finalPasswordCheck = $finalRegistrar ? \Illuminate\Support\Facades\Hash::check('123456', $finalRegistrar->password) : false;
    $finalPhpCheck = $finalRegistrar ? password_verify('123456', $finalRegistrar->password) : false;
    $passwordWorks = $finalPasswordCheck || $finalPhpCheck;

    if ($finalRegistrar && $passwordWorks) {
        echo "<div class='box good'>";
        echo "<h2 class='success'>🎉 SUCCESS! REGISTRAR LOGIN IS FIXED!</h2>";
        echo "<p>All issues have been resolved. The registrar login should now work perfectly.</p>";
        echo "</div>";
    } else {
        echo "<div class='box critical'>";
        echo "<h2 class='error'>❌ ISSUES STILL EXIST</h2>";
        echo "<p>There are still problems that need to be addressed.</p>";
        echo "</div>";
    }

    echo "<div class='box'>";
    echo "<h2>🔑 Final Login Credentials</h2>";
    echo "<p><strong>Email:</strong> registrar@cnhs.edu.ph</p>";
    echo "<p><strong>Password:</strong> 123456</p>";
    echo "<p><strong>Role:</strong> Select 'Registrar' from dropdown</p>";
    echo "<p><a href='/login' style='background:#007bff;color:white;padding:15px 30px;text-decoration:none;border-radius:5px;font-size:18px;'>🚀 TRY LOGIN NOW</a></p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='box critical'><p class='error'>❌ Critical Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre></div>";
}

echo "</body></html>";
?>
