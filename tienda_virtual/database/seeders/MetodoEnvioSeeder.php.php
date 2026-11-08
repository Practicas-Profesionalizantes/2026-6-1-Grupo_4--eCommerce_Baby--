<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoEnvioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('METODO_ENVIO')->insert([
            ['Nombre' => 'Retiro en Local', 'Costo_Base' => 0, 'Activo' => true],
            ['Nombre' => 'Correo Argentino', 'Costo_Base' => 1500, 'Activo' => true],
            ['Nombre' => 'OCA', 'Costo_Base' => 2500, 'Activo' => true],
            ['Nombre' => 'Andreani', 'Costo_Base' => 3000, 'Activo' => true],
            ['Nombre' => 'Envío Express', 'Costo_Base' => 4000, 'Activo' => true],
        ]);
    }
}