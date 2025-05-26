<?php
// Save this as fix_admin_login.php and run it with: php fix_admin_login.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

echo "Starting admin login fix...\n";

// Check if admins table exists
if (!Schema::hasTable('admins')) {
    echo "Error: Admins table does not exist!\n";
    exit;
}

// Check if there are any admin users
$adminCount = DB::table('admins')->count();
echo "Found {$adminCount} admin users.\n";

// Check if Admin username exists
$adminUser = DB::table('admins')->where('username', 'Admin')->first();

if (!$adminUser) {
    echo "Creating admin user with username 'Admin'...\n";
    
    // Create admin with username Admin
    DB::table('admins')->insert([
        'name' => 'Administrator',
        'username' => 'Admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('admin123'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "Created admin user with:\n";
    echo "Username: Admin\n";
    echo "Password: admin123\n";
} else {
    // Reset password for existing admin
    echo "Resetting password for username 'Admin'...\n";
    
    DB::table('admins')->where('username', 'Admin')
        ->update([
            'password' => Hash::make('admin123'),
            'updated_at' => now(),
        ]);
    
    echo "Password reset to: admin123\n";
}

// Check auth configuration
$authConfig = config('auth');
echo "\nChecking auth configuration...\n";

if (isset($authConfig['guards']['admin'])) {
    echo "Admin guard is configured.\n";
    echo "Provider: " . $authConfig['guards']['admin']['provider'] . "\n";
} else {
    echo "Warning: Admin guard is not configured in config/auth.php!\n";
}

if (isset($authConfig['providers']['admins'])) {
    echo "Admin provider is configured.\n";
    echo "Model: " . $authConfig['providers']['admins']['model'] . "\n";
} else {
    echo "Warning: Admin provider is not configured in config/auth.php!\n";
}

echo "\nAdmin login fix completed!\n";
echo "Try logging in with:\n";
echo "Username: Admin\n";
echo "Password: admin123\n";
