<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CategoriaModel;
use App\Models\PromocionModel;

class ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Nombre' => fake()->randomElement([
                'Body para Bebe',
                'Babero',
                'Pijama Infantil',

            ]),

            'Descripcion' => fake()->sentence(),

            'Porcentaje_Ganancia' => fake()->numberBetween(10, 80),

            'Activo' => fake()->boolean(),

            'Destacado' => fake()->boolean(),

            'Id_categoria' => CategoriaModel::factory(),

            'Id_promocion' => PromocionModel::factory(),
        ];
    }
}