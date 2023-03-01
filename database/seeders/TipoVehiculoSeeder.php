<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tipo_vehiculos = [
            ['nombre' => 'Automovil'],
            ['nombre' => 'Automovil blindado'],
            ['nombre' => 'Camioneta'],
            ['nombre' => 'Camioneta blindada']
            
        ];

        DB::table('tipo_vehiculo')->insert($tipo_vehiculos);
    }
}
