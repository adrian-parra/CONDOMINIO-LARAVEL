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
        Schema::create('codigos_postales', function (Blueprint $table) {
            $table->id();
            $table->string("d_codigo")->nullable(false);
            $table->string("d_asenta")->nullable(false);
            $table->string("d_tipo_asenta")->nullable(false);
            $table->string("D_mnpio");
            $table->string("d_estado");
            $table->string("d_ciudad");
            $table->string("d_CP");
            $table->string("c_estado");
            $table->string("c_oficina");
            $table->string("c_CP")->nullable(true);
            $table->string("c_tipo_asenta");
            $table->string("c_mnpio");
            $table->string("id_asenta_cpcons");
            $table->string("d_zona");
            $table->string("c_cve_ciudad");
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
        Schema::dropIfExists('codigos_postales');
    }
};
