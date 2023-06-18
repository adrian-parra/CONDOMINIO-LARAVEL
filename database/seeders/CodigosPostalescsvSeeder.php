<?php

namespace Database\Seeders;

use App\Models\CodigoPostal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CodigosPostalescsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $csvFile = database_path('filesCsv/CodigosPostalesSinaloa.csv');
        $delimiter = ','; // Delimitador del archivo CSV

        if (($handle = fopen($csvFile, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
                $model = new CodigoPostal();
                $model->d_codigo = $data[0];
                $model->d_asenta = $data[1];
                $model->d_tipo_asenta = $data[2];
                $model->D_mnpio = $data[3];
                $model->d_estado = $data[4];
                $model->d_ciudad = $data[5];
                $model->d_CP = $data[6];
                $model->c_estado = $data[7];
                $model->c_oficina = $data[8];
                $model->c_CP = $data[9];
                $model->c_tipo_asenta = $data[10];
                $model->c_mnpio = $data[11];
                $model->id_asenta_cpcons = $data[12];
                $model->d_zona = $data[13];
                $model->c_cve_ciudad = $data[14];
                $model->save();
            }
            fclose($handle);
        }
    }
}
