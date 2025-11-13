<?php

namespace Database\Factories;

use App\Models\TipoComprobante;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoComprobanteFactory extends Factory
{
    protected $model = TipoComprobante::class;

    public function definition()
    {
        return [
            'descripcion' => $this->faker->word,
        ];
    }
}