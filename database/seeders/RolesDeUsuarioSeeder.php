<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesDeUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $roles = [
            ['descripcion'=>'ADMIN GENERAL'] ,
            ['descripcion'=>'ADMIN FRACCIONAMIENTO']
        ];

        DB::table('rol')->insert($roles);
    }
}
