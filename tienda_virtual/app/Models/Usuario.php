<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    // Define el nombre de tu tabla en la base de datos
    public $table = 'Usuario'; 

    // Permite que Laravel escriba en estos campos masivamente
    public $fillable = ['Email', 'Password_Hash', 'Fecha_Registro', 'id_rol'];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    } 
}