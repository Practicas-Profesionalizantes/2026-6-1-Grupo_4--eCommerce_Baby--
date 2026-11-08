<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VarianteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('VARIANTE_PRODUCTO')->insert([
            [
                'Id_variante' => 1,
                'Id_producto' => 1,
                'SKU' => 'BOD-001-RN',
                'Talle' => 'RN',
                'Color' => 'Blanco',
                'Precio_Venta' => 8500.00,
                'Stock_Actual' => 30,
            ],
            [
                'Id_variante' => 2,
                'Id_producto' => 1,
                'SKU' => 'BOD-001-S',
                'Talle' => 'S',
                'Color' => 'Celeste',
                'Precio_Venta' => 8900.00,
                'Stock_Actual' => 25,
            ],
            [
                'Id_variante' => 3,
                'Id_producto' => 1,
                'SKU' => 'BOD-001-M',
                'Talle' => 'M',
                'Color' => 'Rosa',
                'Precio_Venta' => 9200.00,
                'Stock_Actual' => 20,
            ],
            [
                'Id_variante' => 4,
                'Id_producto' => 2,
                'SKU' => 'REM-002-S',
                'Talle' => 'S',
                'Color' => 'Amarillo',
                'Precio_Venta' => 7500.00,
                'Stock_Actual' => 15,
            ],
            [
                'Id_variante' => 5,
                'Id_producto' => 2,
                'SKU' => 'REM-002-M',
                'Talle' => 'M',
                'Color' => 'Verde',
                'Precio_Venta' => 7800.00,
                'Stock_Actual' => 18,
            ],
            [
                'Id_variante' => 6,
                'Id_producto' => 3,
                'SKU' => 'PAN-003-RN',
                'Talle' => 'RN',
                'Color' => 'Gris',
                'Precio_Venta' => 12000.00,
                'Stock_Actual' => 12,
            ],
            [
                'Id_variante' => 7,
                'Id_producto' => 3,
                'SKU' => 'PAN-003-S',
                'Talle' => 'S',
                'Color' => 'Azul',
                'Precio_Venta' => 12500.00,
                'Stock_Actual' => 10,
            ],
            [
                'Id_variante' => 8,
                'Id_producto' => 4,
                'SKU' => 'PIJ-004-RN',
                'Talle' => 'RN',
                'Color' => 'Blanco',
                'Precio_Venta' => 15000.00,
                'Stock_Actual' => 8,
            ],
            [
                'Id_variante' => 9,
                'Id_producto' => 5,
                'SKU' => 'CAM-005-S',
                'Talle' => 'S',
                'Color' => 'Gris',
                'Precio_Venta' => 25000.00,
                'Stock_Actual' => 5,
            ],
            [
                'Id_variante' => 10,
                'Id_producto' => 6,
                'SKU' => 'BAB-006-UN',
                'Talle' => null,
                'Color' => 'Celeste',
                'Precio_Venta' => 4500.00,
                'Stock_Actual' => 40,
            ],
        ]);
    }
}