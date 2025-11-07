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
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id('id_bitacora');
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->string('accion', 100)->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha')->useCurrent();

            $table->index('id_usuario', 'bitacora_id_usuario_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bitacoras');
    }
};