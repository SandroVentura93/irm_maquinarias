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
        Schema::create('compras', function (Blueprint $table) {
            $table->id('id_compra');
            $table->unsignedBigInteger('id_proveedor');
            $table->unsignedTinyInteger('id_moneda');
            $table->dateTime('fecha');
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('igv', 12, 2)->default(0.00);
            $table->decimal('total', 12, 2)->default(0.00);
            $table->timestamps();

            $table->index('id_proveedor', 'compras_id_proveedor_foreign');
            $table->index('id_moneda', 'compras_id_moneda_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras');
    }
};