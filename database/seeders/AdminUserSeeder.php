<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Note: These passwords are for testing only.
     * In production, use strong passwords and consider using environment variables.
     */
    public function run(): void
    {
        // Crear usuario administrador de prueba
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@saborestilo.com',
            'password' => Hash::make('admin123'), // Change in production
            'email_verified_at' => now(),
        ]);

        // Crear otro usuario de prueba
        User::create([
            'name' => 'Usuario Demo',
            'email' => 'demo@saborestilo.com',
            'password' => Hash::make('demo123'), // Change in production
            'email_verified_at' => now(),
        ]);
    }
}
