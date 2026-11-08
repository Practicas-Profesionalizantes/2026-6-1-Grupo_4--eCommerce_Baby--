<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta_ProduccionModel extends Model
{
    protected $table = 'RECETA_PRODUCCION';
    protected $primaryKey = 'Id_receta';
    protected $fillable = ['Id_variante', 'Id_insumo', 'Cantidad_Necesaria'];
    public $timestamps = false;
}
