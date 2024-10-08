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
        Schema::create('propietarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellidos', 100);
            $table->string('correo', 40)->unique();
            $table->string('celular', 20);
            $table->string('celular_alt', 20)->nullable();
            $table->string('telefono_fijo', 20)->unique();
            $table->string('identificacion_url')->nullable();
            $table->boolean('is_inquilino');

            $table->unique(['celular', 'fraccionamiento_id']);

            $table->unsignedBigInteger('fraccionamiento_id');
            $table->foreign('fraccionamiento_id')->references('id')
                ->on('fraccionamientos')->onDelete('cascade');

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
        Schema::dropIfExists('propietarios');
    }
};
