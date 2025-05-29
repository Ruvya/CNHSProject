<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Registrar;
use Illuminate\Support\Facades\Hash;

try {
    echo "Testing registrar login credentials...\n\n";

    $email = 'registrar@cnhs.edu.ph';
    $password = 'password123';

    echo "Looking for registrar with email: {$email}\n";
    $registrar = Registrar::where('email', $email)->first();

    if (!$registrar) {
        echo "❌ No registrar found with email: {$email}\n";
        echo "Available registrars:\n";
        $all = Registrar::all();
        foreach ($all as $r) {
            echo "- {$r->email}\n";
        }
        exit;
    }

    echo "✅ Found registrar: {$registrar->first_name} {$registrar->last_name}\n";
    echo "ID: {$registrar->id}\n";
    echo "Email: {$registrar->email}\n";
    echo "Created: {$registrar->created_at}\n\n";

    echo "Testing password...\n";
    $passwordCorrect = Hash::check($password, $registrar->password);
    echo "Password check result: " . ($passwordCorrect ? "✅ CORRECT" : "❌ INCORRECT") . "\n\n";

    if ($passwordCorrect) {
        echo "🎉 Authentication test PASSED!\n";
        echo "The credentials should work for login.\n\n";
        
        echo "=== LOGIN CREDENTIALS ===\n";
        echo "Email: {$email}\n";
        echo "Password: {$password}\n";
        echo "========================\n\n";
        
        echo "If login still fails, check:\n";
        echo "1. Clear browser cache/cookies\n";
        echo "2. Try incognito/private mode\n";
        echo "3. Check browser console for JavaScript errors\n";
        echo "4. Verify you're using the correct login URL: /registrar/login\n";
    } else {
        echo "❌ Authentication test FAILED!\n";
        echo "Password hash in database: {$registrar->password}\n";
        echo "Expected password: {$password}\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
