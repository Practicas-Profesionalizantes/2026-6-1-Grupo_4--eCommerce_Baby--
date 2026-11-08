<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Le decimos que use TU tabla en vez de la de Laravel
    protected $table = 'usuario';

    // Le decimos cuál es tu llave primaria
    protected $primaryKey = 'Id_usuario';

    // NUEVO: tu tabla no tiene created_at ni updated_at
    public $timestamps = false;

    // Como tus columnas no tienen los nombres estándar, las mapeamos acá
    protected $fillable = [
        'Email',
        'Password_Hash',
        'Id_rol',
    ];

    protected $hidden = [
        'Password_Hash',
    ];

    // Le decimos a Laravel que la contraseña se llama 'Password_Hash'
    public function getAuthPassword()
    {
        return $this->Password_Hash;
    }

    // Conecta el usuario con su perfil de la tabla 'cliente'
    public function cliente()
    {
        return $this->hasOne(ClienteModel::class, 'Id_usuario', 'Id_usuario');
    }

    // Permite usar Auth::user()->name y trae el nombre desde 'cliente'
    public function getNameAttribute()
    {
        if (!$this->cliente) {
            return null;
        }

        return trim($this->cliente->Nombre . ' ' . $this->cliente->Apellido);
    }

    // Permite usar Auth::user()->email aunque tu columna se llame 'Email'
    public function getEmailAttribute()
    {
        return $this->attributes['Email'] ?? null;
    }
}