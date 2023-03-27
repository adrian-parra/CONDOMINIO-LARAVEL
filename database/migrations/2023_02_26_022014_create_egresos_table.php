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
        Schema::create('egresos', function (Blueprint $table) {
            $table->id();

            $table->string('descripcion', 100);
            $table->boolean('is_verified');
            $table->unsignedInteger('estatus_egreso_id')->default(0);
            $table->unsignedFloat('monto_total');
            $table->string('comprobante_url');
            $table->enum('tipo_pago', ['T/C', 'T/D', 'CHEQUE', 'EFECTIVO', 'TRANSFERENCIA']);
            $table->date('fecha_pago');
            $table->unsignedBigInteger('tipo_egreso_id')->nullable();
            $table->unsignedBigInteger('fraccionamiento_id');

            $table->timestamps();

            $table->foreign('tipo_egreso_id')->references('id')
                ->on('tipo_de_egresos')->onDelete('set null');

            $table->foreign('fraccionamiento_id')->references('id')
                ->on('fraccionamientos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('egresos');
    }
};
