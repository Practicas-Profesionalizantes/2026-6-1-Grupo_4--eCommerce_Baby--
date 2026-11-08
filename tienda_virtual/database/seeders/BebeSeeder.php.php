<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BebeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Bebe')->insert([
            ['Id_bebe' => 1, 'Id_cliente' => 1, 'Nombre' => 'Mateo', 'Fecha_Nacimiento' => '2025-01-15', 'Genero' => 'Masculino'],
            ['Id_bebe' => 2, 'Id_cliente' => 1, 'Nombre' => 'Sofia', 'Fecha_Nacimiento' => '2024-10-20', 'Genero' => 'Femenino'],
            ['Id_bebe' => 3, 'Id_cliente' => 2, 'Nombre' => 'Lucas', 'Fecha_Nacimiento' => '2025-03-10', 'Genero' => 'Masculino'],
            ['Id_bebe' => 4, 'Id_cliente' => 3, 'Nombre' => 'Valentina', 'Fecha_Nacimiento' => '2024-12-05', 'Genero' => 'Femenino'],
        ]);
    }
}