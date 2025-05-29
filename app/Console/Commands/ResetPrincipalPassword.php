<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Principal;
use Illuminate\Support\Facades\Hash;

class ResetPrincipalPassword extends Command
{
    protected $signature = 'principal:reset-password';
    protected $description = 'Reset the principal password to the default value';

    public function handle()
    {
        $principal = Principal::where('email', 'principal@cnhs.edu.ph')->first();

        if (!$principal) {
            $principal = Principal::create([
                'name' => 'CNHS Principal',
                'email' => 'principal@cnhs.edu.ph',
                'password' => Hash::make('cnhs2024')
            ]);
            $this->info('Principal account created successfully.');
        } else {
            $principal->update([
                'password' => Hash::make('cnhs2024')
            ]);
            $this->info('Principal password reset successfully.');
        }

        $this->info('Email: principal@cnhs.edu.ph');
        $this->info('Password: cnhs2024');
    }
} 