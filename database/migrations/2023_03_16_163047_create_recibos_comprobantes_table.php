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
        Schema::create('recibos_comprobantes', function (Blueprint $table) {
            $table->id();
            $table->decimal('monto');
            $table->string('comprobante_url');
            $table->enum('estatus', ['PENDIENTE', 'FINALIZADO', 'RECHAZADO'])->default('PENDIENTE');
            $table->string('razon_rechazo', 200)->nullable();
            $table->enum('tipo_pago', ['T/C', 'T/D', 'CHEQUE', 'EFECTIVO', 'TRANSFERENCIA']);
            $table->unsignedBigInteger('fraccionamiento_id');
            $table->unsignedBigInteger('propiedad_id');
            $table->unsignedBigInteger('recibo_id');

            $table->foreign('fraccionamiento_id')
                ->references('id')
                ->on('fraccionamientos');

            $table->foreign('propiedad_id')
                ->references('id')
                ->on('propiedads');

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
        Schema::dropIfExists('recibos_comprobantes');
    }
};
