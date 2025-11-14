<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clientes = [
            [
                'tipo_documento' => 'RUC',
                'numero_documento' => '20556677889',
                'nombre' => 'Constructora Los Andes S.A.C.',
                'direccion' => 'Av. Los Constructores 450, San Isidro',
                'telefono' => '014445566',
                'correo' => 'proyectos@losandes.pe',
                'activo' => true,
            ],
            [
                'tipo_documento' => 'RUC',
                'numero_documento' => '20667788990',
                'nombre' => 'Minera del Norte E.I.R.L.',
                'direccion' => 'Jr. Minería 123, Trujillo',
                'telefono' => '044556677',
                'correo' => 'ventas@mineranorte.pe',
                'activo' => true,
            ],
            [
                'tipo_documento' => 'DNI',
                'numero_documento' => '12345678',
                'nombre' => 'Juan Carlos Pérez García',
                'direccion' => 'Av. Las Flores 789, Miraflores',
                'telefono' => '987654321',
                'correo' => 'jperez@gmail.com',
                'activo' => true,
            ],
            [
                'tipo_documento' => 'DNI',
                'numero_documento' => '87654321',
                'nombre' => 'María Fernanda López Silva',
                'direccion' => 'Jr. Los Pinos 456, San Borja',
                'telefono' => '912345678',
                'correo' => 'mlopez@hotmail.com',
                'activo' => true,
            ],
            [
                'tipo_documento' => 'RUC',
                'numero_documento' => '20778899001',
                'nombre' => 'Transportes Rápidos S.R.L.',
                'direccion' => 'Av. Transportistas 321, Ate',
                'telefono' => '016667788',
                'correo' => 'administracion@rapidostransportes.pe',
                'activo' => true,
            ],
            [
                'tipo_documento' => 'DNI',
                'numero_documento' => '45678912',
                'nombre' => 'Roberto Antonio Mamani Quispe',
                'direccion' => 'Calle Los Artesanos 654, Villa El Salvador',
                'telefono' => '956789123',
                'correo' => 'rmamani@outlook.com',
                'activo' => true,
            ],
        ];

        foreach ($clientes as $cliente) {
            $cliente['created_at'] = Carbon::now();
            $cliente['updated_at'] = Carbon::now();
            DB::table('clientes')->insert($cliente);
        }
    }
}
