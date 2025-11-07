<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MarcasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marcas = [
            ['nombre' => 'Caterpillar', 'descripcion' => 'Líder mundial en maquinaria de construcción y minería', 'activo' => true],
            ['nombre' => 'Komatsu', 'descripcion' => 'Fabricante japonés de maquinaria pesada', 'activo' => true],
            ['nombre' => 'Volvo', 'descripcion' => 'Maquinaria sueca de alta calidad', 'activo' => true],
            ['nombre' => 'JCB', 'descripcion' => 'Fabricante británico de equipos de construcción', 'activo' => true],
            ['nombre' => 'Case', 'descripcion' => 'Maquinaria estadounidense para construcción', 'activo' => true],
            ['nombre' => 'Liebherr', 'descripcion' => 'Fabricante alemán de maquinaria pesada', 'activo' => true],
            ['nombre' => 'Hitachi', 'descripcion' => 'Tecnología japonesa en maquinaria', 'activo' => true],
            ['nombre' => 'John Deere', 'descripcion' => 'Maquinaria agrícola y de construcción', 'activo' => true],
            ['nombre' => 'Hyundai', 'descripcion' => 'Maquinaria coreana de construcción', 'activo' => true],
            ['nombre' => 'Doosan', 'descripcion' => 'Fabricante coreano de equipos pesados', 'activo' => true],
            ['nombre' => 'Sany', 'descripcion' => 'Maquinaria china en crecimiento', 'activo' => true],
            ['nombre' => 'XCMG', 'descripcion' => 'Fabricante chino de maquinaria de construcción', 'activo' => true],
        ];

        foreach ($marcas as $marca) {
            $marca['created_at'] = Carbon::now();
            $marca['updated_at'] = Carbon::now();
            DB::table('marcas')->insert($marca);
        }
    }
}
