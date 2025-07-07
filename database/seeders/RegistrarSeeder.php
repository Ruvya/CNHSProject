<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegistrarSeeder extends Seeder
{
    public function run()
    {
        // First, add the registrar_secret column if it doesn't exist
        if (!\Illuminate\Support\Facades\Schema::hasColumn('registrars', 'registrar_secret')) {
            \Illuminate\Support\Facades\Schema::table('registrars', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('registrar_secret')->nullable();
            });
        }

        // Delete existing registrar if exists
        DB::table('registrars')->where('email', 'registrar@cnhs.edu.ph')->delete();

        // Insert new registrar with correct field names
        DB::table('registrars')->insert([
            'first_name' => 'CNHS',
            'last_name' => 'Registrar',
            'email' => 'registrar@cnhs.edu.ph',
            'password' => Hash::make('password123'),
            'phone' => '09123456789',
            'address' => 'Camarines Norte High School',
            'profile_picture' => null,
            'registrar_secret' => 'letmein',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}