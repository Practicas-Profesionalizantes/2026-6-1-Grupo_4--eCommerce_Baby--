<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metodo_EnvioModel extends Model
{
    protected $table = 'METODO_ENVIO';
    protected $primaryKey = 'Id_metodo_envio';
    protected $fillable = ['Nombre', 'Costo_Base', 'Activo'];
    public $timestamps = false;
}
