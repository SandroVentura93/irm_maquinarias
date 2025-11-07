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
        Schema::create('comprobantes_electronicos', function (Blueprint $table) {
            $table->id('id_comprobante');
            $table->unsignedBigInteger('id_venta')->nullable();
            $table->unsignedTinyInteger('id_tipo_comprobante');
            $table->string('serie', 10);
            $table->unsignedBigInteger('numero');
            $table->string('cliente_ruc', 15)->nullable();
            $table->string('cliente_razon_social', 200)->nullable();
            $table->dateTime('fecha_emision');
            $table->decimal('monto_subtotal', 12, 2)->default(0.00);
            $table->decimal('monto_igv', 12, 2)->default(0.00);
            $table->decimal('monto_total', 12, 2);
            $table->unsignedTinyInteger('moneda_id');
            $table->string('xml_nombre', 255)->nullable();
            $table->string('xml_hash', 255)->nullable();
            $table->string('pdf_nombre', 255)->nullable();
            $table->enum('estado', ['PENDIENTE', 'FIRMADO', 'ENVIADO', 'ACEPTADO', 'RECHAZO', 'ANULADO'])->default('PENDIENTE');
            $table->json('respuesta_sunat')->nullable();
            $table->text('qr')->nullable();
            $table->string('usuario_genero', 100)->nullable();
            $table->timestamps();

            $table->index('id_venta', 'comprobantes_electronicos_id_venta_foreign');
            $table->index('id_tipo_comprobante', 'comprobantes_electronicos_id_tipo_comprobante_foreign');
            $table->index('moneda_id', 'comprobantes_electronicos_moneda_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comprobantes_electronicos');
    }
};