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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->enum('tipo_documento', ['DNI', 'RUC', 'PASAPORTE']);
            $table->string('numero_documento', 15);
            $table->string('nombre', 255)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->char('id_ubigeo', 6)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 100)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('id_ubigeo', 'clientes_id_ubigeo_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};