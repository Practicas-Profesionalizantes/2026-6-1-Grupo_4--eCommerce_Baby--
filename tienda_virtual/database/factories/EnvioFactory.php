<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PedidoModel;

class EnvioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Empresa_Logistica' => fake()->randomElement([
                'Correo Argentino',
                'Andreani',
                'OCA',
                'Correo Express'
            ]),

            'Numero_Tracking' => fake()->bothify('TRK-########'),

            'Estado_Envio' => fake()->randomElement([
                'Pendiente',
                'En Camino',
                'Entregado'
            ]),

            'Fecha_Despacho' => fake()->date(),

            'Id_pedido' => PedidoModel::factory(),
        ];
    }
}