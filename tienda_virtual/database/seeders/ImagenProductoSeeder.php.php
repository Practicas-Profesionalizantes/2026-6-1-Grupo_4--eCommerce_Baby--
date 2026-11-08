<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImagenProductoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('IMAGEN_PRODUCTO')->insert([
            [
                'Id_imagen' => 1,
                'Id_producto' => 1,
                'URL_Imagen' => 'https://tienda.com/images/body-bebe.jpg',
                'Es_Portada' => true,
            ],
            [
                'Id_imagen' => 2,
                'Id_producto' => 1,
                'URL_Imagen' => 'https://tienda.com/images/body-bebe-2.jpg',
                'Es_Portada' => false,
            ],
            [
                'Id_imagen' => 3,
                'Id_producto' => 3,
                'URL_Imagen' => 'https://tienda.com/images/jogging-bebe.jpg',
                'Es_Portada' => true,
            ],
            [
                'Id_imagen' => 4,
                'Id_producto' => 4,
                'URL_Imagen' => 'https://tienda.com/images/pijama-infantil.jpg',
                'Es_Portada' => true,
            ],
            [
                'Id_imagen' => 5,
                'Id_producto' => 5,
                'URL_Imagen' => 'https://tienda.com/images/campera-impermeable.jpg',
                'Es_Portada' => true,
            ],
            [
                'Id_imagen' => 6,
                'Id_producto' => 6,
                'URL_Imagen' => 'https://tienda.com/images/babero.jpg',
                'Es_Portada' => true,
            ],
        ]);
    }
}