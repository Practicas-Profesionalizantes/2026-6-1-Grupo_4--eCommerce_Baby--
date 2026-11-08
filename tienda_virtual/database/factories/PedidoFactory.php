<?php

namespace Database\Factories;

use App\Models\ClienteModel;
use App\Models\DireccionModel;
use App\Models\Metodo_EnvioModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{

    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 10000, 80000);
        $descuento = $this->faker->randomElement([0, 1000, 2500]);
        $costoEnvio = $this->faker->randomElement([0, 3500, 5500]);

        return [
            'Id_cliente' => ClienteModel::factory(),
            'Id_direccion' => DireccionModel::factory(),
            'Id_metodo_envio' => Metodo_EnvioModel::factory(),
            'Subtotal' => $subtotal,
            'Descuento_Aplicado' => $descuento,
            'Total_Final' => ($subtotal - $descuento) + $costoEnvio,
            'Estado_Pedido' => $this->faker->randomElement(['Pendiente', 'Preparacion', 'Enviado', 'Entregado', 'Cancelado']),
            'Fecha_Pedido' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
