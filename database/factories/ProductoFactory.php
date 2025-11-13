<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition()
    {
        return [
            'descripcion' => $this->faker->word,
            'precio_venta' => $this->faker->randomFloat(2, 10, 100),
            'stock_actual' => $this->faker->numberBetween(1, 50),
        ];
    }
}