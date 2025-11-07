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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id('id_venta');
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedTinyInteger('id_moneda');
            $table->unsignedTinyInteger('id_tipo_comprobante');
            $table->string('serie', 10);
            $table->string('numero', 20);
            $table->dateTime('fecha');
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('igv', 12, 2)->default(0.00);
            $table->decimal('total', 12, 2)->default(0.00);
            $table->string('xml_hash', 255)->nullable();
            $table->string('xml_nombre', 255)->nullable();
            $table->enum('xml_estado', ['PENDIENTE', 'ENVIADO', 'ACEPTADO', 'RECHAZADO'])->default('PENDIENTE');
            $table->text('qr_hash')->nullable();
            $table->timestamps();

            $table->index('id_cliente', 'ventas_id_cliente_foreign');
            $table->index('id_tipo_comprobante', 'ventas_id_tipo_comprobante_foreign');
            $table->index('id_moneda', 'ventas_id_moneda_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas');
    }
};