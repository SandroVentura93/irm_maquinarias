<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@irm.com',
            'password' => Hash::make('123456789'),
            'email_verified_at' => now(),
        ]);

        // Crear usuario de prueba
        User::create([
            'name' => 'Usuario Demo',
            'email' => 'demo@irm.com',
            'password' => Hash::make('123456789'),
            'email_verified_at' => now(),
        ]);

        echo "Usuarios creados exitosamente:\n";
        echo "- admin@irm.com (contraseña: 123456789)\n";
        echo "- demo@irm.com (contraseña: 123456789)\n";
    }
}