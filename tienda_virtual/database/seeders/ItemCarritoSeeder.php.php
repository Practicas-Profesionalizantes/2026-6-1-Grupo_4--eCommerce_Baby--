<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarritoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('CARRITO')->insert([
            ['Id_carrito' => 1, 'Id_cliente' => 1, 'Fecha_Creacion' => now()],
            ['Id_carrito' => 2, 'Id_cliente' => 2, 'Fecha_Creacion' => now()->subHours(2)],
            ['Id_carrito' => 3, 'Id_cliente' => 3, 'Fecha_Creacion' => now()->subDay()],
            ['Id_carrito' => 4, 'Id_cliente' => 4, 'Fecha_Creacion' => now()->subHours(5)],
            ['Id_carrito' => 5, 'Id_cliente' => 5, 'Fecha_Creacion' => now()->subMinutes(30)],
        ]);
    }
}