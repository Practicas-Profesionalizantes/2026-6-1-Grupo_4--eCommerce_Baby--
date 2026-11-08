<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item_CarritoModel extends Model
{
    protected $table = 'ITEM_CARRITO';
    protected $primaryKey = 'Id_item_carrito';
    protected $fillable = ['Id_carrito', 'Id_variante', 'Cantidad'];
    public $timestamps = false;
}
