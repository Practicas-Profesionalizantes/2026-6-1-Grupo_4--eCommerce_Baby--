<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnvioModel extends Model
{
    protected $table = 'ENVIO';
    protected $primaryKey = 'Id_envio';
    protected $fillable = ['Id_pedido', 'Empresa_Logistica', 'Numero_Tracking', 'Estado_Envio', 'Fecha_Despacho'];
    public $timestamps = false;
}
