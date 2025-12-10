<?php
require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

// Usamos DB facade
\DB::enableQueryLog();

echo "Monedas:\n";
$monedas = \App\Models\Moneda::select('id_moneda','codigo_iso')->get();
foreach ($monedas as $m) {
    echo "{$m->id_moneda} -> {$m->codigo_iso}\n";
}

echo "\nSum total por moneda (SUM total):\n";
$sumTotal = \DB::table('ventas')->select('id_moneda', \DB::raw('COUNT(*) as cnt, SUM(total) as sum_total'))
    ->whereIn('xml_estado',['ACEPTADO','PENDIENTE'])
    ->groupBy('id_moneda')
    ->get();
foreach ($sumTotal as $r) {
    echo "{$r->id_moneda} : cnt={$r->cnt} sum_total={$r->sum_total}\n";
}

echo "\nSum con CASE (ACEPTADO total, PENDIENTE total - saldo):\n";
$sumCase = \DB::table('ventas')->select('id_moneda', \DB::raw('SUM(CASE WHEN xml_estado = "ACEPTADO" THEN total WHEN xml_estado = "PENDIENTE" THEN total - saldo ELSE 0 END) as monto'))
    ->whereIn('xml_estado',['ACEPTADO','PENDIENTE'])
    ->groupBy('id_moneda')
    ->get();
foreach ($sumCase as $r) {
    echo "{$r->id_moneda} : monto={$r->monto}\n";
}

echo "\nDone.\n";
