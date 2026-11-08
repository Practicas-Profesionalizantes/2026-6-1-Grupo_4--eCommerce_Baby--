<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PedidoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('PEDIDO')->insert([
            [
                'Id_pedido' => 1,
                'Id_cliente' => 1,
                'Id_metodo_pago' => 1,
                'Id_metodo_envio' => 1,
                'Fecha_Pedido' => now()->subDays(3),
                'Estado_Pedido' => 'Completado',
                'Total' => 58700.00,
            ],
            [
                'Id_pedido' => 2,
                'Id_cliente' => 2,
                'Id_metodo_pago' => 3,
                'Id_metodo_envio' => 2,
                'Fecha_Pedido' => now()->subDays(1),
                'Estado_Pedido' => 'Pendiente',
                'Total' => 45900.00,
            ],
            [
                'Id_pedido' => 3,
                'Id_cliente' => 3,
                'Id_metodo_pago' => 4,
                'Id_metodo_envio' => 3,
                'Fecha_Pedido' => now()->subDays(5),
                'Estado_Pedido' => 'En Proceso',
                'Total' => 32500.00,
            ],
            [
                'Id_pedido' => 4,
                'Id_cliente' => 4,
                'Id_metodo_pago' => 2,
                'Id_metodo_envio' => 4,
                'Fecha_Pedido' => now()->subDays(7),
                'Estado_Pedido' => 'Cancelado',
                'Total' => 125000.00,
            ],
        ]);
    }
}