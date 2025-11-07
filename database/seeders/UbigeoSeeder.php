<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbigeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('ubigeos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('ubigeos')->insert([
            ['id_ubigeo' => '060101', 'departamento' => 'Cajamarca', 'provincia' => 'Cajamarca', 'distrito' => 'Cajamarca'],
            ['id_ubigeo' => '060102', 'departamento' => 'Cajamarca', 'provincia' => 'Cajamarca', 'distrito' => 'Baños del Inca'],
            ['id_ubigeo' => '060103', 'departamento' => 'Cajamarca', 'provincia' => 'Cajamarca', 'distrito' => 'Los Baños'],
            ['id_ubigeo' => '060104', 'departamento' => 'Cajamarca', 'provincia' => 'Celendín', 'distrito' => 'Celendín'],
            ['id_ubigeo' => '060105', 'departamento' => 'Cajamarca', 'provincia' => 'Chota', 'distrito' => 'Chota'],
        ]);
    }
}