<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UsuarioModel;

class ClienteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'Nombre' => fake()->firstName(),
            'Apellido' => fake()->lastName(),
            'DNI_CUIT' => fake()->numerify('########'),
            'Telefono' => fake()->numerify('11########'),
            'Condicion_IVA' => fake()->randomElement([
                'Consumidor Final',
                'Monotributista',
                'Responsable Inscripto'
            ]),
            'Id_usuario' => UsuarioModel::factory(),
        ];
    }
}