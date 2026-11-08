<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InsumoModel;
use App\Models\Variante_ProductoModel;

class Movimiento_StockFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Tipo_Movimiento' => fake()->randomElement([
                'Entrada',
                'Salida'
            ]),

            'Cantidad' => fake()->numberBetween(1, 100),

            'Motivo' => fake()->randomElement([
                'Compra de insumos',
                'Produccion',
                'Venta',
                'Ajuste de inventario',
                'Producto dañado'
            ]),

            'Fecha_Movimiento' => fake()->date(),

            'Id_insumo' => InsumoModel::factory(),

            'Id_variante' => Variante_ProductoModel::factory(),
        ];
    }
}