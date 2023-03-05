<?php

namespace Database\Factories;

use App\Models\fraccionamiento;
use App\Models\Propietario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recibo>
 */
class ReciboFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fracc = fraccionamiento::inRandomOrder()->first();
        $propietario = Propietario::inRandomOrder()->first();

        return [
            'fecha_pago' => $this->faker->date('Y-m-d'),
            'monto' => $this->faker->randomFloat(2, 500, 1000),
            'fecha_vencimiento' =>  $this->faker->date('Y-m-d', '2023-12-01'),
            'estatus' => $this->faker->randomElement(["PAGADO", "VENCIDO", "POR_PAGAR"]),
            'propietario_id' => $propietario,
            'fraccionamiento_id' => $fracc,
        ];
    }
}
