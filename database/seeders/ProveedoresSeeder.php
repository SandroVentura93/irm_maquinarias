<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProveedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $proveedores = [
            [
                'razon_social' => 'Ferreyros S.A.',
                'tipo_documento' => 'RUC',
                'numero_documento' => '20100027092',
                'contacto' => 'Carlos Mendoza',
                'telefono' => '014116000',
                'correo' => 'ventas@ferreyros.com.pe',
                'direccion' => 'Av. Cristóbal de Peralta Norte 820, Lima',
                'activo' => true,
            ],
            [
                'razon_social' => 'Komatsu Mitsui Maquinarias Perú S.A.',
                'tipo_documento' => 'RUC',
                'numero_documento' => '20259155834',
                'contacto' => 'Ana Torres',
                'telefono' => '015117300',
                'correo' => 'info@kmmp.com.pe',
                'direccion' => 'Av. Argentina 4793, Callao',
                'activo' => true,
            ],
            [
                'razon_social' => 'Volvo Perú S.A.',
                'tipo_documento' => 'RUC',
                'numero_documento' => '20100082237',
                'contacto' => 'Luis Rodriguez',
                'telefono' => '016254400',
                'correo' => 'contacto@volvo.pe',
                'direccion' => 'Av. El Derby 254, Santiago de Surco',
                'activo' => true,
            ],
            [
                'razon_social' => 'Maquinarias S.A.',
                'tipo_documento' => 'RUC',
                'numero_documento' => '20345678901',
                'contacto' => 'Maria Silva',
                'telefono' => '014567890',
                'correo' => 'ventas@maquinarias.pe',
                'direccion' => 'Av. Industrial 123, Ate',
                'activo' => true,
            ],
            [
                'razon_social' => 'Repuestos Lima E.I.R.L.',
                'tipo_documento' => 'RUC',
                'numero_documento' => '20567890123',
                'contacto' => 'Pedro Gonzales',
                'telefono' => '016789012',
                'correo' => 'info@repuestoslima.pe',
                'direccion' => 'Jr. Huánuco 890, La Victoria',
                'activo' => true,
            ],
        ];

        foreach ($proveedores as $proveedor) {
            $proveedor['created_at'] = Carbon::now();
            $proveedor['updated_at'] = Carbon::now();
            DB::table('proveedores')->insert($proveedor);
        }
    }
}
