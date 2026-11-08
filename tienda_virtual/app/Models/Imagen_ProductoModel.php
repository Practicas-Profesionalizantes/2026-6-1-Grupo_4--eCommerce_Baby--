<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imagen_ProductoModel extends Model
{
    protected $table = 'IMAGEN_PRODUCTO';
    protected $primaryKey = 'Id_imagen';
    protected $fillable = ['Id_producto', 'URL_Imagen', 'Es_Portada'];
    public $timestamps = false;
}
