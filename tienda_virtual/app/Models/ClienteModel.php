<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteModel extends Model
{
    protected $table = 'Cliente';
    protected $primaryKey = 'Id_cliente';
    protected $fillable = ['Id_usuario', 'Nombre', 'Apellido', 'DNI_CUIT', 'Telefono', 'Condicion_IVA'];
    public $timestamps = false;
}
