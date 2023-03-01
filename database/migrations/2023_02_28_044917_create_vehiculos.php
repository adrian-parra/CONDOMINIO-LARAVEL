<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_propiedad');
            $table->foreign('id_propiedad')->references('id')->on('propiedads');
            $table->unsignedBigInteger('id_fraccionamiento');
            $table->foreign('id_fraccionamiento')->references('id')->on('fraccionamientos');
            $table->unsignedBigInteger('id_estado')->comment('ESTADO DE MEXICO EMISOR DE LAS PLACAS');
            $table->foreign('id_estado')->references('id')->on('estados');
            $table->unsignedBigInteger('id_tipo_vehiculo');
            $table->foreign('id_tipo_vehiculo')->references('id')->on('tipo_vehiculo');
            $table->string('marca');
            $table->string('patch_tarjeta_circulacion')->nullable()->comment("RUTA DEL ARCHIVO DE TARGETA DE CIRCULACION");
            $table->string('color');
            $table->string('placas');
            $table->boolean('estatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehiculos');
    }
};
