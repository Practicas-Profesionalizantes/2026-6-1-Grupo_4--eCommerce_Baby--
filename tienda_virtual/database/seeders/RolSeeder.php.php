<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Rol')->insert([
            ['Id_rol' => 1, 'Nombre_Rol' => 'Admin'],
            ['Id_rol' => 2, 'Nombre_Rol' => 'Cliente'],
            ['Id_rol' => 3, 'Nombre_Rol' => 'Fabrica'],
        ]);
    }
}