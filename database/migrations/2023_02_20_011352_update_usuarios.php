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
        //
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('primer_apellido');
            $table->dropColumn('segundo_apellido');
            $table->string('apellido');
           $table->unsignedBigInteger('id_fraccionamiento');
           $table->foreign('id_fraccionamiento')->references('id')->on('fraccionamientos');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
       
    }
};
