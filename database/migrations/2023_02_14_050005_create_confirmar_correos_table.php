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
        Schema::create('confirmar_correos', function (Blueprint $table) {
            $table->id();
            $table->string('correo')->unique();
            $table->string('estado')->comment('ENVIADO,CONFIRMADO EXPIRADO');
            $table->string('motivo')->comment('REGISTRO,CAMBIO');
            $table->string('nuevo_correo');
            $table->string('token');
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
        Schema::dropIfExists('confirmar_correos');
    }
};
