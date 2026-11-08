<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductoModel;

class Imagen_ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'URL_Imagen' => fake()->imageUrl(),

            'Es_Portada' => fake()->randomElement([
                true,
                false
            ]),

            'Id_producto' => ProductoModel::factory(),
        ];
    }
}