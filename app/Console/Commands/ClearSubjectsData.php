<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearSubjectsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subjects:clear-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all subjects and their related data (grades, assignments, etc.).';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! $this->confirm('This will permanently delete ALL subjects, grades, teacher assignments, and student-subject links. This action cannot be undone. Are you sure?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $this->info('Disabling foreign key checks and clearing all subject-related data...');

        Schema::disableForeignKeyConstraints();

        $tables = ['grades', 'student_subject', 'teacher_assignments', 'subjects'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->line("Truncating `{$table}` table...");
                DB::table($table)->truncate();
            } else {
                $this->warn("Table `{$table}` not found. Skipping.");
            }
        }

        Schema::enableForeignKeyConstraints();

        $this->info('All subject-related data has been successfully cleared.');
        return 0;
    }
}
