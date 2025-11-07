<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Disparador trg_after_delete_detalle_compra
        DB::unprepared("CREATE TRIGGER trg_after_delete_detalle_compra
        AFTER DELETE ON detalle_compras
        FOR EACH ROW
        BEGIN
            -- Reducir el stock si se elimina una compra
            UPDATE productos
            SET stock_actual = stock_actual - OLD.cantidad
            WHERE id_producto = OLD.id_producto;
        END");

        // Disparador trg_after_insert_detalle_compra
        DB::unprepared("CREATE TRIGGER trg_after_insert_detalle_compra
        AFTER INSERT ON detalle_compras
        FOR EACH ROW
        BEGIN
            -- Aumentar el stock según la cantidad comprada
            UPDATE productos
            SET stock_actual = stock_actual + NEW.cantidad
            WHERE id_producto = NEW.id_producto;
        END");

        // Disparador trg_after_update_detalle_compra
        DB::unprepared("CREATE TRIGGER trg_after_update_detalle_compra
        AFTER UPDATE ON detalle_compras
        FOR EACH ROW
        BEGIN
            -- Ajustar el stock según la diferencia de cantidades
            UPDATE productos
            SET stock_actual = stock_actual + (NEW.cantidad - OLD.cantidad)
            WHERE id_producto = NEW.id_producto;
        END");

        // Disparador trg_after_delete_detalle_venta
        DB::unprepared("CREATE TRIGGER trg_after_delete_detalle_venta
        AFTER DELETE ON detalle_ventas
        FOR EACH ROW
        BEGIN
            -- Reponer stock al eliminar una venta
            UPDATE productos
            SET stock_actual = stock_actual + OLD.cantidad
            WHERE id_producto = OLD.id_producto;
        END");

        // Disparador trg_after_insert_detalle_venta
        DB::unprepared("CREATE TRIGGER trg_after_insert_detalle_venta
        AFTER INSERT ON detalle_ventas
        FOR EACH ROW
        BEGIN
            -- Disminuir stock según cantidad vendida
            UPDATE productos
            SET stock_actual = stock_actual - NEW.cantidad
            WHERE id_producto = NEW.id_producto;
        END");

        // Disparador trg_after_update_detalle_venta
        DB::unprepared("CREATE TRIGGER trg_after_update_detalle_venta
        AFTER UPDATE ON detalle_ventas
        FOR EACH ROW
        BEGIN
            -- Ajustar stock si cambió la cantidad vendida
            UPDATE productos
            SET stock_actual = stock_actual - (NEW.cantidad - OLD.cantidad)
            WHERE id_producto = NEW.id_producto;
        END");

        // Disparador trg_after_update_productos_stock
        DB::unprepared("CREATE TRIGGER trg_after_update_productos_stock
        AFTER UPDATE ON productos
        FOR EACH ROW
        BEGIN
            -- Solo ejecutar si hubo cambio en el stock_actual
            IF NEW.stock_actual <> OLD.stock_actual THEN
                CALL sp_verificar_stock_bajo();
            END IF;
        END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_delete_detalle_compra");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_insert_detalle_compra");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_update_detalle_compra");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_delete_detalle_venta");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_insert_detalle_venta");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_update_detalle_venta");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_update_productos_stock");
    }
};