<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\fraccionamiento;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Propietario>
 */
class PropietarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fracc = fraccionamiento::pluck('id')->toArray();

        return [
            'nombre' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'correo' => $this->faker->email(),
            'celular' => $this->faker->phoneNumber(),
            'celular_alt' => $this->faker->phoneNumber(),
            'telefono_fijo' => $this->faker->phoneNumber(),
            'identificacion_url' => $this->faker->filePath(),
            'is_inquilino' => $this->faker->boolean(),
            'fraccionamiento_id' => $this->faker->randomElement($fracc),
            'clave_interfon' => $this->faker->randomAscii(),
            'clave_interfon_alt' => $this->faker->randomAscii()
        ];
    }
}
