<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Cliente')->insert([
            [
                'Id_cliente' => 1,
                'Id_usuario' => 2,
                'Nombre' => 'Juan',
                'Apellido' => 'Pérez',
                'DNI_CUIT' => '20-12345678-9',
                'Telefono' => '3511234567',
                'Condicion_IVA' => 'Consumidor Final',
            ],
            [
                'Id_cliente' => 2,
                'Id_usuario' => 3,
                'Nombre' => 'María',
                'Apellido' => 'González',
                'DNI_CUIT' => '27-87654321-0',
                'Telefono' => '3517654321',
                'Condicion_IVA' => 'Resp. Inscripto',
            ],
        ]);

        DB::table('Cliente')->insert([
            ['Id_usuario' => 5, 'Nombre' => 'Carlos', 'Apellido' => 'López', 'DNI_CUIT' => '20-11111111-1', 'Telefono' => '3511111111', 'Condicion_IVA' => 'Consumidor Final'],
            ['Id_usuario' => 6, 'Nombre' => 'Laura', 'Apellido' => 'Martínez', 'DNI_CUIT' => '27-22222222-2', 'Telefono' => '3512222222', 'Condicion_IVA' => 'Resp. Inscripto'],
            ['Id_usuario' => 7, 'Nombre' => 'Pedro', 'Apellido' => 'Ramírez', 'DNI_CUIT' => '20-33333333-3', 'Telefono' => '3513333333', 'Condicion_IVA' => 'Consumidor Final'],
        ]);
    }
}