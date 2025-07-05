<?php

require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Registrar;
use Illuminate\Support\Facades\Hash;

echo "=== REGISTRAR LOGIN FIX SCRIPT ===\n\n";

// Check current registrar accounts
echo "1. Checking existing registrar accounts...\n";
$registrars = Registrar::all();

if ($registrars->count() > 0) {
    echo "Found " . $registrars->count() . " registrar account(s):\n";
    foreach ($registrars as $registrar) {
        echo "   - ID: {$registrar->id}, Email: {$registrar->email}\n";
        echo "     Name: " . ($registrar->name ?? $registrar->first_name . ' ' . $registrar->last_name) . "\n";
        echo "     Password Hash: " . substr($registrar->password, 0, 30) . "...\n\n";
    }
} else {
    echo "No registrar accounts found.\n\n";
}

// Delete existing registrar accounts to start fresh
echo "2. Cleaning up existing registrar accounts...\n";
$deleted = Registrar::where('email', 'registrar@cnhs.edu.ph')->delete();
echo "Deleted {$deleted} existing registrar account(s).\n\n";

// Create new registrar account with known credentials
echo "3. Creating new registrar account...\n";
$registrar = Registrar::create([
    'first_name' => 'CNHS',
    'last_name' => 'Registrar',
    'name' => 'CNHS Registrar',
    'email' => 'registrar@cnhs.edu.ph',
    'password' => Hash::make('123456'),
    'phone' => '09123456789',
    'address' => 'Camarines Norte High School',
    'registrar_secret' => 'letmein',
]);

echo "✅ Created registrar account:\n";
echo "   - ID: {$registrar->id}\n";
echo "   - Email: {$registrar->email}\n";
echo "   - Name: {$registrar->name}\n";
echo "   - Password: 123456 (plain text)\n\n";

// Test password verification
echo "4. Testing password verification...\n";
$testPassword = '123456';
$isCorrect = Hash::check($testPassword, $registrar->password);
echo "Password verification test: " . ($isCorrect ? "✅ PASSED" : "❌ FAILED") . "\n\n";

// Test authentication
echo "5. Testing authentication...\n";
try {
    $credentials = ['email' => 'registrar@cnhs.edu.ph', 'password' => '123456'];
    
    // Manual check (same as in controller)
    $user = Registrar::where('email', $credentials['email'])->first();
    $passwordCorrect = $user ? Hash::check($credentials['password'], $user->password) : false;
    
    echo "Manual authentication test:\n";
    echo "   - User found: " . ($user ? "✅ YES" : "❌ NO") . "\n";
    echo "   - Password correct: " . ($passwordCorrect ? "✅ YES" : "❌ NO") . "\n";
    
    if ($user && $passwordCorrect) {
        echo "   - Authentication: ✅ SUCCESS\n";
    } else {
        echo "   - Authentication: ❌ FAILED\n";
    }
    
} catch (Exception $e) {
    echo "❌ Authentication test failed: " . $e->getMessage() . "\n";
}

echo "\n=== REGISTRAR LOGIN CREDENTIALS ===\n";
echo "Email: registrar@cnhs.edu.ph\n";
echo "Password: 123456\n";
echo "Role: registrar\n\n";

echo "=== NEXT STEPS ===\n";
echo "1. Go to the login page: http://localhost:8000/login\n";
echo "2. Select 'Registrar' role\n";
echo "3. Enter email: registrar@cnhs.edu.ph\n";
echo "4. Enter password: 123456\n";
echo "5. Click Login\n\n";

echo "✅ Registrar login fix completed!\n";
