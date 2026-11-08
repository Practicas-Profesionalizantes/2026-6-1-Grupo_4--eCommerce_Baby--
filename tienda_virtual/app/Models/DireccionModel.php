<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = 'Direccion';
    protected $primaryKey = 'Id_direccion';
    protected $fillable = ['Id_cliente', 'Calle_Numero', 'Ciudad', 'Provincia', 'Codigo_Postal', 'Tipo'];
    public $timestamps = false;
}
