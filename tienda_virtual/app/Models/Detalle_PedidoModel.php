<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle_PedidoModel extends Model
{
    protected $table = 'DETALLE_PEDIDO';
    protected $primaryKey = 'Id_detalle';
    protected $fillable = ['Id_pedido', 'Id_variante', 'Cantidad', 'Precio_Unitario_Cobrado', 'Costo_Unitario_Fabricacion'];
    public $timestamps = false;
}
