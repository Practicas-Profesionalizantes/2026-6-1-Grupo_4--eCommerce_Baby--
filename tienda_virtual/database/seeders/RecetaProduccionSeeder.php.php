<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecetaProduccionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('RECETA_PRODUCCION')->insert([
            ['Id_variante' => 1, 'Id_insumo' => 1, 'Cantidad_Necesaria' => 1.50],
            ['Id_variante' => 1, 'Id_insumo' => 3, 'Cantidad_Necesaria' => 0.50],
            ['Id_variante' => 2, 'Id_insumo' => 1, 'Cantidad_Necesaria' => 1.50],
            ['Id_variante' => 2, 'Id_insumo' => 3, 'Cantidad_Necesaria' => 0.50],
            ['Id_variante' => 3, 'Id_insumo' => 1, 'Cantidad_Necesaria' => 1.50],
            ['Id_variante' => 3, 'Id_insumo' => 3, 'Cantidad_Necesaria' => 0.50],
            ['Id_variante' => 4, 'Id_insumo' => 1, 'Cantidad_Necesaria' => 1.00],
            ['Id_variante' => 5, 'Id_insumo' => 1, 'Cantidad_Necesaria' => 1.00],
            ['Id_variante' => 6, 'Id_insumo' => 1, 'Cantidad_Necesaria' => 2.00],
            ['Id_variante' => 6, 'Id_insumo' => 3, 'Cantidad_Necesaria' => 0.75],
            ['Id_variante' => 7, 'Id_insumo' => 1, 'Cantidad_Necesaria' => 2.00],
            ['Id_variante' => 7, 'Id_insumo' => 3, 'Cantidad_Necesaria' => 0.75],
            ['Id_variante' => 8, 'Id_insumo' => 2, 'Cantidad_Necesaria' => 2.50],
            ['Id_variante' => 8, 'Id_insumo' => 3, 'Cantidad_Necesaria' => 1.00],
            ['Id_variante' => 9, 'Id_insumo' => 1, 'Cantidad_Necesaria' => 3.00],
            ['Id_variante' => 9, 'Id_insumo' => 5, 'Cantidad_Necesaria' => 1.00],
            ['Id_variante' => 9, 'Id_insumo' => 3, 'Cantidad_Necesaria' => 1.50],
        ]);
    }
}