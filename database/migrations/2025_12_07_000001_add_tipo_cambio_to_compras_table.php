<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            if (!Schema::hasColumn('compras', 'tipo_cambio')) {
                $table->decimal('tipo_cambio', 10, 4)->nullable()->after('total');
            }
        });
    }

    public function down(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            if (Schema::hasColumn('compras', 'tipo_cambio')) {
                $table->dropColumn('tipo_cambio');
            }
        });
    }
};
