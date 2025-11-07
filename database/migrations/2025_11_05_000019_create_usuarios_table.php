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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->unsignedTinyInteger('id_rol')->default(1);
            $table->string('nombre', 100);
            $table->string('usuario', 50);
            $table->string('contrasena', 255);
            $table->string('correo', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique('usuario', 'usuarios_usuario_unique');
            $table->index('id_rol', 'usuarios_id_rol_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};