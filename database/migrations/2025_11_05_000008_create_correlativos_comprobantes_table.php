<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('correlativos_comprobantes', function (Blueprint $table) {
            $table->id('id_correlativo');
            $table->unsignedTinyInteger('id_tipo_comprobante');
            $table->string('serie', 10);
            $table->unsignedBigInteger('ultimo_numero')->default(0);
            $table->string('prefijo', 10)->nullable();
            $table->timestamps();

            $table->unique(['id_tipo_comprobante', 'serie'], 'unq_correlativo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('correlativos_comprobantes');
    }
};