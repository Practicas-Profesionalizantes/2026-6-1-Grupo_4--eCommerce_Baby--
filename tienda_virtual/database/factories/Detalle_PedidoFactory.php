<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PedidoModel;
use App\Models\Variante_ProductoModel;

class Detalle_PedidoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Cantidad' => fake()->numberBetween(1, 5),

            'Precio_Unitario_Cobrado' => fake()->numberBetween(1000, 15000),

            'Costo_Unitario_Fabricacion' => fake()->numberBetween(500, 10000),

            'Id_pedido' => PedidoModel::factory(),

            'Id_variante' => Variante_ProductoModel::factory(),
        ];
    }
}