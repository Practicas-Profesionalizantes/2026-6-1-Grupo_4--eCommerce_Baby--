<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdenProduccionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ORDEN_PRODUCCION')->insert([
            ['Id_orden' => 1, 'Id_variante' => 1, 'Cantidad_Planificada' => 100, 'Cantidad_Producida_Real' => 98, 'Fecha_Inicio' => now()->subDays(10), 'Estado_Orden' => 'Finalizado', 'Costo_Total_Estimado' => 800000.00],
            ['Id_orden' => 2, 'Id_variante' => 3, 'Cantidad_Planificada' => 50, 'Cantidad_Producida_Real' => 30, 'Fecha_Inicio' => now()->subDays(5), 'Estado_Orden' => 'Costura', 'Costo_Total_Estimado' => 400000.00],
            ['Id_orden' => 3, 'Id_variante' => 7, 'Cantidad_Planificada' => 20, 'Cantidad_Producida_Real' => 0, 'Fecha_Inicio' => now()->subDays(2), 'Estado_Orden' => 'Corte', 'Costo_Total_Estimado' => 500000.00],
        ]);
    }
}