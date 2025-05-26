<?php
// Save this as test_admin_login.php and run it with: php test_admin_login.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

echo "Testing admin login functionality...\n";

// 1. Check if admins table exists
if (!Schema::hasTable('admins')) {
    echo "Error: Admins table does not exist!\n";
    exit;
}

// 2. Create a test admin if none exists
$admin = Admin::where('email', 'admin@example.com')->first();
if (!$admin) {
    echo "Creating test admin...\n";
    $admin = new Admin();
    $admin->name = 'Admin User';
    $admin->username = 'admin';
    $admin->email = 'admin@example.com';
    $admin->password = Hash::make('admin123');
    $admin->save();
    echo "Test admin created.\n";
} else {
    echo "Test admin already exists.\n";
    // Reset password
    $admin->password = Hash::make('admin123');
    $admin->save();
    echo "Password reset to 'admin123'.\n";
}

// 3. Test authentication
echo "Testing authentication...\n";
$credentials = [
    'email' => 'admin@example.com',
    'password' => 'admin123'
];

if (Auth::guard('admin')->attempt($credentials)) {
    echo "Authentication successful!\n";
} else {
    echo "Authentication failed!\n";
    
    // Check auth configuration
    $authConfig = config('auth');
    echo "\nChecking auth configuration...\n";
    
    if (isset($authConfig['guards']['admin'])) {
        echo "Admin guard is configured.\n";
        echo "Provider: " . $authConfig['guards']['admin']['provider'] . "\n";
    } else {
        echo "Warning: Admin guard is not configured in config/auth.php!\n";
        echo "Please add the following to your config/auth.php file:\n";
        echo "
'guards' => [
    // ... other guards
    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
],

'providers' => [
    // ... other providers
    'admins' => [
        'driver' => 'eloquent',
        'model' => App\\Models\\Admin::class,
    ],
],
";
    }
}

echo "\nTest completed.\n";
echo "Try logging in with:\n";
echo "Email: admin@example.com\n";
echo "Password: admin123\n";