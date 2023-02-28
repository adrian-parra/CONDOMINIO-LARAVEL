<?php

namespace Database\Seeders;

use App\Models\DetalleEgreso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetalleEgresoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DetalleEgreso::factory()
            ->count(100)
            ->create();
    }
}
