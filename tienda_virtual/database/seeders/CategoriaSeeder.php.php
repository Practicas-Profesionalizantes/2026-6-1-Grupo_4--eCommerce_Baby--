<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Categoria')->insert([
            ['Nombre' => 'Remeras', 'Descripcion' => 'Remeras de algodón para bebés y niños'],
            ['Nombre' => 'Pantalones', 'Descripcion' => 'Pantalones y joggers infantiles'],
            ['Nombre' => 'Camperas', 'Descripcion' => 'Camperas y abrigos para bebés'],
            ['Nombre' => 'Accesorios', 'Descripcion' => 'Baberos, gorros y accesorios'],
            ['Nombre' => 'Calzado', 'Descripcion' => 'Zapatos y zapatillas para bebés'],
            ['Nombre' => 'Ropa de Cama', 'Descripcion' => 'Sábanas y mantas para cuna'],
            ['Nombre' => 'Juguetes', 'Descripcion' => 'Juguetes educativos para bebés'],
        ]);
    }
}