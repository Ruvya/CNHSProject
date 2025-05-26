<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

echo "Testing admin authentication...\n\n";

// Test credentials
$credentials = [
    'username' => 'admin',
    'password' => 'admin123'
];

echo "Testing with credentials:\n";
echo "Username: " . $credentials['username'] . "\n";
echo "Password: " . $credentials['password'] . "\n\n";

// Check if admin exists
$admin = Admin::where('username', $credentials['username'])->first();
if (!$admin) {
    echo "❌ Admin user not found!\n";
    exit;
}

echo "✅ Admin user found:\n";
echo "ID: " . $admin->id . "\n";
echo "Name: " . $admin->name . "\n";
echo "Username: " . $admin->username . "\n";
echo "Email: " . $admin->email . "\n\n";

// Test authentication
echo "Testing authentication...\n";
if (Auth::guard('admin')->attempt($credentials)) {
    echo "✅ Authentication successful!\n";
    echo "Authenticated user: " . Auth::guard('admin')->user()->name . "\n";
} else {
    echo "❌ Authentication failed!\n";
    
    // Check password manually
    if (password_verify($credentials['password'], $admin->password)) {
        echo "✅ Password verification successful (manual check)\n";
        echo "Issue might be with Laravel auth configuration\n";
    } else {
        echo "❌ Password verification failed (manual check)\n";
        echo "Password in database might be different\n";
    }
}

echo "\nDone.\n";
