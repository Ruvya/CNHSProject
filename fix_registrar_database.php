<?php

echo "=== FIXING REGISTRAR DATABASE ===\n\n";

try {
    // Bootstrap Laravel
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "✅ Laravel bootstrapped successfully\n";

    // Check if registrars table exists
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('registrars');
    echo "Registrars table exists: " . ($tableExists ? "YES" : "NO") . "\n";

    if (!$tableExists) {
        echo "❌ Registrars table doesn't exist. Creating it...\n";
        
        // Create registrars table
        \Illuminate\Support\Facades\Schema::create('registrars', function ($table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('registrar_secret')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        
        echo "✅ Registrars table created successfully\n";
    }

    // Check if registrar_secret column exists
    $hasSecretColumn = \Illuminate\Support\Facades\Schema::hasColumn('registrars', 'registrar_secret');
    echo "Registrar secret column exists: " . ($hasSecretColumn ? "YES" : "NO") . "\n";

    if (!$hasSecretColumn) {
        echo "Adding registrar_secret column...\n";
        \Illuminate\Support\Facades\Schema::table('registrars', function ($table) {
            $table->string('registrar_secret')->nullable();
        });
        echo "✅ Added registrar_secret column\n";
    }

    // Delete any existing registrar accounts
    echo "Cleaning up existing registrar accounts...\n";
    $deleted = \App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->delete();
    echo "Deleted $deleted existing registrar accounts\n";

    // Create new registrar account
    echo "Creating new registrar account...\n";
    $registrar = \App\Models\Registrar::create([
        'first_name' => 'Registrar',
        'last_name' => 'User',
        'email' => 'registrar@cnhs.edu.ph',
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'registrar_secret' => 'letmein',
    ]);

    echo "✅ Registrar account created successfully!\n";
    echo "ID: " . $registrar->id . "\n";
    echo "Email: " . $registrar->email . "\n";

    // Verify the account exists
    $verify = \App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
    if ($verify) {
        echo "✅ Verification successful - registrar account exists\n";
        echo "Account ID: " . $verify->id . "\n";
        echo "Account Email: " . $verify->email . "\n";
        echo "Has Password: " . ($verify->password ? "YES" : "NO") . "\n";
        echo "Has Secret: " . ($verify->registrar_secret ? "YES" : "NO") . "\n";
    } else {
        echo "❌ Verification failed - account not found\n";
    }

    // Test password verification
    $passwordTest = \Illuminate\Support\Facades\Hash::check('password123', $verify->password);
    echo "Password test: " . ($passwordTest ? "PASS" : "FAIL") . "\n";

    echo "\n=== CREDENTIALS FOR LOGIN ===\n";
    echo "🌐 Main Login URL: http://localhost:8000/login\n";
    echo "📧 Email: registrar@cnhs.edu.ph\n";
    echo "🔑 Password: password123\n";
    echo "👤 Role: Select 'Registrar' from dropdown\n";
    echo "🔐 Secret (if needed): letmein\n\n";

    echo "=== ALTERNATIVE LOGIN ===\n";
    echo "🌐 Registrar Login URL: http://localhost:8000/registrar/login\n";
    echo "📧 Email: registrar@cnhs.edu.ph\n";
    echo "🔑 Password: password123\n\n";

    // Count total registrars
    $totalRegistrars = \App\Models\Registrar::count();
    echo "Total registrar accounts in database: $totalRegistrars\n";

    echo "\n✅ SETUP COMPLETE! You can now log in as registrar.\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
