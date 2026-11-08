<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PromocionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Nombre' => fake()->randomElement([
                'Oferta de Verano',
                'Descuento Especial',
                'Promo Bebes',
                'Semana del Niño',
                'Black Friday',
                'Liquidacion'
            ]),

            'Porcentaje_Descuento' => fake()->numberBetween(5, 50),

            'Fecha_Inicio' => fake()->dateTimeBetween('-1 month', 'now'),

            'Activa' => fake()->boolean(),

            'Fecha_Fin' => fake()->dateTimeBetween('now', '+2 months'),
        ];
    }
}