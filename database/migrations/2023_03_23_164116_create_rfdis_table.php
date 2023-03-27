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
        Schema::create('rfdis', function (Blueprint $table) {
            $table->string('rfdi', 20)->primary();
            $table->enum('tipo', ['PEATONAL', 'AUTOMOVIL']);
            $table->enum('estatus', ['ACTIVO', 'INACTIVO', 'CANCELADA'])->default('ACTIVO');
            $table->unsignedBigInteger('fraccionamiento_id');
            $table->unsignedBigInteger('propiedad_id');
            $table->timestamps();

            $table->unique(['rfdi', 'fraccionamiento_id']);

            $table->foreign('fraccionamiento_id')
                ->references('id')
                ->on('fraccionamientos');

            $table->foreign('propiedad_id')
                ->references('id')
                ->on('propiedads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rfdis');
    }
};
