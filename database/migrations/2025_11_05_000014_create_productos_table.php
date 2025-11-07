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
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->unsignedBigInteger('id_categoria')->nullable();
            $table->unsignedBigInteger('id_marca')->nullable();
            $table->unsignedBigInteger('id_proveedor')->nullable();
            $table->string('codigo', 50);
            $table->string('numero_parte', 50)->nullable();
            $table->string('descripcion', 255);
            $table->string('modelo', 100)->nullable();
            $table->decimal('peso', 10, 2)->nullable();
            $table->string('ubicacion', 100)->nullable();
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->decimal('precio_compra', 12, 2)->default(0.00);
            $table->decimal('precio_venta', 12, 2)->default(0.00);
            $table->boolean('importado')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index('id_categoria', 'productos_id_categoria_foreign');
            $table->index('id_marca', 'productos_id_marca_foreign');
            $table->index('id_proveedor', 'productos_id_proveedor_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
};