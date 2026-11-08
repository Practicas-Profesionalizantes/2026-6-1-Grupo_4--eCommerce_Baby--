<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarritoModel extends Model
{
    protected $table = 'CARRITO';
    protected $primaryKey = 'Id_carrito';
    protected $fillable = ['Id_cliente', 'Fecha_Creacion'];
    public $timestamps = false;
}
