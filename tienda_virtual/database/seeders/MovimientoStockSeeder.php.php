<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovimientoStockSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('MOVIMIENTO_STOCK')->insert([
            ['Id_movimiento' => 1, 'Id_variante' => 1, 'Id_insumo' => null, 'Tipo_Movimiento' => 'Entrada', 'Cantidad' => 100, 'Motivo' => 'Producción finalizada', 'Fecha_Movimiento' => now()->subDays(8)],
            ['Id_movimiento' => 2, 'Id_variante' => 2, 'Id_insumo' => null, 'Tipo_Movimiento' => 'Salida', 'Cantidad' => 5, 'Motivo' => 'Venta', 'Fecha_Movimiento' => now()->subDays(3)],
            ['Id_movimiento' => 3, 'Id_variante' => null, 'Id_insumo' => 1, 'Tipo_Movimiento' => 'Entrada', 'Cantidad' => 200, 'Motivo' => 'Compra a proveedor', 'Fecha_Movimiento' => now()->subDays(15)],
            ['Id_movimiento' => 4, 'Id_variante' => 3, 'Id_insumo' => null, 'Tipo_Movimiento' => 'Entrada', 'Cantidad' => 50, 'Motivo' => 'Producción', 'Fecha_Movimiento' => now()->subDays(4)],
        ]);
    }
}