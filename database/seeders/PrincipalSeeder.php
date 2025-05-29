<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Principal;
use Illuminate\Support\Facades\Hash;

class PrincipalSeeder extends Seeder
{
    public function run()
    {
        Principal::create([
            'name' => 'CNHS Principal',
            'email' => 'principal@cnhs.edu.ph',
            'password' => Hash::make('cnhs2024'),
        ]);
    }
} 