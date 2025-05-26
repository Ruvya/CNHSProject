<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

echo "=== Admin Login Debug Test ===\n\n";

// Test credentials
$credentials = [
    'username' => 'admin',
    'password' => 'admin123'
];

echo "Testing admin login with:\n";
echo "Username: " . $credentials['username'] . "\n";
echo "Password: " . $credentials['password'] . "\n\n";

// Check if admin exists
$admin = Admin::where('username', $credentials['username'])->first();
if (!$admin) {
    echo "❌ Admin user not found!\n";
    echo "Available admins:\n";
    $admins = Admin::all();
    foreach ($admins as $a) {
        echo "- ID: {$a->id}, Username: {$a->username}, Name: {$a->name}\n";
    }
    exit;
}

echo "✅ Admin user found:\n";
echo "ID: " . $admin->id . "\n";
echo "Name: " . $admin->name . "\n";
echo "Username: " . $admin->username . "\n";
echo "Email: " . $admin->email . "\n\n";

// Test authentication
echo "Testing authentication with admin guard...\n";
if (Auth::guard('admin')->attempt($credentials)) {
    echo "✅ Authentication successful!\n";
    $authenticatedUser = Auth::guard('admin')->user();
    echo "Authenticated as: " . $authenticatedUser->name . "\n";
    
    // Test route existence
    echo "\nTesting admin dashboard route...\n";
    try {
        $dashboardUrl = route('admin.dashboard');
        echo "✅ Admin dashboard route exists: " . $dashboardUrl . "\n";
    } catch (Exception $e) {
        echo "❌ Admin dashboard route error: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "❌ Authentication failed!\n";
    
    // Manual password check
    if (password_verify($credentials['password'], $admin->password)) {
        echo "✅ Password verification successful (manual check)\n";
        echo "Issue might be with Laravel auth guard configuration\n";
    } else {
        echo "❌ Password verification failed (manual check)\n";
        echo "Password in database might be different\n";
    }
}

echo "\n=== Debug Complete ===\n";
