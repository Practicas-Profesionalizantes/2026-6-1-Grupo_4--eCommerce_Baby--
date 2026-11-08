<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CarritoModel;
use App\Models\Variante_ProductoModel;

class Item_CarritoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Cantidad' => fake()->numberBetween(1, 10),

            'Id_carrito' => CarritoModel::factory(),

            'Id_variante' => Variante_ProductoModel::factory(),
        ];
    }
}