<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('METODO_PAGO')->insert([
            ['Nombre' => 'Efectivo', 'Activo' => true],
            ['Nombre' => 'Transferencia Bancaria', 'Activo' => true],
            ['Nombre' => 'Tarjeta de Crédito', 'Activo' => true],
            ['Nombre' => 'Mercado Pago', 'Activo' => true],
            ['Nombre' => 'Tarjeta de Débito', 'Activo' => true],
        ]);
    }
}