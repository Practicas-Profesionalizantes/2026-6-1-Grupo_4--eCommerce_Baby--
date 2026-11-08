<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DireccionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Direccion')->insert([
            [
                'Id_direccion' => 1,
                'Id_cliente' => 1,
                'Calle_Numero' => 'Av. Libertad 123',
                'Ciudad' => 'Córdoba',
                'Provincia' => 'Córdoba',
                'Codigo_Postal' => '5000',
                'Tipo' => 'Envio',
            ],
            [
                'Id_direccion' => 2,
                'Id_cliente' => 1,
                'Calle_Numero' => 'San Martín 456',
                'Ciudad' => 'Córdoba',
                'Provincia' => 'Córdoba',
                'Codigo_Postal' => '5000',
                'Tipo' => 'Facturacion',
            ],
            [
                'Id_direccion' => 3,
                'Id_cliente' => 2,
                'Calle_Numero' => 'Belgrano 789',
                'Ciudad' => 'Buenos Aires',
                'Provincia' => 'Buenos Aires',
                'Codigo_Postal' => '1000',
                'Tipo' => 'Envio',
            ],
        ]);

        DB::table('Direccion')->insert([
            ['Id_cliente' => 3, 'Calle_Numero' => 'Rivadavia 555', 'Ciudad' => 'Rosario', 'Provincia' => 'Santa Fe', 'Codigo_Postal' => '2000', 'Tipo' => 'Envio'],
            ['Id_cliente' => 4, 'Calle_Numero' => 'Mitre 888', 'Ciudad' => 'Mendoza', 'Provincia' => 'Mendoza', 'Codigo_Postal' => '5500', 'Tipo' => 'Facturacion'],
            ['Id_cliente' => 5, 'Calle_Numero' => 'Sarmiento 321', 'Ciudad' => 'Córdoba', 'Provincia' => 'Córdoba', 'Codigo_Postal' => '5000', 'Tipo' => 'Envio'],
        ]);
    }
}