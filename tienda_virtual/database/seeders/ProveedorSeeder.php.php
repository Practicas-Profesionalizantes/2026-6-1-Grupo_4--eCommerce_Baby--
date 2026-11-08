<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('PROVEEDOR')->insert([
            ['Nombre_Empresa' => 'Textiles del Sur SA', 'Contacto' => 'Carlos López', 'Telefono' => '3514567890'],
            ['Nombre_Empresa' => 'Insumos Industriales Ríos', 'Contacto' => 'Ana Martínez', 'Telefono' => '3519876543'],
            ['Nombre_Empresa' => 'Telas Premium Argentina', 'Contacto' => 'Roberto Gómez', 'Telefono' => '3512345678'],
            ['Nombre_Empresa' => 'Distribuidora de Insumos', 'Contacto' => 'María Fernandez', 'Telefono' => '3515678901'],
            ['Nombre_Empresa' => 'Textiles Baby SRL', 'Contacto' => 'Pedro Díaz', 'Telefono' => '3516789012'],
        ]);
    }
}