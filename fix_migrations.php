<?php

// Script to fix migration issues by marking existing tables as migrated
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    echo "=== Migration Fix Script ===\n";
    
    // Check if migrations table exists
    if (!Schema::hasTable('migrations')) {
        echo "Creating migrations table...\n";
        DB::statement('
            CREATE TABLE migrations (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL
            )
        ');
        echo "✓ Migrations table created\n";
    }
    
    // List of migrations that should be marked as run if their tables exist
    $migrationsToCheck = [
        '2025_01_20_000001_create_temporary_student_credentials_table' => 'temporary_student_credentials',
        '2025_01_28_000010_create_sections_table' => 'sections',
        '2025_01_28_000011_create_student_assignments_table' => 'student_assignments',
        '2025_01_28_000012_create_teacher_assignments_table' => 'teacher_assignments',
        '2025_05_25_093534_create_student_subject_table' => 'student_subject',
    ];
    
    $batch = DB::table('migrations')->max('batch') + 1;
    
    foreach ($migrationsToCheck as $migration => $table) {
        // Check if table exists
        if (Schema::hasTable($table)) {
            // Check if migration is already recorded
            $exists = DB::table('migrations')->where('migration', $migration)->exists();
            
            if (!$exists) {
                echo "Marking migration as run: {$migration}\n";
                DB::table('migrations')->insert([
                    'migration' => $migration,
                    'batch' => $batch
                ]);
                echo "✓ Marked {$migration} as run\n";
            } else {
                echo "Migration already recorded: {$migration}\n";
            }
        } else {
            echo "Table {$table} does not exist, migration {$migration} will run normally\n";
        }
    }
    
    echo "\n=== Current Migration Status ===\n";
    $migrations = DB::table('migrations')->orderBy('batch')->orderBy('migration')->get();
    foreach ($migrations as $migration) {
        echo "Batch {$migration->batch}: {$migration->migration}\n";
    }
    
    echo "\n✓ Migration fix completed!\n";
    echo "You can now run 'php artisan migrate' to run any remaining migrations.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
