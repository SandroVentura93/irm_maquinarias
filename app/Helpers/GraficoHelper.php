<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class GraficoHelper
{
    /**
     * Genera y guarda un gráfico como imagen PNG usando QuickChart API.
     * @param array $labels
     * @param array $data
     * @param string $title
     * @param string $filename
     * @return string Ruta del archivo generado
     */
    public static function generarGraficoDiario($labels, $data, $title, $filename = null)
    {
        $chartConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => $title,
                    'data' => $data,
                    'backgroundColor' => ['#43cea2', '#ee0979', '#0072ff'],
                ]]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => $title
                ]
            ]
        ];
        $url = 'https://quickchart.io/chart';
        $response = file_get_contents($url . '?c=' . urlencode(json_encode($chartConfig)) . '&format=png&width=600&height=300');
        $filename = $filename ?: 'grafico_diario_' . Str::random(8) . '.png';
        $path = storage_path('app/public/' . $filename);
        File::put($path, $response);
        return $path;
    }
    /**
     * Genera y guarda un gráfico trimestral como imagen PNG usando QuickChart API.
     * @param array $labels Meses abreviados
     * @param array $ventas_chart Datos de ventas por mes
     * @param array $compras_chart Datos de compras por mes
     * @param array $ganancias_chart Datos de ganancias por mes
     * @param string $title Título del gráfico
     * @param string|null $filename Nombre de archivo opcional
     * @return string Ruta del archivo generado
     */
    public static function generarGraficoTrimestral($labels, $ventas_chart, $compras_chart, $ganancias_chart, $title, $filename = null)
    {
        $chartConfig = [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Ventas',
                        'data' => $ventas_chart,
                        'backgroundColor' => '#43cea2',
                    ],
                    [
                        'label' => 'Compras',
                        'data' => $compras_chart,
                        'backgroundColor' => '#ee0979',
                    ],
                    [
                        'label' => 'Ganancia',
                        'data' => $ganancias_chart,
                        'backgroundColor' => '#0072ff',
                    ]
                ]
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text' => $title
                ],
                'scales' => [
                    'xAxes' => [[ 'stacked' => false ]],
                    'yAxes' => [[ 'stacked' => false ]]
                ]
            ]
        ];
        $url = 'https://quickchart.io/chart';
        $response = file_get_contents($url . '?c=' . urlencode(json_encode($chartConfig)) . '&format=png&width=600&height=300');
        $filename = $filename ?: 'grafico_trimestral_' . \Illuminate\Support\Str::random(8) . '.png';
        $path = storage_path('app/public/' . $filename);
        \Illuminate\Support\Facades\File::put($path, $response);
        return $path;
    }
}
