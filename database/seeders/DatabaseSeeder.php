<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\DetalleEgreso;
use App\Models\TipoVehiculo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(
            FraccionamientoSeeder::class,
        );

        $this->call(
            PropietarioSeeder::class
        );

        // $this->call(
        //     PropiedadSeeder::class
        // );

        $this->call(
            ProveedorSeeder::class
        );

        $this->call(
            ProductoSeeder::class
        );

        $this->call(
            TipoDeEgresoSeeder::class
        );

        $this->call(
            EgresoSeeder::class
        );

        $this->call(
            DetalleEgresoSeeder::class
        );

        $this->call(
            EstadosSeeder::class
        );

        $this->call(
            TipoVehiculoSeeder::class
        );

        $this->call(
            RolesDeUsuarioSeeder::class        
        );      
    }
}
