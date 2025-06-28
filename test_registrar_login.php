<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\Registrar;

echo "=== REGISTRAR LOGIN TEST ===\n\n";

// Test credentials
$credentials = [
    'email' => 'registrar@cnhs.edu.ph',
    'password' => 'password123'
];

echo "Testing registrar login with:\n";
echo "Email: " . $credentials['email'] . "\n";
echo "Password: " . $credentials['password'] . "\n\n";

// Check if registrar exists
$registrar = Registrar::where('email', $credentials['email'])->first();
if (!$registrar) {
    echo "❌ Registrar user not found!\n";
    echo "Available registrars:\n";
    $registrars = Registrar::all();
    foreach ($registrars as $r) {
        echo "- ID: {$r->id}, Email: {$r->email}, Name: {$r->name}\n";
    }
    exit;
}

echo "✅ Registrar user found:\n";
echo "ID: " . $registrar->id . "\n";
echo "Name: " . $registrar->name . "\n";
echo "Email: " . $registrar->email . "\n\n";

// Test password verification
echo "Testing password verification...\n";
$passwordCorrect = \Illuminate\Support\Facades\Hash::check($credentials['password'], $registrar->password);
if ($passwordCorrect) {
    echo "✅ Password verification successful!\n";
} else {
    echo "❌ Password verification failed!\n";
    exit;
}

// Test authentication
echo "\nTesting authentication with registrar guard...\n";
if (Auth::guard('registrar')->attempt($credentials)) {
    echo "✅ Authentication successful!\n";
    $authenticatedUser = Auth::guard('registrar')->user();
    echo "Authenticated as: " . $authenticatedUser->name . "\n";
    
    // Test route existence
    echo "\nTesting registrar dashboard route...\n";
    try {
        $dashboardUrl = route('registrar.dashboard');
        echo "✅ Registrar dashboard route exists: " . $dashboardUrl . "\n";
    } catch (Exception $e) {
        echo "❌ Registrar dashboard route error: " . $e->getMessage() . "\n";
    }
    
    // Test middleware
    echo "\nTesting auth:registrar middleware...\n";
    $isAuthenticated = Auth::guard('registrar')->check();
    echo "Is authenticated: " . ($isAuthenticated ? "✅ YES" : "❌ NO") . "\n";
    
    // Logout
    Auth::guard('registrar')->logout();
    echo "✅ Logged out successfully\n";
    
} else {
    echo "❌ Authentication failed!\n";
    
    // Try manual login
    echo "\nTrying manual login...\n";
    Auth::guard('registrar')->login($registrar);
    $isAuthenticated = Auth::guard('registrar')->check();
    echo "Manual login result: " . ($isAuthenticated ? "✅ SUCCESS" : "❌ FAILED") . "\n";
    
    if ($isAuthenticated) {
        $authenticatedUser = Auth::guard('registrar')->user();
        echo "Authenticated as: " . $authenticatedUser->name . "\n";
        Auth::guard('registrar')->logout();
    }
}

echo "\n=== TEST COMPLETE ===\n"; 