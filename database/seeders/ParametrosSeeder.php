<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParametrosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parametros = [
            ['nombre' => 'IGV', 'valor' => '0.18', 'descripcion' => 'Impuesto General a las Ventas'],
            ['nombre' => 'MONEDA_DEFAULT', 'valor' => '1', 'descripcion' => 'Moneda por defecto (Sol Peruano)'],
            ['nombre' => 'STOCK_CRITICO', 'valor' => '5', 'descripcion' => 'Cantidad mínima para alertas de stock'],
            ['nombre' => 'EMPRESA_RUC', 'valor' => '20570639553', 'descripcion' => 'RUC de la empresa'],
            ['nombre' => 'EMPRESA_RAZON_SOCIAL', 'valor' => 'IRM Maquinarias S.R.L.', 'descripcion' => 'Razón social de la empresa'],
        ];

        foreach ($parametros as $parametro) {
            DB::table('parametros')->insert($parametro);
        }
    }
}
