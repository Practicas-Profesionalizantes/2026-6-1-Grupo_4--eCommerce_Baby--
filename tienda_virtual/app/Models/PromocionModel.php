<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromocionModel extends Model
{
    protected $table = 'Promocion';
    protected $primaryKey = 'Id_promocion';
    protected $fillable = ['Nombre', 'Porcentaje_Descuento', 'Fecha_Inicio', 'Fecha_Fin', 'Activa'];
    public $timestamps = false;
}
