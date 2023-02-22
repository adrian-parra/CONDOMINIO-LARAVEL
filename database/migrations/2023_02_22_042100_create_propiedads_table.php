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
        Schema::create('propiedads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_propiedad_id')->nullable();
            $table->string('clave_catastral', 40);
            $table->string('predial_url');
            $table->string('descripcion');
            $table->float('superficie');
            $table->float('balance');
            $table->boolean('estatus_id');
            $table->string('razon_de_rechazo')->nullable();
            $table->unsignedBigInteger('propietario_id')->nullable();
            $table->unsignedBigInteger('inquilino_id')->nullable();
            $table->unsignedBigInteger('fraccionamiento_id');

            $table->timestamps();

            $table->foreign('propietario_id')->references('id')
                ->on('propietarios')->onDelete('set null');

            $table->foreign('inquilino_id')->references('id')
                ->on('propietarios')->onDelete('set null');

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
        Schema::dropIfExists('propiedades');
    }
};
