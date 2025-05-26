<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MarkMigrationsAsRun extends Command
{
    protected $signature = 'migrations:mark-as-run {--migration= : Specific migration to mark as run}';
    protected $description = 'Mark migrations as run without actually running them';

    public function handle()
    {
        $specificMigration = $this->option('migration');
        
        if ($specificMigration) {
            // Mark specific migration as run
            $this->markMigrationAsRun($specificMigration);
        } else {
            // Mark all migrations as run
            $this->markAllMigrationsAsRun();
        }
        
        return Command::SUCCESS;
    }
    
    private function markMigrationAsRun($migration)
    {
        // Check if migration exists
        $migrationPath = database_path("migrations/{$migration}.php");
        if (!File::exists($migrationPath)) {
            $this->error("Migration {$migration}.php does not exist!");
            return;
        }
        
        // Check if already in migrations table
        $exists = DB::table('migrations')->where('migration', $migration)->exists();
        if ($exists) {
            $this->info("Migration {$migration} is already marked as run.");
            return;
        }
        
        // Add to migrations table
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => DB::table('migrations')->max('batch') + 1
        ]);
        
        $this->info("Migration {$migration} marked as run successfully.");
    }
    
    private function markAllMigrationsAsRun()
    {
        // Get all migration files
        $migrationFiles = File::glob(database_path('migrations/*.php'));
        $count = 0;
        $batch = DB::table('migrations')->max('batch') + 1;
        
        foreach ($migrationFiles as $file) {
            $filename = basename($file, '.php');
            
            // Skip if already in migrations table
            if (DB::table('migrations')->where('migration', $filename)->exists()) {
                continue;
            }
            
            // Add to migrations table
            DB::table('migrations')->insert([
                'migration' => $filename,
                'batch' => $batch
            ]);
            
            $count++;
        }
        
        $this->info("{$count} migrations marked as run successfully.");
    }
}