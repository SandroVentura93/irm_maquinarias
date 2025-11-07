<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbigeosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ubigeos = [
            ['id_ubigeo' => '150101', 'departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'Lima'],
            ['id_ubigeo' => '150102', 'departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'Ancón'],
            ['id_ubigeo' => '150103', 'departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'Ate'],
            ['id_ubigeo' => '150108', 'departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'Breña'],
            ['id_ubigeo' => '150111', 'departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'Comas'],
            ['id_ubigeo' => '150114', 'departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'La Molina'],
            ['id_ubigeo' => '150117', 'departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'Lince'],
            ['id_ubigeo' => '150122', 'departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'Miraflores'],
            ['id_ubigeo' => '150130', 'departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'San Borja'],
            ['id_ubigeo' => '150131', 'departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'San Isidro'],
            ['id_ubigeo' => '150140', 'departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'Santiago de Surco'],
            ['id_ubigeo' => '150142', 'departamento' => 'Lima', 'provincia' => 'Lima', 'distrito' => 'Villa El Salvador'],
            ['id_ubigeo' => '070101', 'departamento' => 'Callao', 'provincia' => 'Callao', 'distrito' => 'Callao'],
            ['id_ubigeo' => '130101', 'departamento' => 'La Libertad', 'provincia' => 'Trujillo', 'distrito' => 'Trujillo'],
            ['id_ubigeo' => '040101', 'departamento' => 'Arequipa', 'provincia' => 'Arequipa', 'distrito' => 'Arequipa'],
        ];

        foreach ($ubigeos as $ubigeo) {
            DB::table('ubigeos')->insert($ubigeo);
        }
    }
}
