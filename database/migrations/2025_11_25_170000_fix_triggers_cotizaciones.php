<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Corregir triggers para que las cotizaciones NO afecten el stock
     * 
     * @return void
     */
    public function up()
    {
        // ELIMINAR triggers antiguos
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_insert_detalle_venta");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_update_detalle_venta");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_delete_detalle_venta");

        // RECREAR trigger INSERT con validación de tipo de comprobante
        DB::unprepared("
            CREATE TRIGGER trg_after_insert_detalle_venta
            AFTER INSERT ON detalle_ventas
            FOR EACH ROW
            BEGIN
                DECLARE tipo_codigo VARCHAR(10);
                
                -- Obtener el código SUNAT del tipo de comprobante
                SELECT tc.codigo_sunat INTO tipo_codigo
                FROM ventas v
                JOIN tipo_comprobantes tc ON v.id_tipo_comprobante = tc.id_tipo_comprobante
                WHERE v.id_venta = NEW.id_venta;
                
                -- Solo descontar stock si es Factura (01), Boleta (03) o Ticket (12)
                -- NO descontar para Cotizaciones (CT) u otros
                IF tipo_codigo IN ('01', '03', '12') THEN
                    UPDATE productos
                    SET stock_actual = stock_actual - NEW.cantidad
                    WHERE id_producto = NEW.id_producto;
                END IF;
            END
        ");

        // RECREAR trigger UPDATE con validación de tipo de comprobante
        DB::unprepared("
            CREATE TRIGGER trg_after_update_detalle_venta
            AFTER UPDATE ON detalle_ventas
            FOR EACH ROW
            BEGIN
                DECLARE tipo_codigo VARCHAR(10);
                
                -- Obtener el código SUNAT del tipo de comprobante
                SELECT tc.codigo_sunat INTO tipo_codigo
                FROM ventas v
                JOIN tipo_comprobantes tc ON v.id_tipo_comprobante = tc.id_tipo_comprobante
                WHERE v.id_venta = NEW.id_venta;
                
                -- Solo ajustar stock si es Factura (01), Boleta (03) o Ticket (12)
                IF tipo_codigo IN ('01', '03', '12') THEN
                    UPDATE productos
                    SET stock_actual = stock_actual - (NEW.cantidad - OLD.cantidad)
                    WHERE id_producto = NEW.id_producto;
                END IF;
            END
        ");

        // RECREAR trigger DELETE con validación de tipo de comprobante
        DB::unprepared("
            CREATE TRIGGER trg_after_delete_detalle_venta
            AFTER DELETE ON detalle_ventas
            FOR EACH ROW
            BEGIN
                DECLARE tipo_codigo VARCHAR(10);
                
                -- Obtener el código SUNAT del tipo de comprobante
                SELECT tc.codigo_sunat INTO tipo_codigo
                FROM ventas v
                JOIN tipo_comprobantes tc ON v.id_tipo_comprobante = tc.id_tipo_comprobante
                WHERE v.id_venta = OLD.id_venta;
                
                -- Solo reponer stock si es Factura (01), Boleta (03) o Ticket (12)
                IF tipo_codigo IN ('01', '03', '12') THEN
                    UPDATE productos
                    SET stock_actual = stock_actual + OLD.cantidad
                    WHERE id_producto = OLD.id_producto;
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar los triggers modificados
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_insert_detalle_venta");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_update_detalle_venta");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_delete_detalle_venta");
        
        // Recrear los triggers originales (sin validación)
        DB::unprepared("
            CREATE TRIGGER trg_after_insert_detalle_venta
            AFTER INSERT ON detalle_ventas
            FOR EACH ROW
            BEGIN
                UPDATE productos
                SET stock_actual = stock_actual - NEW.cantidad
                WHERE id_producto = NEW.id_producto;
            END
        ");
        
        DB::unprepared("
            CREATE TRIGGER trg_after_update_detalle_venta
            AFTER UPDATE ON detalle_ventas
            FOR EACH ROW
            BEGIN
                UPDATE productos
                SET stock_actual = stock_actual - (NEW.cantidad - OLD.cantidad)
                WHERE id_producto = NEW.id_producto;
            END
        ");
        
        DB::unprepared("
            CREATE TRIGGER trg_after_delete_detalle_venta
            AFTER DELETE ON detalle_ventas
            FOR EACH ROW
            BEGIN
                UPDATE productos
                SET stock_actual = stock_actual + OLD.cantidad
                WHERE id_producto = OLD.id_producto;
            END
        ");
    }
};
