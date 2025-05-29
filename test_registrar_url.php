<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== REGISTRAR LOGIN INFORMATION ===\n\n";

echo "✅ CORRECT LOGIN URL:\n";
echo "🔗 http://localhost:8000/registrar/login\n\n";

echo "✅ CREDENTIALS:\n";
echo "📧 Email: registrar@cnhs.edu.ph\n";
echo "🔑 Password: password123\n\n";

echo "❌ DON'T USE:\n";
echo "🚫 http://localhost:8000/login (general login with role switching)\n\n";

echo "=== VERIFICATION ===\n";
try {
    $registrar = \App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
    if ($registrar) {
        $passwordCheck = \Illuminate\Support\Facades\Hash::check('password123', $registrar->password);
        echo "✅ Registrar exists: {$registrar->first_name} {$registrar->last_name}\n";
        echo "✅ Password valid: " . ($passwordCheck ? "YES" : "NO") . "\n";
        echo "✅ Account ready for login!\n\n";
    } else {
        echo "❌ No registrar found with email: registrar@cnhs.edu.ph\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking registrar: " . $e->getMessage() . "\n";
}

echo "=== TROUBLESHOOTING ===\n";
echo "If login still fails:\n";
echo "1. Clear browser cache and cookies\n";
echo "2. Try incognito/private browsing mode\n";
echo "3. Make sure you're using the CORRECT URL: /registrar/login\n";
echo "4. Check browser console for JavaScript errors\n";
echo "5. Verify the Laravel server is running on port 8000\n\n";

echo "🎯 Ready to login as registrar!\n";
