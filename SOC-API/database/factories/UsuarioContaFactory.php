<?php

namespace Database\Factories;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\UsuarioConta;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UsuarioConta>
 */
class UsuarioContaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'CEDULA' => $this->faker->unique()->numberBetween(100000000, 999999999),
            'NOMBRE' => $this->faker->firstName(),
            'PRIMER_APELLIDO' => $this->faker->lastName(),
            'SEGUNDO_APELLIDO' => $this->faker->lastName(),
            'CORREO' => $this->faker->unique()->safeEmail(),
            'TELEFONO' => $this->faker->numberBetween(60000000, 80000000),
            'CONTRASENIA' => Hash::make('password'), // Puedes ajustar esta lógica según el método de encriptación que utilices
        ];
    }
}
