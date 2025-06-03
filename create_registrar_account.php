<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Registrar;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

try {
    echo "=== CREATING REGISTRAR ACCOUNT ===\n\n";

    // Check if registrars table exists
    if (!Schema::hasTable('registrars')) {
        echo "❌ Registrars table does not exist. Please run migrations first.\n";
        echo "Run: php artisan migrate\n";
        exit(1);
    }

    // Check if registrar_secret column exists, if not add it
    if (!Schema::hasColumn('registrars', 'registrar_secret')) {
        echo "Adding registrar_secret column...\n";
        Schema::table('registrars', function ($table) {
            $table->string('registrar_secret')->nullable();
        });
        echo "✅ Added registrar_secret column\n\n";
    }

    // Delete existing registrar if exists
    echo "Checking for existing registrar...\n";
    $existing = Registrar::where('email', 'registrar@cnhs.edu.ph')->first();
    if ($existing) {
        echo "Found existing registrar, deleting...\n";
        $existing->delete();
        echo "✅ Deleted existing registrar\n\n";
    }

    // Create new registrar
    echo "Creating new registrar account...\n";
    $registrar = Registrar::create([
        'first_name' => 'Registrar',
        'last_name' => 'User',
        'email' => 'registrar@cnhs.edu.ph',
        'password' => Hash::make('password123'),
        'registrar_secret' => 'letmein',
    ]);

    echo "✅ Successfully created registrar account!\n\n";

    echo "=== REGISTRAR LOGIN CREDENTIALS ===\n";
    echo "🌐 Login URL: http://localhost:8000/registrar/login\n";
    echo "📧 Email: registrar@cnhs.edu.ph\n";
    echo "🔑 Password: password123\n";
    echo "🔐 Secret Code: letmein (if required)\n\n";

    echo "=== ALTERNATIVE CREDENTIALS ===\n";
    
    // Also create alternative registrar account
    $alt_existing = Registrar::where('email', 'registrar@example.com')->first();
    if ($alt_existing) {
        $alt_existing->delete();
    }
    
    $alt_registrar = Registrar::create([
        'first_name' => 'Test',
        'last_name' => 'Registrar',
        'email' => 'registrar@example.com',
        'password' => Hash::make('registrar123'),
        'registrar_secret' => 'letmein',
    ]);
    
    echo "📧 Email: registrar@example.com\n";
    echo "🔑 Password: registrar123\n";
    echo "🔐 Secret Code: letmein\n\n";

    echo "=== VERIFICATION ===\n";
    $count = Registrar::count();
    echo "Total registrar accounts: {$count}\n";
    
    $all_registrars = Registrar::all();
    foreach ($all_registrars as $reg) {
        echo "- {$reg->email} (ID: {$reg->id})\n";
    }

    echo "\n✅ Setup complete! You can now log in as registrar.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
