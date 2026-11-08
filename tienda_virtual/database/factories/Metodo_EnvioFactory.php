<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class Metodo_EnvioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Nombre' => fake()->randomElement([
                'Retiro en Local',
                'Correo Argentino',
                'Andreani',
                'OCA',
                'Envio Express'
            ]),

            'Costo_Base' => fake()->numberBetween(500, 5000),

            'Activo' => fake()->boolean(),
        ];
    }
}