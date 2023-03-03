<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\fraccionamiento>
 */
class FraccionamientoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->domainName(),
            'codigo_postal' => $this->faker->postCode(),
            'egresos_authorized' => true,
            'created_at' => $this->faker->date(),
            'updated_at' => null,
        ];
    }
}
