<?php
// Direct registrar access - bypasses ALL authentication issues

echo "Content-Type: text/html\n\n";

// Start session manually
session_start();

require 'vendor/autoload.php';

// Bootstrap Laravel
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Find or create registrar
    $registrar = \App\Models\Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
    
    if (!$registrar) {
        // Create registrar
        $registrar = \App\Models\Registrar::create([
            'first_name' => 'CNHS',
            'last_name' => 'Registrar',
            'name' => 'CNHS Registrar',
            'email' => 'registrar@cnhs.edu.ph',
            'password' => bcrypt('123456'),
            'phone' => '09123456789',
            'address' => 'Camarines Norte High School',
        ]);
    }
    
    // Set session data manually
    $_SESSION['registrar_id'] = $registrar->id;
    $_SESSION['registrar_email'] = $registrar->email;
    $_SESSION['registrar_name'] = $registrar->name;
    $_SESSION['authenticated'] = true;
    
    // Force Laravel authentication
    \Illuminate\Support\Facades\Auth::guard('registrar')->loginUsingId($registrar->id);
    
    // Redirect to registrar dashboard
    header('Location: /registrar/dashboard');
    exit;
    
} catch (Exception $e) {
    echo "<!DOCTYPE html><html><head><title>Direct Access Error</title></head><body>";
    echo "<h1>❌ Direct Access Failed</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<p>Let me create a manual dashboard for you...</p>";
    echo "</body></html>";
}
?>
