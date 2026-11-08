<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class Metodo_PagoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Nombre' => fake()->randomElement([
                'Efectivo',
                'Transferencia Bancaria',
                'Tarjeta de Debito',
                'Tarjeta de Credito',
                'Mercado Pago'
            ]),

            'Activo' => fake()->boolean(),
        ];
    }
}