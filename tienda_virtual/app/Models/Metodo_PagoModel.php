<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metodo_PagoModel extends Model
{
    protected $table = 'METODO_PAGO';
    protected $primaryKey = 'Id_metodo_pago';
    protected $fillable = ['Nombre', 'Activo'];
    public $timestamps = false;
}
