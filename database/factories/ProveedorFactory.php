<?php

namespace Database\Factories;

use App\Models\fraccionamiento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proveedor>
 */
class ProveedorFactory extends Factory
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
            'nombre' => $this->faker->name(),
            'rfc' => $this->faker->text(13),
            'nombre_contacto' => $this->faker->name(),
            'correo_contacto' => $this->faker->email(),
            'notas' => $this->faker->text(200),
            'metodo_de_pago_id' => $this->faker->randomElement([0, 1]),
            'fraccionamiento_id' => $this->faker->randomElement($fracc),
        ];
    }
}
