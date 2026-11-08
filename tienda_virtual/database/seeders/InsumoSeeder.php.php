<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsumoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('INSUMO')->insert([
            ['Id_proveedor' => 1, 'Nombre' => 'Tela Algodón Orgánico', 'Unidad_Medida' => 'Metros', 'Costo_Actual' => 3500.00, 'Stock_Disponible' => 1000],
            ['Id_proveedor' => 1, 'Nombre' => 'Tela Franela', 'Unidad_Medida' => 'Metros', 'Costo_Actual' => 4200.00, 'Stock_Disponible' => 500],
            ['Id_proveedor' => 2, 'Nombre' => 'Hilo Poliéster', 'Unidad_Medida' => 'Carretes', 'Costo_Actual' => 250.00, 'Stock_Disponible' => 2000],
            ['Id_proveedor' => 3, 'Nombre' => 'Botones de Madera', 'Unidad_Medida' => 'Docenas', 'Costo_Actual' => 1200.00, 'Stock_Disponible' => 150],
            ['Id_proveedor' => 2, 'Nombre' => 'Cierre Metálico', 'Unidad_Medida' => 'Unidades', 'Costo_Actual' => 800.00, 'Stock_Disponible' => 300],
            ['Id_proveedor' => 1, 'Nombre' => 'Tela Impermeable', 'Unidad_Medida' => 'Metros', 'Costo_Actual' => 5000.00, 'Stock_Disponible' => 200],
            ['Id_proveedor' => 3, 'Nombre' => 'Relleno para Camperas', 'Unidad_Medida' => 'Kg', 'Costo_Actual' => 2800.00, 'Stock_Disponible' => 100],
        ]);
    }
}