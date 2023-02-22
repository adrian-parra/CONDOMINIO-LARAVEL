<?php

namespace Database\Seeders;

use App\Models\fraccionamiento;
use Illuminate\Database\Seeder;

class FraccionamientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        fraccionamiento::factory()
            ->count(10)
            ->create();
    }
}
