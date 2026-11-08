<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromocionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Promocion')->insert([
            [
                'Nombre' => 'Cyber Monday',
                'Porcentaje_Descuento' => 30.00,
                'Fecha_Inicio' => now(),
                'Fecha_Fin' => now()->addDays(7),
                'Activa' => true,
            ],
            [
                'Nombre' => 'Liquidación Invierno',
                'Porcentaje_Descuento' => 50.00,
                'Fecha_Inicio' => now()->subDays(5),
                'Fecha_Fin' => now()->addDays(10),
                'Activa' => true,
            ],
            [
                'Nombre' => '2x1 en Remeras',
                'Porcentaje_Descuento' => 25.00,
                'Fecha_Inicio' => now()->addDays(3),
                'Fecha_Fin' => now()->addDays(15),
                'Activa' => false,
            ],
        ]);
    }
}