<?php

namespace Database\Factories;
use App\Models\Variante_ProductoModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrdenProduccionFactory extends Factory
{

    public function definition(): array
    {
        return [
            
            'Id_variante' => Variante_ProductoModel::factory(),

            'Cantidad_Planificada' => $this->faker->numberBetween(50, 200),

            'Cantidad_Producida_Real' => 0, 

            'Fecha_Inicio' => $this->faker->date(),

            'Estado_Orden' => $this->faker->randomElement(['Corte', 'Costura', 'Finalizado']),

            'Costo_Total_Estimado' => $this->faker->randomFloat(2, 50000, 300000),
        ];
    }
}
