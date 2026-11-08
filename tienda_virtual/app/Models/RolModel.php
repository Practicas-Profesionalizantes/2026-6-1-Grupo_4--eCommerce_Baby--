<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolModel extends Model
{
    protected $table = 'Rol';
    protected $primaryKey = 'id_rol';
    protected $fillable = ['Nombre_Rol'];
    public $timestamps = false;
}
