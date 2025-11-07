<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddAnuladoStatusToVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE ventas MODIFY COLUMN xml_estado ENUM('PENDIENTE','ENVIADO','ACEPTADO','RECHAZADO','ANULADO') NOT NULL DEFAULT 'PENDIENTE'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE ventas MODIFY COLUMN xml_estado ENUM('PENDIENTE','ENVIADO','ACEPTADO','RECHAZADO') NOT NULL DEFAULT 'PENDIENTE'");
    }
}
