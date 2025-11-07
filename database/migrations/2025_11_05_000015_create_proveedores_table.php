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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id('id_proveedor');
            $table->string('razon_social', 191);
            $table->enum('tipo_documento', ['DNI', 'RUC', 'PASAPORTE']);
            $table->string('numero_documento', 15);
            $table->string('contacto', 191)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->string('correo', 191)->nullable();
            $table->string('direccion', 191)->nullable();
            $table->unsignedBigInteger('id_ubigeo')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique('numero_documento', 'proveedores_numero_documento_unique');
            $table->index('id_ubigeo', 'proveedores_id_ubigeo_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
};