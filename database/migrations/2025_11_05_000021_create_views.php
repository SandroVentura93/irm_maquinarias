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
        // Crear vista vw_reporte_compras_diario
        DB::statement("CREATE OR REPLACE VIEW vw_reporte_compras_diario AS
            SELECT 
                CAST(c.fecha AS DATE) AS fecha,
                COUNT(DISTINCT c.id_compra) AS numero_comprobantes,
                COALESCE(SUM(c.total), 0) AS total_compras,
                COALESCE(SUM(dc.cantidad), 0) AS total_productos_comprados
            FROM compras c
            LEFT JOIN detalle_compras dc ON dc.id_compra = c.id_compra
            WHERE CAST(c.fecha AS DATE) = CURDATE()
            GROUP BY CAST(c.fecha AS DATE)
        ");

        // Crear vista vw_reporte_compras_mensual
        DB::statement("CREATE OR REPLACE VIEW vw_reporte_compras_mensual AS
            SELECT 
                CAST(c.fecha AS DATE) AS fecha,
                COUNT(DISTINCT c.id_compra) AS numero_comprobantes,
                COALESCE(SUM(c.total), 0) AS total_compras,
                COALESCE(SUM(dc.cantidad), 0) AS total_productos_comprados
            FROM compras c
            LEFT JOIN detalle_compras dc ON dc.id_compra = c.id_compra
            WHERE YEAR(c.fecha) = YEAR(CURDATE()) AND MONTH(c.fecha) = MONTH(CURDATE())
            GROUP BY CAST(c.fecha AS DATE)
            ORDER BY CAST(c.fecha AS DATE) ASC
        ");

        // Crear vista vw_reporte_ventas_diario
        DB::statement("CREATE OR REPLACE VIEW vw_reporte_ventas_diario AS
            SELECT 
                CAST(v.fecha AS DATE) AS fecha,
                COUNT(DISTINCT v.id_venta) AS numero_comprobantes,
                COALESCE(SUM(v.total), 0) AS total_ventas,
                COALESCE(SUM(d.cantidad), 0) AS total_productos_vendidos
            FROM ventas v
            LEFT JOIN detalle_ventas d ON d.id_venta = v.id_venta
            WHERE CAST(v.fecha AS DATE) = CURDATE()
            GROUP BY CAST(v.fecha AS DATE)
        ");

        // Crear vista vw_reporte_ventas_mensual
        DB::statement("CREATE OR REPLACE VIEW vw_reporte_ventas_mensual AS
            SELECT 
                CAST(v.fecha AS DATE) AS fecha,
                COUNT(DISTINCT v.id_venta) AS numero_comprobantes,
                COALESCE(SUM(v.total), 0) AS total_ventas,
                COALESCE(SUM(d.cantidad), 0) AS total_productos_vendidos
            FROM ventas v
            LEFT JOIN detalle_ventas d ON d.id_venta = v.id_venta
            WHERE YEAR(v.fecha) = YEAR(CURDATE()) AND MONTH(v.fecha) = MONTH(CURDATE())
            GROUP BY CAST(v.fecha AS DATE)
            ORDER BY CAST(v.fecha AS DATE) ASC
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS vw_reporte_compras_diario");
        DB::statement("DROP VIEW IF EXISTS vw_reporte_compras_mensual");
        DB::statement("DROP VIEW IF EXISTS vw_reporte_ventas_diario");
        DB::statement("DROP VIEW IF EXISTS vw_reporte_ventas_mensual");
    }
};