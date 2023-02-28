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
        Schema::create('detalle_egresos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('egreso_id');
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('fraccionamiento_id');
            $table->string('descripcion', 100);
            $table->integer('cantidad');
            $table->decimal('precio_unitario');
            $table->timestamps();
            // $table->primary(['egreso_id', 'producto_id']);

            $table->foreign('egreso_id')->references('id')
                ->on('egresos')->onDelete('cascade');

            $table->foreign('producto_id')->references('id')
                ->on('productos')->onDelete('cascade');

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
        Schema::dropIfExists('detalle_egresos');
    }
};
