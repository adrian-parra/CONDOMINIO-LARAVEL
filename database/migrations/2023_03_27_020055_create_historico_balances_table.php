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
        Schema::create('historico_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedFloat('monto');
            $table->enum('tipo', ['INCREMENTO', 'DECREMENTO']);

            $table->unsignedBigInteger('propiedad_id')->nullable();
            $table->unsignedBigInteger('pago_id')->nullable()->default(null);
            $table->unsignedBigInteger('recibo_id')->nullable()->default(null);

            $table->timestamps();

            $table->foreign('propiedad_id')
                ->references('id')
                ->on('propiedads')
                ->onDelete('cascade');

            $table->foreign('pago_id')
                ->references('id')
                ->on('recibos_comprobantes')
                ->onDelete('cascade');;

            // $table->foreign('recibo_id')
            //     ->references('id')
            //     ->on('recibos')
            //     ->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historico_balances');
    }
};
