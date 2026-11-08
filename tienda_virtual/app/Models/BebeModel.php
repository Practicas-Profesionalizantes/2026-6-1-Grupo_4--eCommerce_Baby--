<?php

namespace App\Models; // La "A" y la "M" deben ser mayúsculas

use Illuminate\Database\Eloquent\Model;

class BebeModel extends Model
{
    protected $table = 'Bebe';
    protected $primaryKey = 'Id_bebe';
    protected $fillable = ['Id_cliente', 'Nombre', 'Fecha_Nacimiento', 'Genero'];
    public $timestamps = false;
}
