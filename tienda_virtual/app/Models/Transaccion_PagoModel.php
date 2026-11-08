<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaccion_PagoModel extends Model
{
    protected $table = 'TRANSACCION_PAGO';
    protected $primaryKey = 'Id_transaccion';
    protected $fillable = ['Id_pedido', 'Id_metodo_pago', 'Monto', 'Estado_Pago', 'Referencia_Pasarela', 'Fecha_Pago'];
    public $timestamps = false;
}
