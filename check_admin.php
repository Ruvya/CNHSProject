<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

echo "Checking admin users...\n\n";

// Check all admin users
$admins = Admin::all();

if ($admins->count() === 0) {
    echo "No admin users found. Creating default admin...\n";
    
    Admin::create([
        'name' => 'Administrator',
        'username' => 'Admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('admin123'),
    ]);
    
    echo "Created admin user:\n";
    echo "Username: Admin\n";
    echo "Password: admin123\n";
} else {
    echo "Found " . $admins->count() . " admin user(s):\n\n";
    
    foreach ($admins as $admin) {
        echo "ID: " . $admin->id . "\n";
        echo "Name: " . $admin->name . "\n";
        echo "Username: " . $admin->username . "\n";
        echo "Email: " . $admin->email . "\n";
        echo "Created: " . $admin->created_at . "\n";
        echo "---\n";
    }
    
    // Reset password for the first admin to ensure we can login
    $firstAdmin = $admins->first();
    $firstAdmin->password = Hash::make('admin123');
    $firstAdmin->save();
    
    echo "\nPassword reset for username '" . $firstAdmin->username . "' to: admin123\n";
}

echo "\nYou can now login with:\n";
echo "Username: " . ($admins->count() > 0 ? $admins->first()->username : 'Admin') . "\n";
echo "Password: admin123\n";
