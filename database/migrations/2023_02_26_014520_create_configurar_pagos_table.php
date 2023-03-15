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
        Schema::create('configurar_pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_fraccionamiento');
            $table->foreign('id_fraccionamiento')->references('id')->on('fraccionamientos');
            $table->string('descripcion', 100);
            $table->enum('tipo_pago', ['ORDINARIO', 'EXTRAORDINARIO']);
            $table->double('monto');
            $table->date('fecha_inicial');
            $table->enum('periodo', ['UNICO', 'SEMANAL', 'MENSUAL', 'ANUAL']);
            $table->integer('dias_max_pago');
            $table->integer('dias_max_descuento');
            $table->double('porcentaje_penalizacion');
            $table->double('porcentaje_descuento');
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
        Schema::dropIfExists('configurar_pagos');
    }
};
