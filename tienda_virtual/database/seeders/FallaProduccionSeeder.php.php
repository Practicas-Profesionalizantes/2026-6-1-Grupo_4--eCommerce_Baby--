<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FallaProduccionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('FALLA_PRODUCCION')->insert([
            ['Id_falla' => 1, 'Id_orden' => 1, 'Cantidad_Fallada' => 2, 'Motivo_Falla' => 'Error de costura', 'Costo_Perdido' => 15000.00],
            ['Id_falla' => 2, 'Id_orden' => 2, 'Cantidad_Fallada' => 5, 'Motivo_Falla' => 'Material defectuoso', 'Costo_Perdido' => 35000.00],
        ]);
    }
}