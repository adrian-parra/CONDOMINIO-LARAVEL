<?php

namespace Database\Factories;

use App\Models\fraccionamiento;
use App\Models\Propietario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Propiedad>
 */
class PropiedadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fracc = fraccionamiento::pluck('id')->toArray();
        $propietario = Propietario::where('is_inquilino', false)->pluck('id')->toArray();

        $inquilino = Propietario::where('is_inquilino', true)->pluck('id')->toArray();

        return [
            'tipo_propiedad_id' => $this->faker->randomElement([0, 1, 2, 3]),
            'clave_catastral' =>  $this->faker->postCode(),
            'predial_url' => $this->faker->filePath(),
            'descripcion' => $this->faker->realText(150),
            'superficie' => $this->faker->randomFloat(4, 1000, 10000),
            'estatus_id' => $this->faker->randomElement([0, 1, 2, 3]),
            'propietario_id' => $this->faker->randomElement($propietario),
            'inquilino_id' => optional($this->faker->randomElement([null, 1]))->randomElement($inquilino),
            'fraccionamiento_id' => $this->faker->randomElement($fracc),
        ];
    }
}
