<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PedidoModel;
use App\Models\Metodo_PagoModel;

class Transaccion_PagoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Monto' => fake()->numberBetween(1000, 100000),

            'Estado_Pago' => fake()->randomElement([
                'Pendiente',
                'Aprobado',
                'Rechazado'
            ]),

            'Referencia_Pasarela' => fake()->bothify('PAY-########'),

            'Fecha_Pago' => fake()->date(),

            'Id_pedido' => PedidoModel::factory(),

            'Id_metodo_pago' => Metodo_PagoModel::factory(),
        ];
    }
}