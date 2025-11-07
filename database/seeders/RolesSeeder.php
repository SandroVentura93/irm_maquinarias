<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso total al sistema'],
            ['nombre' => 'Gerente', 'descripcion' => 'Acceso a reportes y configuración'],
            ['nombre' => 'Vendedor', 'descripcion' => 'Acceso a ventas y clientes'],
            ['nombre' => 'Almacenero', 'descripcion' => 'Acceso a inventario y productos'],
            ['nombre' => 'Contador', 'descripcion' => 'Acceso a reportes financieros'],
        ];

        foreach ($roles as $rol) {
            DB::table('roles')->insert($rol);
        }
    }
}
