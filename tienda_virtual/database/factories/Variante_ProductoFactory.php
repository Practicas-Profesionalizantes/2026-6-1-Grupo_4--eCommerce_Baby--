<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductoModel;

class Variante_ProductoFactory extends Factory
{

    public function definition(): array
    {
        return [
            'SKU' => fake()->bothify('SKU-####'),

            'Talle' => fake()->randomElement([
                'RN',
                'S',
                'M',
                'L',
                'XL'
            ]),

            'Color' => fake()->randomElement([
                'Blanco',
                'Celeste',
                'Rosa',
                'Verde',
                'Amarillo',
                'Gris'
            ]),

            'Precio_Venta' => fake()->numberBetween(3000, 50000),

            'Stock_Actual' => fake()->numberBetween(0, 100),

            'Id_producto' => ProductoModel::factory(),
        ];
    }
}