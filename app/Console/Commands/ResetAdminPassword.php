<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password {email?}';
    protected $description = 'Reset admin password to admin123';

    public function handle()
    {
        $email = $this->argument('email') ?? 'admin@example.com';
        
        $admin = Admin::where('email', $email)->first();
        
        if (!$admin) {
            $this->error("Admin with email {$email} not found!");
            
            // Ask if they want to create a new admin
            if ($this->confirm('Do you want to create a new admin user?')) {
                $name = $this->ask('Enter admin name');
                $username = $this->ask('Enter admin username');
                $email = $this->ask('Enter admin email');
                $password = $this->secret('Enter admin password (hidden)') ?? 'admin123';
                
                $admin = new Admin();
                $admin->name = $name;
                $admin->username = $username;
                $admin->email = $email;
                $admin->password = Hash::make($password);
                $admin->save();
                
                $this->info("Admin created successfully with email: {$email}");
            }
            
            return 1;
        }
        
        $admin->password = Hash::make('admin123');
        $admin->save();
        
        $this->info("Password for admin {$email} has been reset to 'admin123'");
        return 0;
    }
}