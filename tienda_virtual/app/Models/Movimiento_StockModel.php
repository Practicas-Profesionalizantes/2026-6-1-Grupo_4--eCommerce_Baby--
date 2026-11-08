<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento_StockModel extends Model
{
    protected $table = 'MOVIMIENTO_STOCK';
    protected $primaryKey = 'Id_movimiento';
    protected $fillable = ['Id_variante', 'Id_insumo', 'Tipo_Movimiento', 'Cantidad', 'Motivo', 'Fecha_Movimiento'];
    public $timestamps = false;
}