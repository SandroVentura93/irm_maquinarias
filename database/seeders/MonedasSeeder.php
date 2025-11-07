<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonedasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $monedas = [
            ['nombre' => 'Sol Peruano', 'simbolo' => 'S/', 'codigo_iso' => 'PEN'],
            ['nombre' => 'Dólar Estadounidense', 'simbolo' => '$', 'codigo_iso' => 'USD'],
            ['nombre' => 'Euro', 'simbolo' => '€', 'codigo_iso' => 'EUR'],
        ];

        foreach ($monedas as $moneda) {
            DB::table('monedas')->insert($moneda);
        }
    }
}
