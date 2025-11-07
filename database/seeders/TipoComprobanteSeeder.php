<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_comprobantes')->insert([
            ['codigo_sunat' => '01', 'descripcion' => 'Factura'],
            ['codigo_sunat' => '03', 'descripcion' => 'Boleta de Venta'],
            ['codigo_sunat' => '07', 'descripcion' => 'Nota de Crédito'],
            ['codigo_sunat' => '08', 'descripcion' => 'Nota de Débito'],
            ['codigo_sunat' => '09', 'descripcion' => 'Guía de Remisión'],
            ['codigo_sunat' => '12', 'descripcion' => 'Ticket o Cinta Emitido por Máquina Registradora'],
            ['codigo_sunat' => '13', 'descripcion' => 'Documento emitido por Operador de Servicios Electrónicos'],
            ['codigo_sunat' => '14', 'descripcion' => 'Documento emitido por el Sistema de Emisión Electrónica'],
        ]);
    }
}