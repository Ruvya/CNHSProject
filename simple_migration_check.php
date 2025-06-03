<?php

// Simple migration checker that doesn't require Laravel bootstrap
echo "=== Simple Migration Check ===\n";

try {
    // Database connection details from .env
    $host = '127.0.0.1';
    $port = '3306';
    $database = 'NewStudentPortal';
    $username = 'root';
    $password = 'ruvyannlacaba1@1';
    
    // Connect to database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection successful\n";
    
    // Get migration files
    $migrationFiles = glob(__DIR__ . '/database/migrations/*.php');
    $allMigrations = [];
    
    foreach ($migrationFiles as $file) {
        $filename = basename($file, '.php');
        $allMigrations[] = $filename;
    }
    
    echo "Found " . count($allMigrations) . " migration files\n";
    
    // Get completed migrations
    $stmt = $pdo->query("SELECT migration FROM migrations ORDER BY batch, id");
    $completedMigrations = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
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
    }
    
    // Show last 5 completed migrations
    echo "\n=== Last 5 Completed Migrations ===\n";
    $stmt = $pdo->query("SELECT migration, batch FROM migrations ORDER BY batch DESC, id DESC LIMIT 5");
    $recentMigrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($recentMigrations as $migration) {
        echo "Batch {$migration['batch']}: {$migration['migration']}\n";
    }
    
    // Show table count
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "\nFound " . count($tables) . " tables in database:\n";
    foreach ($tables as $table) {
        echo "  - $table\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
