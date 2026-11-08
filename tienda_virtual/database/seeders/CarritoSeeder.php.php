<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemCarritoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ITEM_CARRITO')->insert([
            ['Id_item_carrito' => 1, 'Id_carrito' => 1, 'Id_variante' => 1, 'Cantidad' => 2],
            ['Id_item_carrito' => 2, 'Id_carrito' => 1, 'Id_variante' => 6, 'Cantidad' => 1],
            ['Id_item_carrito' => 3, 'Id_carrito' => 2, 'Id_variante' => 9, 'Cantidad' => 1],
            ['Id_item_carrito' => 4, 'Id_carrito' => 2, 'Id_variante' => 4, 'Cantidad' => 3],
            ['Id_item_carrito' => 5, 'Id_carrito' => 3, 'Id_variante' => 10, 'Cantidad' => 5],
            ['Id_item_carrito' => 6, 'Id_carrito' => 4, 'Id_variante' => 2, 'Cantidad' => 1],
            ['Id_item_carrito' => 7, 'Id_carrito' => 4, 'Id_variante' => 8, 'Cantidad' => 2],
        ]);
    }
}