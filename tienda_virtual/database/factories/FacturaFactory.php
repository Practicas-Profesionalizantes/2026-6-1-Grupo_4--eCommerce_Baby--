<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PedidoModel;

class FacturaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Tipo_Comprobante' => fake()->randomElement([
                'Factura A',
                'Factura B',
                'Factura C'
            ]),

            'Nro_Comprobante' => fake()->unique()->numerify('########'),

            'CUIT_Receptor' => fake()->numerify('20#########'),

            'Fecha_Emision' => fake()->date(),

            'Id_pedido' => PedidoModel::factory(),
        ];
    }
}