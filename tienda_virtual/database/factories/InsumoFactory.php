<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProveedorModel;

class InsumoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Nombre' => fake()->randomElement([
                'Tela Algodon',
                'Hilo',
                'Botones',
                'Cierre',
                'Etiqueta',
                'Velcro',
                'Relleno'
            ]),

            'Unidad_Medida' => fake()->randomElement([
                'Kg',
                'Metro',
                'Unidad',
                'Caja'
            ]),

            'Costo_Actual' => fake()->numberBetween(100, 5000),

            'Stock_Disponible' => fake()->numberBetween(10, 500),

            'Id_proveedor' => ProveedorModel::factory(),
        ];
    }
}