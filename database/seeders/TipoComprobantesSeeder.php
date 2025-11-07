<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoComprobantesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tiposComprobantes = [
            ['codigo_sunat' => '01', 'descripcion' => 'Factura'],
            ['codigo_sunat' => '03', 'descripcion' => 'Boleta de Venta'],
            ['codigo_sunat' => '07', 'descripcion' => 'Nota de Crédito'],
            ['codigo_sunat' => '08', 'descripcion' => 'Nota de Débito'],
            ['codigo_sunat' => '09', 'descripcion' => 'Guía de Remisión'],
            ['codigo_sunat' => '12', 'descripcion' => 'Ticket de Máquina Registradora'],
            ['codigo_sunat' => '14', 'descripcion' => 'Recibo por Honorarios'],
        ];

        foreach ($tiposComprobantes as $tipo) {
            DB::table('tipo_comprobantes')->insert($tipo);
        }
    }
}
