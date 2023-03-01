<?php

namespace Database\Seeders;

use App\Models\TipoDeEgreso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoDeEgresoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoDeEgreso::factory()
            ->count(10)
            ->create();
    }
}
