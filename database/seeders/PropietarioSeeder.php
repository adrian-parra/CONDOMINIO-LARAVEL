<?php

namespace Database\Seeders;

use App\Models\Propietario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropietarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Propietario::factory()
            ->count(200)
            ->hasPropiedad(1)
            ->create();

        Propietario::factory()
            ->count(15)
            ->hasPropiedad(2)
            ->create();

        Propietario::factory()
            ->count(5)
            ->hasPropiedad(3)
            ->create();
    }
}
