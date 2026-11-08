<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ClienteModel;

class DireccionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Calle_Numero' => fake()->streetAddress(),

            'Ciudad' => fake()->city(),

            'Provincia' => fake()->state(),

            'Codigo_Postal' => fake()->postcode(),

            'Tipo' => fake()->randomElement([
                'Casa',
                'Trabajo',
                'Departamento'
            ]),

            'Id_cliente' => ClienteModel::factory(),
        ];
    }
}