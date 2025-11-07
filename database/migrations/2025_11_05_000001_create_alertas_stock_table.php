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
        Schema::create('alertas_stock', function (Blueprint $table) {
            $table->id('id_alerta');
            $table->unsignedBigInteger('id_producto');
            $table->integer('cantidad_actual');
            $table->integer('stock_minimo');
            $table->dateTime('fecha_alerta')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('estado', ['PENDIENTE', 'ATENDIDA'])->default('PENDIENTE');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('id_producto', 'alertas_stock_id_producto_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alertas_stock');
    }
};