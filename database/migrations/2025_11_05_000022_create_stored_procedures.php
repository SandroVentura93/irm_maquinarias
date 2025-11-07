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
        // Eliminar procedimientos almacenados existentes antes de crearlos
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_agregar_detalle_venta");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_generar_comprobante_preliminar");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_registrar_compra");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_registrar_venta");

        // Procedimiento sp_agregar_detalle_venta
        DB::unprepared("CREATE PROCEDURE sp_agregar_detalle_venta (
            IN p_id_venta BIGINT, 
            IN p_id_producto BIGINT, 
            IN p_cantidad INT, 
            IN p_precio DECIMAL(10,2)
        )
        BEGIN
            DECLARE v_subtotal DECIMAL(10,2);

            -- Calcular subtotal del producto
            SET v_subtotal = p_cantidad * p_precio;

            -- Insertar detalle de venta
            INSERT INTO detalle_ventas (
                id_venta, id_producto, cantidad, precio_unitario, subtotal, created_at
            )
            VALUES (p_id_venta, p_id_producto, p_cantidad, p_precio, v_subtotal, NOW());

            -- Actualizar totales en la tabla ventas
            UPDATE ventas
            SET subtotal = (
                    SELECT IFNULL(SUM(subtotal), 0)
                    FROM detalle_ventas
                    WHERE id_venta = p_id_venta
                ),
                igv = subtotal * 0.18,
                total = subtotal + (subtotal * 0.18)
            WHERE id_venta = p_id_venta;
        END");

        // Procedimiento sp_generar_comprobante_preliminar
        DB::unprepared("CREATE PROCEDURE sp_generar_comprobante_preliminar (
            IN p_id_venta BIGINT, 
            IN p_id_tipo_comprobante TINYINT, 
            IN p_serie VARCHAR(10), 
            IN p_usuario VARCHAR(100)
        )
        BEGIN
            DECLARE v_numero BIGINT DEFAULT 0;
            DECLARE v_monto_total DECIMAL(12,2) DEFAULT 0.00;
            DECLARE v_moneda_id TINYINT DEFAULT 1;
            DECLARE v_cliente_ruc VARCHAR(15);
            DECLARE v_cliente_nombre VARCHAR(200);

            IF p_id_venta IS NOT NULL THEN
                SELECT total, id_moneda,
                    (SELECT numero_documento FROM clientes WHERE id_cliente = ventas.id_cliente),
                    (SELECT COALESCE(razon_social, nombre) FROM clientes WHERE id_cliente = ventas.id_cliente)
                INTO v_monto_total, v_moneda_id, v_cliente_ruc, v_cliente_nombre
                FROM ventas WHERE id_venta = p_id_venta LIMIT 1;
            END IF;

            START TRANSACTION;

            INSERT INTO correlativos_comprobantes (id_tipo_comprobante, serie, ultimo_numero)
            VALUES (p_id_tipo_comprobante, p_serie, 0)
            ON DUPLICATE KEY UPDATE id_correlativo = id_correlativo;

            UPDATE correlativos_comprobantes
            SET ultimo_numero = ultimo_numero + 1
            WHERE id_tipo_comprobante = p_id_tipo_comprobante AND serie = p_serie;

            SELECT ultimo_numero INTO v_numero
            FROM correlativos_comprobantes
            WHERE id_tipo_comprobante = p_id_tipo_comprobante AND serie = p_serie
            FOR UPDATE;

            INSERT INTO comprobantes_electronicos (
                id_venta, id_tipo_comprobante, serie, numero,
                cliente_ruc, cliente_razon_social, fecha_emision,
                monto_subtotal, monto_igv, monto_total, moneda_id,
                estado, usuario_genero, created_at
            )
            VALUES (
                p_id_venta, p_id_tipo_comprobante, p_serie, v_numero,
                v_cliente_ruc, v_cliente_nombre, NOW(),
                v_monto_total, 0.00, v_monto_total, v_moneda_id,
                'PENDIENTE', p_usuario, NOW()
            );

            COMMIT;

            SELECT v_numero AS numero_asignado, LAST_INSERT_ID() AS id_comprobante;
        END");

        // Procedimiento sp_registrar_compra
        DB::unprepared("CREATE PROCEDURE sp_registrar_compra (
            IN p_id_proveedor BIGINT, 
            IN p_id_moneda TINYINT, 
            IN p_id_usuario BIGINT
        )
        BEGIN
            INSERT INTO compras (
                id_proveedor, id_moneda, fecha, subtotal, igv, total, created_at
            )
            VALUES (
                p_id_proveedor, p_id_moneda, NOW(), 0.00, 0.00, 0.00, NOW()
            );

            SELECT LAST_INSERT_ID() AS id_compra;
        END");

        // Procedimiento sp_registrar_venta
        DB::unprepared("CREATE PROCEDURE sp_registrar_venta (
            IN p_id_cliente BIGINT, 
            IN p_id_moneda TINYINT, 
            IN p_id_tipo_comprobante TINYINT, 
            IN p_serie VARCHAR(10), 
            IN p_numero VARCHAR(20), 
            IN p_metodo_pago VARCHAR(50), 
            IN p_id_usuario BIGINT
        )
        BEGIN
            INSERT INTO ventas (
                id_cliente, id_moneda, id_tipo_comprobante, serie, numero,
                fecha, subtotal, igv, total, metodo_pago, id_usuario, created_at
            )
            VALUES (
                p_id_cliente, p_id_moneda, p_id_tipo_comprobante, p_serie, p_numero,
                NOW(), 0.00, 0.00, 0.00, p_metodo_pago, p_id_usuario, NOW()
            );

            SELECT LAST_INSERT_ID() AS id_venta;
        END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_agregar_detalle_venta");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_generar_comprobante_preliminar");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_registrar_compra");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_registrar_venta");
    }
};