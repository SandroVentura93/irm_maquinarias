<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Usar updateOrCreate para evitar violaciones de llave primaria y permitir re-ejecución segura
        Cliente::updateOrCreate(
            ['numero_documento' => '12345678'],
            [
                'tipo_documento' => 'DNI',
                'razon_social' => null,
                'nombre' => 'Juan Perez',
                'direccion' => 'Av. Siempre Viva 123',
                'telefono' => '987654321',
                'correo' => 'juan.perez@example.com',
                'activo' => true,
            ]
        );
    }
}