<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetallePedidoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('DETALLE_PEDIDO')->insert([
            ['Id_detalle' => 1, 'Id_pedido' => 1, 'Id_variante' => 1, 'Cantidad' => 2, 'Precio_Unitario_Cobrado' => 14900.00, 'Costo_Unitario_Fabricacion' => 8000.00],
            ['Id_detalle' => 2, 'Id_pedido' => 1, 'Id_variante' => 6, 'Cantidad' => 1, 'Precio_Unitario_Cobrado' => 28900.00, 'Costo_Unitario_Fabricacion' => 15000.00],
            ['Id_detalle' => 3, 'Id_pedido' => 2, 'Id_variante' => 9, 'Cantidad' => 1, 'Precio_Unitario_Cobrado' => 45900.00, 'Costo_Unitario_Fabricacion' => 25000.00],
            ['Id_detalle' => 4, 'Id_pedido' => 3, 'Id_variante' => 10, 'Cantidad' => 5, 'Precio_Unitario_Cobrado' => 4500.00, 'Costo_Unitario_Fabricacion' => 2000.00],
            ['Id_detalle' => 5, 'Id_pedido' => 3, 'Id_variante' => 4, 'Cantidad' => 2, 'Precio_Unitario_Cobrado' => 7500.00, 'Costo_Unitario_Fabricacion' => 3500.00],
            ['Id_detalle' => 6, 'Id_pedido' => 4, 'Id_variante' => 8, 'Cantidad' => 3, 'Precio_Unitario_Cobrado' => 15000.00, 'Costo_Unitario_Fabricacion' => 8000.00],
        ]);
    }
}