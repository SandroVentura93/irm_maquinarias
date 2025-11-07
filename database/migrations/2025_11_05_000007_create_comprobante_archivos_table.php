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
        Schema::create('comprobante_archivos', function (Blueprint $table) {
            $table->id('id_archivo');
            $table->unsignedBigInteger('id_comprobante');
            $table->enum('tipo', ['XML', 'PDF']);
            $table->string('nombre_archivo', 500);
            $table->string('ruta', 1000)->nullable();
            $table->unsignedBigInteger('tamanio_bytes')->nullable();
            $table->timestamps();

            $table->index('id_comprobante', 'comprobante_archivos_id_comprobante_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comprobante_archivos');
    }
};