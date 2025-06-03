<?php

// Simple script to check and run pending migrations
echo "=== Checking Pending Migrations ===\n";

try {
    // Include Laravel bootstrap
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    // Get all migration files
    $migrationFiles = glob(__DIR__ . '/database/migrations/*.php');
    $allMigrations = [];
    
    foreach ($migrationFiles as $file) {
        $filename = basename($file, '.php');
        $allMigrations[] = $filename;
    }
    
    echo "Found " . count($allMigrations) . " migration files\n";
    
    // Get completed migrations from database
    $completedMigrations = \Illuminate\Support\Facades\DB::table('migrations')
        ->pluck('migration')
        ->toArray();
    
    echo "Found " . count($completedMigrations) . " completed migrations\n";
    
    // Find pending migrations
    $pendingMigrations = array_diff($allMigrations, $completedMigrations);
    
    if (empty($pendingMigrations)) {
        echo "✅ No pending migrations found!\n";
    } else {
        echo "⚠️ Found " . count($pendingMigrations) . " pending migrations:\n";
        foreach ($pendingMigrations as $migration) {
            echo "  - $migration\n";
        }
        
        echo "\n=== Running Pending Migrations ===\n";
        
        // Try to run migrations one by one
        foreach ($pendingMigrations as $migration) {
            echo "Running: $migration\n";
            
            try {
                // Load the migration file
                $migrationPath = __DIR__ . "/database/migrations/$migration.php";
                $migrationClass = include $migrationPath;
                
                // Run the migration
                $migrationClass->up();
                
                // Mark as completed in database
                $batch = \Illuminate\Support\Facades\DB::table('migrations')->max('batch') + 1;
                \Illuminate\Support\Facades\DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => $batch
                ]);
                
                echo "✅ Completed: $migration\n";
                
            } catch (Exception $e) {
                echo "❌ Failed: $migration - " . $e->getMessage() . "\n";
                
                // Check if it's a table already exists error
                if (strpos($e->getMessage(), 'already exists') !== false) {
                    echo "   Table already exists, marking as completed...\n";
                    $batch = \Illuminate\Support\Facades\DB::table('migrations')->max('batch') + 1;
                    \Illuminate\Support\Facades\DB::table('migrations')->insert([
                        'migration' => $migration,
                        'batch' => $batch
                    ]);
                    echo "✅ Marked as completed: $migration\n";
                } else {
                    echo "   Stopping due to error.\n";
                    break;
                }
            }
        }
    }
    
    // Final status check
    echo "\n=== Final Status ===\n";
    $finalCompleted = \Illuminate\Support\Facades\DB::table('migrations')->count();
    $finalPending = count($allMigrations) - $finalCompleted;
    
    echo "Total migrations: " . count($allMigrations) . "\n";
    echo "Completed: $finalCompleted\n";
    echo "Pending: $finalPending\n";
    
    if ($finalPending == 0) {
        echo "🎉 All migrations completed successfully!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
