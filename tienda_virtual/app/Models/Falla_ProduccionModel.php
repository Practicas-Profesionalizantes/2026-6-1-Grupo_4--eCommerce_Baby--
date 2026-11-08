<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Falla_ProduccionModel extends Model
{
    protected $table = 'FALLA_PRODUCCION';
    protected $primaryKey = 'Id_falla';
    protected $fillable = ['Id_orden', 'Cantidad_Fallada', 'Motivo_Falla', 'Costo_Perdido'];
    public $timestamps = false;
}