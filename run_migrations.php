<?php

echo "Starting migration process...\n";

try {
    // Change to the project directory
    chdir(__DIR__);
    
    echo "Current directory: " . getcwd() . "\n";
    
    // Check if artisan exists
    if (!file_exists('artisan')) {
        echo "❌ artisan file not found!\n";
        exit(1);
    }
    
    echo "✅ artisan file found\n";
    
    // Try to run migrate:status first
    echo "\n=== Checking migration status ===\n";
    $output = [];
    $return_var = 0;
    exec('php artisan migrate:status 2>&1', $output, $return_var);
    
    if ($return_var === 0) {
        echo "✅ Migration status command successful\n";
        foreach ($output as $line) {
            echo $line . "\n";
        }
        
        // Check if there are pending migrations
        $hasPending = false;
        foreach ($output as $line) {
            if (strpos($line, 'Pending') !== false) {
                $hasPending = true;
                break;
            }
        }
        
        if ($hasPending) {
            echo "\n=== Running pending migrations ===\n";
            $output = [];
            $return_var = 0;
            exec('php artisan migrate --force 2>&1', $output, $return_var);
            
            if ($return_var === 0) {
                echo "✅ Migrations completed successfully!\n";
                foreach ($output as $line) {
                    echo $line . "\n";
                }
            } else {
                echo "❌ Migration failed with return code: $return_var\n";
                foreach ($output as $line) {
                    echo $line . "\n";
                }
            }
        } else {
            echo "✅ No pending migrations found!\n";
        }
        
    } else {
        echo "❌ Migration status command failed with return code: $return_var\n";
        foreach ($output as $line) {
            echo $line . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
