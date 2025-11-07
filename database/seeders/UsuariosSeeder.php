<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuarios = [
            [
                'id_rol' => 1, // Administrador
                'nombre' => 'Administrador del Sistema',
                'usuario' => 'admin',
                'contrasena' => Hash::make('admin123'),
                'correo' => 'admin@irmmaquinarias.com',
                'telefono' => '014567890',
                'activo' => true,
            ],
            [
                'id_rol' => 2, // Gerente
                'nombre' => 'Carlos Mendoza García',
                'usuario' => 'cmendoza',
                'contrasena' => Hash::make('password123'),
                'correo' => 'cmendoza@irmmaquinarias.com',
                'telefono' => '987654321',
                'activo' => true,
            ],
            [
                'id_rol' => 3, // Vendedor
                'nombre' => 'Ana Torres Silva',
                'usuario' => 'atorres',
                'contrasena' => Hash::make('password123'),
                'correo' => 'atorres@irmmaquinarias.com',
                'telefono' => '912345678',
                'activo' => true,
            ],
            [
                'id_rol' => 4, // Almacenero
                'nombre' => 'Luis Rodriguez Peña',
                'usuario' => 'lrodriguez',
                'contrasena' => Hash::make('password123'),
                'correo' => 'lrodriguez@irmmaquinarias.com',
                'telefono' => '956789012',
                'activo' => true,
            ],
        ];

        foreach ($usuarios as $usuario) {
            $usuario['created_at'] = Carbon::now();
            $usuario['updated_at'] = Carbon::now();
            DB::table('usuarios')->insert($usuario);
        }
    }
}
