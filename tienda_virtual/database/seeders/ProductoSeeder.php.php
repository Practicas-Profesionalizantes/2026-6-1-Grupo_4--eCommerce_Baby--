<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Producto')->insert([
            [
                'Id_producto' => 1,
                'Id_categoria' => 1,
                'Id_promocion' => 1,
                'Nombre' => 'Body de Algodón para Bebé',
                'Descripcion' => 'Body de algodón orgánico, suave y transpirable',
                'Porcentaje_Ganancia' => 40.00,
                'Activo' => true,
                'Destacado' => true,
            ],
            [
                'Id_producto' => 2,
                'Id_categoria' => 1,
                'Id_promocion' => null,
                'Nombre' => 'Remera Manga Corta Infantil',
                'Descripcion' => 'Remera de algodón con estampado divertido',
                'Porcentaje_Ganancia' => 35.00,
                'Activo' => true,
                'Destacado' => false,
            ],
            [
                'Id_producto' => 3,
                'Id_categoria' => 2,
                'Id_promocion' => null,
                'Nombre' => 'Pantalón Jogging para Bebé',
                'Descripcion' => 'Pantalón cómodo con elástico en cintura',
                'Porcentaje_Ganancia' => 38.00,
                'Activo' => true,
                'Destacado' => true,
            ],
            [
                'Id_producto' => 4,
                'Id_categoria' => 2,
                'Id_promocion' => null,
                'Nombre' => 'Pijama Infantil de Franela',
                'Descripcion' => 'Pijama cálido de franela para noches de invierno',
                'Porcentaje_Ganancia' => 45.00,
                'Activo' => true,
                'Destacado' => true,
            ],
            [
                'Id_producto' => 5,
                'Id_categoria' => 3,
                'Id_promocion' => 2,
                'Nombre' => 'Campera Impermeable para Bebé',
                'Descripcion' => 'Campera ligera impermeable con capucha',
                'Porcentaje_Ganancia' => 50.00,
                'Activo' => true,
                'Destacado' => false,
            ],
            [
                'Id_producto' => 6,
                'Id_categoria' => 4,
                'Id_promocion' => null,
                'Nombre' => 'Babero Impermeable',
                'Descripcion' => 'Babero con capa impermeable, fácil de limpiar',
                'Porcentaje_Ganancia' => 30.00,
                'Activo' => true,
                'Destacado' => false,
            ],
            [
                'Id_producto' => 7,
                'Id_categoria' => 5,
                'Id_promocion' => null,
                'Nombre' => 'Zapatillas de Bebé',
                'Descripcion' => 'Zapatillas suaves con suela antideslizante',
                'Porcentaje_Ganancia' => 42.00,
                'Activo' => true,
                'Destacado' => false,
            ],
        ]);
    }
}