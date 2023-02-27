<?php

namespace Database\Factories;

use App\Models\fraccionamiento;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fracc = fraccionamiento::pluck('id')->toArray();
        $proveedor = Proveedor::pluck('id')->toArray();

        return [
            'descripcion' => $this->faker->text(100),
            'identificador_interno' => $this->faker->text(20),
            'proveedor_id' => $this->faker->randomElement($proveedor),
            'fraccionamiento_id' => $this->faker->randomElement($fracc),
        ];
    }
}
