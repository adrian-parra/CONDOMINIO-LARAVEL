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
        Schema::create('tipo_de_egresos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 100);
            $table->boolean('status');
            $table->unsignedBigInteger('fraccionamiento_id');
            $table->unsignedBigInteger('proveedor_id')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('fraccionamiento_id')->references('id')
                ->on('fraccionamientos')->onDelete('cascade');

            $table->foreign('proveedor_id')->references('id')
                ->on('proveedors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_de_egresos');
    }
};
