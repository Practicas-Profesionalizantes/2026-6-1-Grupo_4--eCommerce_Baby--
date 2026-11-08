<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioModel extends Model
{
    protected $table = 'Usuario';
    protected $primaryKey = 'Id_usuario';
    protected $fillable = ['Id_rol', 'Email', 'Password_Hash', 'Fecha_Registro'];
    protected $hidden = ['Password_Hash'];
    
    public $timestamps = false; 
}
