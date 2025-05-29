<?php

// Simple script to check database status
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    echo "=== Database Status Check ===\n";
    
    // Check if migrations table exists
    $hasMigrationsTable = Schema::hasTable('migrations');
    echo "Migrations table exists: " . ($hasMigrationsTable ? 'YES' : 'NO') . "\n";
    
    if ($hasMigrationsTable) {
        $migrationCount = DB::table('migrations')->count();
        echo "Number of recorded migrations: {$migrationCount}\n";
        
        echo "\nLast 5 migrations:\n";
        $lastMigrations = DB::table('migrations')->orderBy('batch', 'desc')->orderBy('id', 'desc')->limit(5)->get();
        foreach ($lastMigrations as $migration) {
            echo "- Batch {$migration->batch}: {$migration->migration}\n";
        }
    }
    
    // Check important tables
    $importantTables = [
        'students', 'teachers', 'admins', 'principals', 'registrars',
        'subjects', 'announcements', 'grades', 'sections',
        'student_assignments', 'teacher_assignments', 'student_subject',
        'temporary_student_credentials'
    ];
    
    echo "\n=== Table Status ===\n";
    foreach ($importantTables as $table) {
        $exists = Schema::hasTable($table);
        echo "{$table}: " . ($exists ? 'EXISTS' : 'MISSING') . "\n";
    }
    
    echo "\n✓ Database check completed!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
