<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsumoModel extends Model
{
    protected $table = 'INSUMO';
    protected $primaryKey = 'Id_insumo';
    protected $fillable = ['Id_proveedor', 'Nombre', 'Unidad_Medida', 'Costo_Actual', 'Stock_Disponible'];
    public $timestamps = false;
}
