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
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fraccionamiento_id');
            $table->unsignedBigInteger('propiedad_id');
            $table->unsignedBigInteger('configuracion_id');
            $table->date('fecha_pago')->nullable()->default(null);
            $table->date('fecha_vencimiento');
            $table->decimal('monto');
            $table->unsignedDecimal('monto_penalizacion')->nullable()->default(null);
            $table->unsignedDecimal('monto_descuento')->nullable()->default(null);
            $table->enum("estatus", ["PAGADO", "VENCIDO", "POR_PAGAR"]);
            $table->timestamps();

            $table->foreign('fraccionamiento_id')
                ->references('id')
                ->on('fraccionamientos');

            $table->foreign('propiedad_id')
                ->references('id')
                ->on('propiedads');

            $table->foreign('configuracion_id')
                ->references('id')
                ->on('configurar_pagos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recibos');
    }
};
