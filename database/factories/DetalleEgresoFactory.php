<?php

namespace Database\Factories;

use App\Models\DetalleEgreso;
use App\Models\Egreso;
use App\Models\fraccionamiento;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetalleEgreso>
 */
class DetalleEgresoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fracc = fraccionamiento::pluck('id')->toArray();
        $egreso = Egreso::inRandomOrder()->first();
        $producto = Producto::inRandomOrder()->first();

        while (DetalleEgreso::where('producto_id', '=', $producto->id)->where('egreso_id', '=', $egreso->id)->first() != null) {
            $producto = Producto::inRandomOrder()->first();
        }

        return [
            'egreso_id' => $egreso,
            'producto_id' => $producto,
            'fraccionamiento_id' => $this->faker->randomElement($fracc),
            'descripcion' => $this->faker->realText(100),
            'cantidad' => intval($this->faker->randomFloat(0, 1, 100)),
            'precio_unitario' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
