<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Orden_ProduccionModel;

class Falla_ProduccionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Cantidad_Fallada' => fake()->numberBetween(1, 20),

            'Motivo_Falla' => fake()->randomElement([
                'Error de costura',
                'Material defectuoso',
                'Error de corte',
                'Producto dañado',
                'Falla de maquinaria'
            ]),

            'Costo_Perdido' => fake()->numberBetween(1000, 50000),

            'Id_orden' => Orden_ProduccionModel::factory(),
        ];
    }
}