<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ClienteModel;

class BebeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Nombre' => fake()->firstName(),

            'Fecha_Nacimiento' => fake()->date(),

            'Genero' => fake()->randomElement([
                'Masculino',
                'Femenino'
            ]),

            'Id_cliente' => ClienteModel::factory(),
        ];
    }
} 