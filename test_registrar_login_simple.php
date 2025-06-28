<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Route;

echo "=== REGISTRAR LOGIN ROUTE TEST ===\n\n";

// Test if the route exists
try {
    $route = Route::getRoutes()->getByName('registrar.login.post');
    if ($route) {
        echo "✅ Route 'registrar.login.post' exists\n";
        echo "URL: " . $route->uri() . "\n";
        echo "Method: " . implode('|', $route->methods()) . "\n";
        echo "Controller: " . $route->getController() . "@" . $route->getActionMethod() . "\n";
    } else {
        echo "❌ Route 'registrar.login.post' not found\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking route: " . $e->getMessage() . "\n";
}

// Test if the GET route exists
try {
    $route = Route::getRoutes()->getByName('registrar.login');
    if ($route) {
        echo "✅ Route 'registrar.login' exists\n";
        echo "URL: " . $route->uri() . "\n";
        echo "Method: " . implode('|', $route->methods()) . "\n";
        echo "Controller: " . $route->getController() . "@" . $route->getActionMethod() . "\n";
    } else {
        echo "❌ Route 'registrar.login' not found\n";
    }
} catch (Exception $e) {
    echo "❌ Error checking route: " . $e->getMessage() . "\n";
}

echo "\n=== FORM ACTION TEST ===\n";

// Test the form action URL generation
try {
    $url = route('registrar.login.post');
    echo "✅ Form action URL: $url\n";
} catch (Exception $e) {
    echo "❌ Error generating form action URL: " . $e->getMessage() . "\n";
}

echo "\n=== CONTROLLER TEST ===\n";

// Test if the controller exists
try {
    $controller = new \App\Http\Controllers\Registrar\AuthController();
    echo "✅ RegistrarAuthController exists and can be instantiated\n";
} catch (Exception $e) {
    echo "❌ Error with RegistrarAuthController: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n"; 