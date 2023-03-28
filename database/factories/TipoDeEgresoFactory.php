<?php

namespace Database\Factories;

use App\Models\fraccionamiento;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TipoDeEgreso>
 */
class TipoDeEgresoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fraccionamiento = fraccionamiento::inRandomOrder()->first();
        $proveedor = Proveedor::inRandomOrder()->first();

        return [
            'descripcion' => $this->faker->realText(100),
            'status' => $this->faker->boolean(90),
            'fraccionamiento_id' => $fraccionamiento
        ];
    }
}
