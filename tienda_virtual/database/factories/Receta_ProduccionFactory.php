<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InsumoModel;
use App\Models\Variante_ProductoModel;

class Receta_ProduccionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Cantidad_Necesaria' => fake()->numberBetween(1, 20),

            'Id_insumo' => InsumoModel::factory(),

            'Id_variante' => Variante_ProductoModel::factory(),
        ];
    }
}