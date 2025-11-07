<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorias = [
            ['nombre' => 'Motores', 'descripcion' => 'Motores de combustión y eléctricos', 'activo' => true],
            ['nombre' => 'Transmisiones', 'descripcion' => 'Cajas de cambio y transmisiones', 'activo' => true],
            ['nombre' => 'Sistema Hidráulico', 'descripcion' => 'Bombas, cilindros y mangueras hidráulicas', 'activo' => true],
            ['nombre' => 'Frenos', 'descripcion' => 'Sistemas de frenado y componentes', 'activo' => true],
            ['nombre' => 'Llantas y Neumáticos', 'descripcion' => 'Llantas, neumáticos y componentes de rodaje', 'activo' => true],
            ['nombre' => 'Filtros', 'descripcion' => 'Filtros de aire, aceite y combustible', 'activo' => true],
            ['nombre' => 'Sistema Eléctrico', 'descripcion' => 'Componentes eléctricos y electrónicos', 'activo' => true],
            ['nombre' => 'Implementos', 'descripcion' => 'Palas, martillos y herramientas', 'activo' => true],
            ['nombre' => 'Lubricantes', 'descripcion' => 'Aceites y grasas lubricantes', 'activo' => true],
            ['nombre' => 'Repuestos Generales', 'descripcion' => 'Repuestos diversos para maquinaria', 'activo' => true],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categorias')->insert($categoria);
        }
    }
}
