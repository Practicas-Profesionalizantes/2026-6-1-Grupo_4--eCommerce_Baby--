<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Nombre' => fake()->word(),
            'Descripcion' => fake()->sentence(),
        ];
    }
}