<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden_ProduccionModel extends Model
{
    protected $table = 'ORDEN_PRODUCCION';
    protected $primaryKey = 'Id_orden';
    protected $fillable = ['Id_variante', 'Cantidad_Planificada', 'Cantidad_Producida_Real', 'Fecha_Inicio', 'Estado_Orden', 'Costo_Total_Estimado'];
    public $timestamps = false;
}
