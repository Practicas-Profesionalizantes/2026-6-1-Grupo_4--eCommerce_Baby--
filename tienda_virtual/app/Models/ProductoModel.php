<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoModel extends Model
{
    protected $table = 'Producto';
    protected $primaryKey = 'Id_producto';
    protected $fillable = ['Id_categoria', 'Id_promocion', 'Nombre', 'Descripcion', 'Porcentaje_Ganancia', 'Activo', 'Destacado'];
    public $timestamps = false;
}
