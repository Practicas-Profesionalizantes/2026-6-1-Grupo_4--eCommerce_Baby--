<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProveedorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Nombre_Empresa' => fake()->randomElement([
                'Textiles del Sur',
                'Insumos Argentinos',
                'Distribuidora Central',
                'Materias Primas S.A.',
                'Proveedor Express',
                'Tela y Diseño'
            ]),

            'Contacto' => fake()->name(),

            'Telefono' => fake()->numerify('11########'),
        ];
    }
}