<?php

namespace Database\Factories;
use App\Models\UsuarioModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\RolModel;

class UsuarioFactory extends Factory
{
    protected $model = UsuarioModel::class;
    public function definition(): array
    {
        return [
            'Email' => fake()->unique()->safeEmail(),

            'Password_Hash' => bcrypt('password'),

            'Fecha_Registro' => fake()->date(),

            'id_rol' => RolModel::factory(),
        ];
    }
}