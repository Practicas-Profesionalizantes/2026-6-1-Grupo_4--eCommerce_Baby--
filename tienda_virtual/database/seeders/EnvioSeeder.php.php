<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnvioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ENVIO')->insert([
            ['Id_envio' => 1, 'Id_pedido' => 1, 'Empresa_Logistica' => 'Correo Argentino', 'Numero_Tracking' => 'CA123456789AR', 'Estado_Envio' => 'Entregado', 'Fecha_Despacho' => now()->subDays(2)],
            ['Id_envio' => 2, 'Id_pedido' => 2, 'Empresa_Logistica' => 'OCA', 'Numero_Tracking' => 'OCA987654321', 'Estado_Envio' => 'En Camino', 'Fecha_Despacho' => now()->subHours(12)],
        ]);
    }
}