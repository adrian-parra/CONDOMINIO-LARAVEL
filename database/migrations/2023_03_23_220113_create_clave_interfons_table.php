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
        Schema::create('clave_interfons', function (Blueprint $table) {
            $table->string('numero_interfon', 20);
            $table->string('codigo_interfon', 5);
            $table->boolean('estatus')->default(1);
            $table->unsignedBigInteger('propiedad_id');
            $table->unsignedBigInteger('fraccionamiento_id');

            $table->timestamps();

            $table->primary(['fraccionamiento_id', 'propiedad_id', 'numero_interfon']);

            $table->unique(['fraccionamiento_id', 'numero_interfon']);

            $table->foreign('fraccionamiento_id')->references('id')
                ->on('fraccionamientos')->onDelete('cascade');

            $table->foreign('propiedad_id')->references('id')
                ->on('propiedads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clave_interfons');
    }
};
