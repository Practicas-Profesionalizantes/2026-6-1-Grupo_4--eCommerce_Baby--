<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaModel extends Model
{
    protected $table = 'Categoria';
    protected $primaryKey = 'Id_categoria';
    protected $fillable = ['Nombre', 'Descripcion'];
    public $timestamps = false;
}
