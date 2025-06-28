<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ROLE FIELD TEST ===\n\n";

// Simulate a POST request to the login route
$request = new \Illuminate\Http\Request();
$request->merge([
    'role' => 'registrar',
    'email' => 'registrar@cnhs.edu.ph',
    'password' => 'password123',
    '_token' => csrf_token()
]);

echo "Request data:\n";
echo "Role: " . $request->input('role') . "\n";
echo "Email: " . $request->input('email') . "\n";
echo "Password: " . ($request->input('password') ? '***filled***' : 'empty') . "\n";
echo "All data: " . json_encode($request->all(), JSON_PRETTY_PRINT) . "\n\n";

// Test the validation rules
$rules = [
    'role' => 'required|in:admin,teacher,student,registrar',
    'password' => 'required',
    'email' => 'required|email'
];

try {
    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    
    if ($validator->fails()) {
        echo "❌ Validation failed:\n";
        foreach ($validator->errors()->all() as $error) {
            echo "- $error\n";
        }
    } else {
        echo "✅ Validation passed!\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n"; 