<?php

namespace Database\Factories;

use App\Models\fraccionamiento;
use App\Models\Proveedor;
use App\Models\TipoDeEgreso;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Egreso>
 */
class EgresoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fracc = fraccionamiento::pluck('id')->toArray();
        $tipo_de_egreso = TipoDeEgreso::pluck('id')->toArray();
        $proveedor = Proveedor::inRandomOrder()->first();

        return [
            'descripcion' => $this->faker->realText(100),
            'is_verified' => $this->faker->boolean(30),
            'monto_total' => $this->faker->randomFloat(2, 1000, 100000),
            'comprobante_url' => $this->faker->filePath(),
            'estatus_egreso_id' => $this->faker->randomElement([0, 1, 2]),
            'fecha_pago' => $this->faker->date(),
            'tipo_pago' => $this->faker->randomElement(['T/C', 'T/D', 'CHEQUE', 'EFECTIVO', 'TRANSFERENCIA']),
            'tipo_egreso_id' => $this->faker->randomElement($tipo_de_egreso),
            'fraccionamiento_id' => $this->faker->randomElement($fracc),
            'proveedor_id' => $proveedor
        ];
    }
}
