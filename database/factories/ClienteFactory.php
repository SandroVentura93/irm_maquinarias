<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition()
    {
        return [
            'tipo_documento' => $this->faker->randomElement(['DNI', 'RUC']),
            'numero_documento' => $this->faker->unique()->numerify('###########'),
            'nombre' => $this->faker->name,
            'direccion' => $this->faker->address,
            'id_ubigeo' => $this->faker->numberBetween(1, 1000),
            'telefono' => $this->faker->phoneNumber,
            'correo' => $this->faker->unique()->safeEmail,
            'activo' => $this->faker->boolean,
            'tipo_cliente' => $this->faker->randomElement(['Natural', 'Jurídico']),
        ];
    }
}