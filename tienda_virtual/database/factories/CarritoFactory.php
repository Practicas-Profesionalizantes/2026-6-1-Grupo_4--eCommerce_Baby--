<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ClienteModel;


class CarritoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Fecha_Creacion' => fake()->dateTimeBetween('-1 year', 'now'),
            'Id_cliente' => ClienteModel::factory(),
        ];
    }
}