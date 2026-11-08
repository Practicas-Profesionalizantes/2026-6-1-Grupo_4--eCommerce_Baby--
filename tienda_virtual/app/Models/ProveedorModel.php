<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProveedorModel extends Model
{
    protected $table = 'PROVEEDOR';
    protected $primaryKey = 'Id_proveedor';
    protected $fillable = ['Nombre_Empresa', 'Contacto', 'Telefono'];
    public $timestamps = false;
}
