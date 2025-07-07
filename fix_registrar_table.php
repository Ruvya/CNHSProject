<?php
// Fix registrar table structure - add missing columns

echo "Content-Type: text/html\n\n";
echo "<!DOCTYPE html><html><head><title>Fix Registrar Table</title>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} .box{border:1px solid #ccc;padding:15px;margin:10px 0;}</style></head><body>";
echo "<h1>🔧 Fix Registrar Table Structure</h1>";

try {
    // Bootstrap Laravel
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "<div class='box'><h2>Step 1: Check Current Table Structure</h2>";
    
    // Check if registrars table exists
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('registrars');
    echo "<p>Registrars table exists: " . ($tableExists ? "<span class='success'>✅ YES</span>" : "<span class='error'>❌ NO</span>") . "</p>";
    
    if ($tableExists) {
        // Get current columns
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('registrars');
        echo "<p>Current columns: " . implode(', ', $columns) . "</p>";
        
        // Check if 'name' column exists
        $hasNameColumn = in_array('name', $columns);
        echo "<p>'name' column exists: " . ($hasNameColumn ? "<span class='success'>✅ YES</span>" : "<span class='error'>❌ NO</span>") . "</p>";
        
        // Check if 'registrar_secret' column exists
        $hasSecretColumn = in_array('registrar_secret', $columns);
        echo "<p>'registrar_secret' column exists: " . ($hasSecretColumn ? "<span class='success'>✅ YES</span>" : "<span class='error'>❌ NO</span>") . "</p>";
        
        if (!$hasNameColumn || !$hasSecretColumn) {
            echo "<p class='warning'>⚠️ Missing columns detected. Adding them...</p>";
            
            // Add missing columns
            \Illuminate\Support\Facades\Schema::table('registrars', function ($table) use ($hasNameColumn, $hasSecretColumn) {
                if (!$hasNameColumn) {
                    $table->string('name')->nullable()->after('last_name');
                }
                if (!$hasSecretColumn) {
                    $table->string('registrar_secret')->nullable();
                }
            });
            
            echo "<p class='success'>✅ Missing columns added successfully</p>";
        } else {
            echo "<p class='success'>✅ All required columns exist</p>";
        }
    } else {
        echo "<p class='error'>❌ Registrars table doesn't exist. Creating it...</p>";
        
        // Create registrars table with all required columns
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
        
        echo "<p class='success'>✅ Registrars table created with all required columns</p>";
    }
    echo "</div>";

    echo "<div class='box'><h2>Step 2: Verify Table Structure</h2>";
    
    // Get updated columns
    $updatedColumns = \Illuminate\Support\Facades\Schema::getColumnListing('registrars');
    echo "<p>Updated columns: " . implode(', ', $updatedColumns) . "</p>";
    
    // Check required columns
    $requiredColumns = ['id', 'first_name', 'last_name', 'name', 'email', 'password', 'phone', 'address', 'profile_picture', 'registrar_secret', 'remember_token', 'created_at', 'updated_at'];
    $missingColumns = array_diff($requiredColumns, $updatedColumns);
    
    if (empty($missingColumns)) {
        echo "<p class='success'>✅ All required columns are present</p>";
    } else {
        echo "<p class='error'>❌ Missing columns: " . implode(', ', $missingColumns) . "</p>";
    }
    echo "</div>";

    echo "<div class='box'><h2>Step 3: Create Test Registrar Account</h2>";
    
    // Delete existing registrar
    \Illuminate\Support\Facades\DB::table('registrars')->where('email', 'registrar@cnhs.edu.ph')->delete();
    echo "<p>🗑️ Deleted existing registrar accounts</p>";
    
    // Create new registrar using direct DB insert
    $registrarId = \Illuminate\Support\Facades\DB::table('registrars')->insertGetId([
        'first_name' => 'CNHS',
        'last_name' => 'Registrar',
        'name' => 'CNHS Registrar',
        'email' => 'registrar@cnhs.edu.ph',
        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        'phone' => '09123456789',
        'address' => 'Camarines Norte High School',
        'registrar_secret' => 'letmein',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "<p class='success'>✅ Created test registrar account with ID: {$registrarId}</p>";
    echo "</div>";

    echo "<div class='box'><h2>Step 4: Test Registrar Model</h2>";
    
    // Test using Eloquent model
    try {
        $registrar = \App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
        if ($registrar) {
            echo "<p class='success'>✅ Registrar found using Eloquent model</p>";
            echo "<p>ID: {$registrar->id}</p>";
            echo "<p>Name: {$registrar->name}</p>";
            echo "<p>Email: {$registrar->email}</p>";
            echo "<p>Full Name: {$registrar->full_name}</p>";
            
            // Test password verification
            $passwordCheck = \Illuminate\Support\Facades\Hash::check('123456', $registrar->password);
            echo "<p>Password verification: " . ($passwordCheck ? "<span class='success'>✅ WORKS</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
        } else {
            echo "<p class='error'>❌ Registrar not found using Eloquent model</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Eloquent model error: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    echo "<div class='box'><h2>Step 5: Test Authentication</h2>";
    
    // Test Laravel Auth::attempt
    try {
        $authResult = \Illuminate\Support\Facades\Auth::guard('registrar')->attempt([
            'email' => 'registrar@cnhs.edu.ph',
            'password' => '123456'
        ]);
        
        echo "<p>Laravel Auth::attempt: " . ($authResult ? "<span class='success'>✅ SUCCESS</span>" : "<span class='error'>❌ FAILED</span>") . "</p>";
        
        if ($authResult) {
            $authUser = \Illuminate\Support\Facades\Auth::guard('registrar')->user();
            echo "<p>Authenticated user: {$authUser->email} (ID: {$authUser->id})</p>";
            \Illuminate\Support\Facades\Auth::guard('registrar')->logout();
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Auth error: " . $e->getMessage() . "</p>";
    }
    echo "</div>";

    echo "<div class='box'>";
    echo "<h2>🎉 Table Fix Complete!</h2>";
    echo "<p class='success'>The registrars table has been fixed and is now compatible with all bypass routes.</p>";
    echo "<p><strong>Test the bypass routes:</strong></p>";
    echo "<p><a href='/registrar-bypass' style='background:#17a2b8;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin:5px;'>Test Registrar Bypass</a></p>";
    echo "<p><a href='/all-login-solutions' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin:5px;'>All Login Solutions</a></p>";
    echo "</div>";

} catch (Exception $e) {
    echo "<div class='box'><p class='error'>❌ Critical Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre></div>";
}

echo "</body></html>";
?>
