<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variante_ProductoModel extends Model
{
    protected $table = 'VARIANTE_PRODUCTO';
    protected $primaryKey = 'Id_variante';
    protected $fillable = ['Id_producto', 'SKU', 'Talle', 'Color', 'Precio_Venta', 'Stock_Actual'];
    public $timestamps = false;
}
