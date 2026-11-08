<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacturaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('FACTURA')->insert([
            ['Id_factura' => 1, 'Id_pedido' => 1, 'Tipo_Comprobante' => 'B', 'Nro_Comprobante' => '0001-12345678', 'CUIT_Receptor' => '20-12345678-9', 'Fecha_Emision' => now()->subDays(3)],
            ['Id_factura' => 2, 'Id_pedido' => 2, 'Tipo_Comprobante' => 'A', 'Nro_Comprobante' => '0002-87654321', 'CUIT_Receptor' => '27-87654321-0', 'Fecha_Emision' => now()->subDays(1)],
        ]);
    }
}