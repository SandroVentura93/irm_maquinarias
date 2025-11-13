<?php

namespace Database\Factories;

use App\Models\Moneda;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonedaFactory extends Factory
{
    protected $model = Moneda::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->currencyCode,
            'codigo_iso' => $this->faker->currencyCode,
        ];
    }
}