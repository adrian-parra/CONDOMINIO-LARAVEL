<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $estados = [
            ['nombre' => 'Aguascalientes'],
            ['nombre' => 'Baja California'],
            ['nombre' => 'Baja California Sur'],
            ['nombre' => 'Campeche'],
            ['nombre' => 'Chiapas'],
            ['nombre' => 'Chihuahua'],
            ['nombre' => 'Ciudad de México'],
            ['nombre' => 'Coahuila'],
            ['nombre' => 'Colima'],
            ['nombre' => 'Durango'],
            ['nombre' => 'Guanajuato'],
            ['nombre' => 'Guerrero'],
            ['nombre' => 'Hidalgo'],
            ['nombre' => 'Jalisco'],
            ['nombre' => 'México'],
            ['nombre' => 'Michoacán'],
            ['nombre' => 'Morelos'],
            ['nombre' => 'Nayarit'],
            ['nombre' => 'Nuevo León'],
            ['nombre' => 'Oaxaca'],
            ['nombre' => 'Puebla'],
            ['nombre' => 'Querétaro'],
            ['nombre' => 'Quintana Roo'],
            ['nombre' => 'San Luis Potosí'],
            ['nombre' => 'Sinaloa'],
            ['nombre' => 'Sonora'],
            ['nombre' => 'Tabasco'],
            ['nombre' => 'Tamaulipas'],
            ['nombre' => 'Tlaxcala'],
            ['nombre' => 'Veracruz'],
            ['nombre' => 'Yucatán'],
            ['nombre' => 'Zacatecas'],
        ];

        DB::table('estados')->insert($estados);
    }
}
