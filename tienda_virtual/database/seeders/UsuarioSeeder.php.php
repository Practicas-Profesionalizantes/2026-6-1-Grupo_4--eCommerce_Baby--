<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Usuario')->insert([
            [
                'Id_usuario' => 1,
                'Id_rol' => 1,
                'Email' => 'admin@tienda.com',
                'Password_Hash' => Hash::make('Admin123!'),
                'Fecha_Registro' => now(),
            ],
            [
                'Id_usuario' => 2,
                'Id_rol' => 2,
                'Email' => 'cliente1@email.com',
                'Password_Hash' => Hash::make('Cliente123!'),
                'Fecha_Registro' => now(),
            ],
            [
                'Id_usuario' => 3,
                'Id_rol' => 2,
                'Email' => 'cliente2@email.com',
                'Password_Hash' => Hash::make('Cliente123!'),
                'Fecha_Registro' => now(),
            ],
            [
                'Id_usuario' => 4,
                'Id_rol' => 3,
                'Email' => 'fabrica@produccion.com',
                'Password_Hash' => Hash::make('Fabrica123!'),
                'Fecha_Registro' => now(),
            ],
        ]);

        DB::table('Usuario')->insert([
            ['Id_rol' => 2, 'Email' => 'maria@email.com', 'Password_Hash' => Hash::make('password'), 'Fecha_Registro' => now()],
            ['Id_rol' => 2, 'Email' => 'carlos@email.com', 'Password_Hash' => Hash::make('password'), 'Fecha_Registro' => now()],
            ['Id_rol' => 2, 'Email' => 'laura@email.com', 'Password_Hash' => Hash::make('password'), 'Fecha_Registro' => now()],
            ['Id_rol' => 2, 'Email' => 'pedro@email.com', 'Password_Hash' => Hash::make('password'), 'Fecha_Registro' => now()],
            ['Id_rol' => 2, 'Email' => 'ana@email.com', 'Password_Hash' => Hash::make('password'), 'Fecha_Registro' => now()],
        ]);
    }
}