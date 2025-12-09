<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pagos_ventas', function (Blueprint $table) {
            $table->id('id_pago_venta');
            $table->unsignedBigInteger('id_venta');
            $table->decimal('monto', 12, 2);
            $table->string('metodo', 50);
            $table->string('numero_operacion', 200)->nullable(); // Add the 'numero_operacion' column
            $table->timestamp('fecha')->useCurrent();
            $table->foreign('id_venta')->references('id_venta')->on('ventas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos_ventas');
    }
};
