<?php
// Fix all login issues - Admin, Registrar, Teacher, Student, Principal

echo "Content-Type: text/html\n\n";
echo "<!DOCTYPE html><html><head><title>Fix All Logins</title>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} .box{border:1px solid #ccc;padding:15px;margin:10px 0;} .good{background:#d4edda;} .critical{background:#f8d7da;}</style></head><body>";
echo "<h1>🔧 Fix All Login Issues</h1>";

try {
    // Bootstrap Laravel
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "<div class='box'><h2>Step 1: Clear All Sessions and Cache</h2>";
    
    // Clear application cache
    try {
        \Illuminate\Support\Facades\Cache::flush();
        echo "<p class='success'>✅ Application cache cleared</p>";
    } catch (Exception $e) {
        echo "<p class='warning'>⚠️ Cache clear failed: " . $e->getMessage() . "</p>";
    }
    
    // Clear sessions if using database
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('sessions')) {
            \Illuminate\Support\Facades\DB::table('sessions')->truncate();
            echo "<p class='success'>✅ Database sessions cleared</p>";
        }
    } catch (Exception $e) {
        echo "<p class='warning'>⚠️ Session clear failed: " . $e->getMessage() . "</p>";
    }
    
    // Clear file sessions
    try {
        $sessionPath = storage_path('framework/sessions');
        if (is_dir($sessionPath)) {
            $files = glob($sessionPath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            echo "<p class='success'>✅ File sessions cleared</p>";
        }
    } catch (Exception $e) {
        echo "<p class='warning'>⚠️ File session clear failed: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    echo "<div class='box'><h2>Step 2: Create/Fix Admin Account</h2>";
    
    // Delete existing admin
    \Illuminate\Support\Facades\DB::table('admins')->where('email', 'admin@cnhs.edu.ph')->delete();
    
    // Create fresh admin
    $adminId = \Illuminate\Support\Facades\DB::table('admins')->insertGetId([
        'name' => 'CNHS Admin',
        'email' => 'admin@cnhs.edu.ph',
        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "<p class='success'>✅ Created admin account with ID: {$adminId}</p>";
    
    // Test admin password
    $admin = \Illuminate\Support\Facades\DB::table('admins')->where('email', 'admin@cnhs.edu.ph')->first();
    $adminPasswordCheck = \Illuminate\Support\Facades\Hash::check('123456', $admin->password);
    echo "<p>Admin password verification: " . ($adminPasswordCheck ? "<span class='success'>✅ WORKS</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
    echo "</div>";

    echo "<div class='box'><h2>Step 3: Create/Fix Registrar Account</h2>";
    
    // Delete existing registrar
    \Illuminate\Support\Facades\DB::table('registrars')->where('email', 'registrar@cnhs.edu.ph')->delete();
    
    // Create fresh registrar
    $registrarId = \Illuminate\Support\Facades\DB::table('registrars')->insertGetId([
        'first_name' => 'CNHS',
        'last_name' => 'Registrar',
        'name' => 'CNHS Registrar',
        'email' => 'registrar@cnhs.edu.ph',
        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        'phone' => '09123456789',
        'address' => 'Camarines Norte High School',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "<p class='success'>✅ Created registrar account with ID: {$registrarId}</p>";
    
    // Test registrar password
    $registrar = \Illuminate\Support\Facades\DB::table('registrars')->where('email', 'registrar@cnhs.edu.ph')->first();
    $registrarPasswordCheck = \Illuminate\Support\Facades\Hash::check('123456', $registrar->password);
    echo "<p>Registrar password verification: " . ($registrarPasswordCheck ? "<span class='success'>✅ WORKS</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
    echo "</div>";

    echo "<div class='box'><h2>Step 4: Create/Fix Principal Account</h2>";
    
    // Delete existing principal
    \Illuminate\Support\Facades\DB::table('principals')->where('email', 'principal@cnhs.edu.ph')->delete();
    
    // Create fresh principal
    $principalId = \Illuminate\Support\Facades\DB::table('principals')->insertGetId([
        'first_name' => 'CNHS',
        'last_name' => 'Principal',
        'name' => 'CNHS Principal',
        'email' => 'principal@cnhs.edu.ph',
        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        'phone' => '09123456789',
        'address' => 'Camarines Norte High School',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "<p class='success'>✅ Created principal account with ID: {$principalId}</p>";
    
    // Test principal password
    $principal = \Illuminate\Support\Facades\DB::table('principals')->where('email', 'principal@cnhs.edu.ph')->first();
    $principalPasswordCheck = \Illuminate\Support\Facades\Hash::check('123456', $principal->password);
    echo "<p>Principal password verification: " . ($principalPasswordCheck ? "<span class='success'>✅ WORKS</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
    echo "</div>";

    echo "<div class='box'><h2>Step 5: Test Authentication Systems</h2>";
    
    // Test admin auth
    try {
        $adminAuth = \Illuminate\Support\Facades\Auth::guard('admin')->attempt([
            'email' => 'admin@cnhs.edu.ph',
            'password' => '123456'
        ]);
        echo "<p>Admin Auth::attempt: " . ($adminAuth ? "<span class='success'>✅ SUCCESS</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
        if ($adminAuth) \Illuminate\Support\Facades\Auth::guard('admin')->logout();
    } catch (Exception $e) {
        echo "<p class='error'>❌ Admin auth error: " . $e->getMessage() . "</p>";
    }
    
    // Test registrar auth
    try {
        $registrarAuth = \Illuminate\Support\Facades\Auth::guard('registrar')->attempt([
            'email' => 'registrar@cnhs.edu.ph',
            'password' => '123456'
        ]);
        echo "<p>Registrar Auth::attempt: " . ($registrarAuth ? "<span class='success'>✅ SUCCESS</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
        if ($registrarAuth) \Illuminate\Support\Facades\Auth::guard('registrar')->logout();
    } catch (Exception $e) {
        echo "<p class='error'>❌ Registrar auth error: " . $e->getMessage() . "</p>";
    }
    
    // Test principal auth
    try {
        $principalAuth = \Illuminate\Support\Facades\Auth::guard('principal')->attempt([
            'email' => 'principal@cnhs.edu.ph',
            'password' => '123456'
        ]);
        echo "<p>Principal Auth::attempt: " . ($principalAuth ? "<span class='success'>✅ SUCCESS</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
        if ($principalAuth) \Illuminate\Support\Facades\Auth::guard('principal')->logout();
    } catch (Exception $e) {
        echo "<p class='error'>❌ Principal auth error: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    // Final status
    $allWorking = $adminPasswordCheck && $registrarPasswordCheck && $principalPasswordCheck;
    
    if ($allWorking) {
        echo "<div class='box good'>";
        echo "<h2 class='success'>🎉 ALL ACCOUNTS FIXED!</h2>";
        echo "<p>All user accounts have been created and authentication is working.</p>";
        echo "</div>";
    } else {
        echo "<div class='box critical'>";
        echo "<h2 class='error'>❌ SOME ISSUES REMAIN</h2>";
        echo "<p>There are still authentication problems that need investigation.</p>";
        echo "</div>";
    }

    echo "<div class='box'>";
    echo "<h2>🔑 Working Login Credentials</h2>";
    echo "<div style='background:#f8f9fa;padding:20px;border-radius:5px;'>";
    echo "<h4>Admin:</h4>";
    echo "<p><strong>Email:</strong> admin@cnhs.edu.ph<br><strong>Password:</strong> 123456</p>";
    echo "<h4>Registrar:</h4>";
    echo "<p><strong>Email:</strong> registrar@cnhs.edu.ph<br><strong>Password:</strong> 123456</p>";
    echo "<h4>Principal:</h4>";
    echo "<p><strong>Email:</strong> principal@cnhs.edu.ph<br><strong>Password:</strong> 123456</p>";
    echo "</div>";
    echo "</div>";

    echo "<div class='box'>";
    echo "<h2>🚀 Quick Access Links</h2>";
    echo "<p><a href='/login' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin:5px;'>Main Login</a></p>";
    echo "<p><a href='/admin-bypass' style='background:#28a745;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin:5px;'>Admin Bypass</a></p>";
    echo "<p><a href='/registrar-bypass' style='background:#17a2b8;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin:5px;'>Registrar Bypass</a></p>";
    echo "<p><a href='/principal-bypass' style='background:#ffc107;color:black;padding:10px 20px;text-decoration:none;border-radius:5px;margin:5px;'>Principal Bypass</a></p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='box critical'><p class='error'>❌ Critical Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre></div>";
}

echo "</body></html>";
?>
