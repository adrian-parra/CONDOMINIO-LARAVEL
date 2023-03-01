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
        Schema::create('fraccionamientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->string('codigo_postal');
            $table->boolean('egresos_authorized')->default(false);
            $table->unsignedBigInteger('user_id')->default(null)->unique()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')
                ->on('usuarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fraccionamientos');
    }
};
