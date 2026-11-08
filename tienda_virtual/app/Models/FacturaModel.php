<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaModel extends Model
{
    protected $table = 'FACTURA';
    protected $primaryKey = 'Id_factura';
    protected $fillable = ['Id_pedido', 'Tipo_Comprobante', 'Nro_Comprobante', 'CUIT_Receptor', 'Fecha_Emision'];
    public $timestamps = false;
}
