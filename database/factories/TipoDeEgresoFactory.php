<?php

namespace Database\Factories;

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
        return [
            'descripcion' => $this->faker->realText(100),
            'status' => $this->faker->boolean(90),
        ];
    }
}
