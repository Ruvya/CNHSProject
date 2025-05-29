<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Registrar;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    echo "Checking database connection...\n";
    DB::connection()->getPdo();
    echo "✅ Database connected successfully!\n\n";

    echo "Checking current registrars...\n";
    $registrars = Registrar::all();
    echo "Found " . $registrars->count() . " registrars:\n";
    foreach ($registrars as $registrar) {
        echo "- {$registrar->email} ({$registrar->first_name} {$registrar->last_name})\n";
    }
    echo "\n";

    // Delete existing registrar if exists
    echo "Deleting existing registrar with email 'registrar@cnhs.edu.ph'...\n";
    Registrar::where('email', 'registrar@cnhs.edu.ph')->delete();
    echo "✅ Deleted existing registrar\n\n";

    // Create new registrar
    echo "Creating new registrar...\n";
    $registrar = Registrar::create([
        'first_name' => 'Registrar',
        'last_name' => 'User',
        'email' => 'registrar@cnhs.edu.ph',
        'password' => Hash::make('password123'),
        'registrar_secret' => 'letmein',
    ]);

    echo "✅ Created registrar successfully!\n";
    echo "ID: {$registrar->id}\n";
    echo "Email: {$registrar->email}\n";
    echo "Name: {$registrar->first_name} {$registrar->last_name}\n";
    echo "Password: password123\n";
    echo "Secret: letmein\n\n";

    // Verify password
    echo "Verifying password...\n";
    $passwordCheck = Hash::check('password123', $registrar->password);
    echo "Password verification: " . ($passwordCheck ? "✅ PASS" : "❌ FAIL") . "\n\n";

    echo "🎉 Registrar account is ready for login!\n";
    echo "Use these credentials:\n";
    echo "Email: registrar@cnhs.edu.ph\n";
    echo "Password: password123\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
