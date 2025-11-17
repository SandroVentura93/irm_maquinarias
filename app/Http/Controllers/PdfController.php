<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Cliente;
use App\Models\Parametro;
use App\Models\TipoComprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use PDF;
use Illuminate\Support\Str;

class PdfController extends Controller
{
    /**
     * Obtener configuración del tipo de comprobante consultando la base de datos
     * y mapeando según los datos reales
     */
    private function getConfiguracionTipoComprobante($tipoComprobante)
    {
        // Templates y configuraciones por código SUNAT
        $configuracionesTemplate = [
            // ['codigo_sunat' => '01', 'descripcion' => 'Factura']
            '01' => [
                'codigo_sunat' => '01',
                'aplica_igv' => true,
                'template' => 'factura',
                'titulo' => 'FACTURA ELECTRÓNICA',
                'subtitulo' => 'Comprobante de pago para empresas con RUC',
                'descripcion' => 'Factura'
            ],
            
            // ['codigo_sunat' => '03', 'descripcion' => 'Boleta de Venta']
            '03' => [
                'codigo_sunat' => '03',
                'aplica_igv' => true,
                'template' => 'boleta',
                'titulo' => 'BOLETA DE VENTA ELECTRÓNICA',
                'subtitulo' => 'Comprobante de pago para personas naturales',
                'descripcion' => 'Boleta de Venta'
            ],
            
            // ['codigo_sunat' => '07', 'descripcion' => 'Nota de Crédito']
            '07' => [
                'codigo_sunat' => '07',
                'aplica_igv' => true,
                'template' => 'nota_credito',
                'titulo' => 'NOTA DE CRÉDITO ELECTRÓNICA',
                'subtitulo' => 'Documento para anular o reducir el valor de un comprobante',
                'descripcion' => 'Nota de Crédito'
            ],
            
            // ['codigo_sunat' => '08', 'descripcion' => 'Nota de Débito']
            '08' => [
                'codigo_sunat' => '08',
                'aplica_igv' => true,
                'template' => 'nota_debito',
                'titulo' => 'NOTA DE DÉBITO ELECTRÓNICA',
                'subtitulo' => 'Documento para aumentar el valor de un comprobante',
                'descripcion' => 'Nota de Débito'
            ],
            
            // ['codigo_sunat' => '09', 'descripcion' => 'Guía de Remisión']
            '09' => [
                'codigo_sunat' => '09',
                'aplica_igv' => false,
                'template' => 'guia_remision',
                'titulo' => 'GUÍA DE REMISIÓN ELECTRÓNICA',
                'subtitulo' => 'Documento para sustentar el traslado de bienes',
                'descripcion' => 'Guía de Remisión'
            ],
            
            // ['codigo_sunat' => '12', 'descripcion' => 'Ticket de Máquina Registradora']
            '12' => [
                'codigo_sunat' => '12',
                'aplica_igv' => true,
                'template' => 'ticket',
                'titulo' => 'TICKET DE MÁQUINA REGISTRADORA',
                'subtitulo' => 'Comprobante emitido por máquina registradora',
                'descripcion' => 'Ticket de Máquina Registradora'
            ],
            
            // ['codigo_sunat' => '14', 'descripcion' => 'Recibo por Honorarios']
            '14' => [
                'codigo_sunat' => '14',
                'aplica_igv' => false,
                'template' => 'recibo_honorarios',
                'titulo' => 'RECIBO POR HONORARIOS',
                'subtitulo' => 'Comprobante por servicios profesionales independientes',
                'descripcion' => 'Recibo por Honorarios'
            ],
            
            // ['codigo_sunat' => 'CT', 'descripcion' => 'Cotización']
            'CT' => [
                'codigo_sunat' => 'CT',
                'aplica_igv' => false,
                'template' => 'cotizacion',
                'titulo' => 'COTIZACIÓN',
                'subtitulo' => 'Documento pre-venta sin valor tributario',
                'descripcion' => 'Cotización'
            ]
        ];



        // Determinar el código SUNAT del tipo de comprobante
        $codigoSunat = null;
        
        if (is_object($tipoComprobante)) {
            // Si viene de la relación del modelo
            $codigoSunat = $tipoComprobante->codigo_sunat ?? null;
        } elseif (is_numeric($tipoComprobante)) {
            // Si es un ID, consultar la base de datos
            $tipo = \App\Models\TipoComprobante::find($tipoComprobante);
            $codigoSunat = $tipo->codigo_sunat ?? null;
        } elseif (is_string($tipoComprobante)) {
            // Si ya es un código SUNAT
            $codigoSunat = $tipoComprobante;
        }
        
        // Obtener las configuraciones de template
        $configuracionesTemplate = $this->getConfiguracionesTemplate();
        
        // Retornar la configuración correspondiente
        return isset($configuracionesTemplate[$codigoSunat]) ? $configuracionesTemplate[$codigoSunat] : null;
    }

    /**
     * Obtener mapeo dinámico de todos los tipos de comprobante de la base de datos
     */
    private function getMapeoTiposComprobante()
    {
        static $mapeo = null;
        
        if ($mapeo === null) {
            $tipos = \App\Models\TipoComprobante::all();
            $mapeo = [];
            
            foreach ($tipos as $tipo) {
                $mapeo[$tipo->id_tipo_comprobante] = [
                    'id' => $tipo->id_tipo_comprobante,
                    'codigo_sunat' => $tipo->codigo_sunat,
                    'descripcion' => $tipo->descripcion
                ];
            }
        }
        
        return $mapeo;
    }

    /**
     * Debug: Mostrar el mapeo actual de tipos de comprobante
     */
    public function debugTiposComprobante()
    {
        $mapeo = $this->getMapeoTiposComprobante();
        $configuraciones = $this->getConfiguracionesTemplate();
        
        $resultado = [];
        foreach ($mapeo as $id => $tipo) {
            $config = $configuraciones[$tipo['codigo_sunat']] ?? null;
            $resultado[] = [
                'id_bd' => $id,
                'codigo_sunat' => $tipo['codigo_sunat'],
                'descripcion_bd' => $tipo['descripcion'],
                'template' => $config['template'] ?? 'NO DEFINIDO',
                'titulo_pdf' => $config['titulo'] ?? 'NO DEFINIDO'
            ];
        }
        
        return response()->json($resultado);
    }

    /**
     * Obtener solo las configuraciones de template (método auxiliar)
     */
    private function getConfiguracionesTemplate()
    {
        return [
            // Templates y configuraciones por código SUNAT
            '01' => [
                'codigo_sunat' => '01',
                'aplica_igv' => true,
                'template' => 'factura',
                'titulo' => 'FACTURA ELECTRÓNICA',
                'subtitulo' => 'Comprobante de pago para empresas con RUC',
                'descripcion' => 'Factura'
            ],
            '03' => [
                'codigo_sunat' => '03',
                'aplica_igv' => true,
                'template' => 'boleta',
                'titulo' => 'BOLETA DE VENTA ELECTRÓNICA',
                'subtitulo' => 'Comprobante de pago para personas naturales',
                'descripcion' => 'Boleta de Venta'
            ],
            '07' => [
                'codigo_sunat' => '07',
                'aplica_igv' => true,
                'template' => 'nota_credito',
                'titulo' => 'NOTA DE CRÉDITO ELECTRÓNICA',
                'subtitulo' => 'Documento para anular o reducir el valor de un comprobante',
                'descripcion' => 'Nota de Crédito'
            ],
            '08' => [
                'codigo_sunat' => '08',
                'aplica_igv' => true,
                'template' => 'nota_debito',
                'titulo' => 'NOTA DE DÉBITO ELECTRÓNICA',
                'subtitulo' => 'Documento para aumentar el valor de un comprobante',
                'descripcion' => 'Nota de Débito'
            ],
            '09' => [
                'codigo_sunat' => '09',
                'aplica_igv' => false,
                'template' => 'guia_remision',
                'titulo' => 'GUÍA DE REMISIÓN ELECTRÓNICA',
                'subtitulo' => 'Documento para sustentar el traslado de bienes',
                'descripcion' => 'Guía de Remisión'
            ],
            '12' => [
                'codigo_sunat' => '12',
                'aplica_igv' => true,
                'template' => 'ticket',
                'titulo' => 'TICKET DE MÁQUINA REGISTRADORA',
                'subtitulo' => 'Comprobante emitido por máquina registradora',
                'descripcion' => 'Ticket de Máquina Registradora'
            ],
            '14' => [
                'codigo_sunat' => '14',
                'aplica_igv' => false,
                'template' => 'recibo_honorarios',
                'titulo' => 'RECIBO POR HONORARIOS',
                'subtitulo' => 'Comprobante por servicios profesionales independientes',
                'descripcion' => 'Recibo por Honorarios'
            ],
            'CT' => [
                'codigo_sunat' => 'CT',
                'aplica_igv' => false,
                'template' => 'cotizacion',
                'titulo' => 'COTIZACIÓN',
                'subtitulo' => 'Documento pre-venta sin valor tributario',
                'descripcion' => 'Cotización'
            ]
        ];
    }

    /**
     * Generar PDF de un comprobante específico
     */
    public function generatePdf($ventaId)
    {
        $startTime = microtime(true);
        
        try {
            // ⚡ Cache optimizado para descargas 
            $cacheKey = "pdf_download_venta_{$ventaId}";
            
            $venta = Cache::remember($cacheKey, 300, function() use ($ventaId) { // 5 minutos
                return Venta::with([
                    'cliente',
                    'detalles.producto.categoria',
                    'detalles.producto.marca',
                    'tipoComprobante'
                ])->findOrFail($ventaId);
            });

            $tipoConfig = $this->getConfiguracionTipoComprobante($venta->tipoComprobante);
            
            if (!$tipoConfig) {
                $debugInfo = "ID: {$venta->id_tipo_comprobante}, Código SUNAT: " . ($venta->tipoComprobante->codigo_sunat ?? 'N/A') . ", Descripción: " . ($venta->tipoComprobante->descripcion ?? 'N/A');
                throw new \Exception("Tipo de comprobante no soportado. {$debugInfo}");
            }

            // ⚡ Cache de datos de empresa
            $empresa = Cache::remember('datos_empresa', 3600, function() { // 1 hora
                return $this->getDatosEmpresa();
            });

            $datos = $this->prepararDatosComprobante($venta, $tipoConfig);

            // ⚡ Configuración optimizada de DomPDF
            $pdf = PDF::loadView('comprobantes.' . $tipoConfig['template'], compact('venta', 'datos', 'empresa', 'tipoConfig'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'Arial, sans-serif',
                    'dpi' => 96, // Performance optimizado
                    'enable_php' => false,
                    'enable_javascript' => false,
                    'enable_html5_parser' => true,
                    'margin_top' => 8,
                    'margin_bottom' => 8,
                    'margin_left' => 8,
                    'margin_right' => 8,
                    'chroot' => base_path('resources/views')
                ]);

            $nombreArchivo = $this->generarNombreArchivo($venta, $tipoConfig);
            
            // ⚡ Performance logging
            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000; // en millisegundos
            
            Log::info("PDF descargado exitosamente", [
                'venta_id' => $ventaId,
                'tipo' => $tipoConfig['descripcion'],
                'archivo' => $nombreArchivo,
                'tiempo_ms' => round($executionTime, 2)
            ]);

            return $pdf->download($nombreArchivo);

        } catch (\Exception $e) {
            Log::error("Error al generar PDF para descarga", [
                'venta_id' => $ventaId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tiempo_ms' => round((microtime(true) - $startTime) * 1000, 2)
            ]);
            
            return response()->json([
                'error' => true,
                'message' => 'Error al generar PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ver PDF en el navegador
     */
    public function viewPdf($ventaId)
    {
        try {
            // ⚡ Cache de consulta para mejorar performance
            $cacheKey = "pdf_venta_{$ventaId}";
            
            $venta = Cache::remember($cacheKey, 300, function() use ($ventaId) { // 5 minutos
                return Venta::with([
                    'cliente',
                    'detalles.producto.categoria',
                    'detalles.producto.marca', 
                    'tipoComprobante'
                ])->findOrFail($ventaId);
            });

            $tipoConfig = $this->getConfiguracionTipoComprobante($venta->tipoComprobante);
            
            if (!$tipoConfig) {
                throw new \Exception("Tipo de comprobante no soportado. ID: {$venta->id_tipo_comprobante}, Descripción: " . ($venta->tipoComprobante->descripcion ?? 'N/A'));
            }

            // ⚡ Cache de datos de empresa
            $empresa = Cache::remember('datos_empresa', 3600, function() { // 1 hora
                return $this->getDatosEmpresa();
            });
            
            $datos = $this->prepararDatosComprobante($venta, $tipoConfig);

            // ⚡ Optimizaciones de DomPDF
            $pdf = PDF::loadView('comprobantes.' . $tipoConfig['template'], compact('venta', 'datos', 'empresa', 'tipoConfig'))
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'Arial, sans-serif',
                    'dpi' => 96, // Reducir DPI para mejor performance
                    'enable_php' => false,
                    'enable_javascript' => false,
                    'enable_html5_parser' => true,
                    'chroot' => base_path('resources/views')
                ]);

            // ⚡ Log para debugging performance
            Log::info("PDF generado exitosamente", [
                'venta_id' => $ventaId,
                'tipo' => $tipoConfig['descripcion'],
                'tiempo_ms' => microtime(true) * 1000
            ]);

            return $pdf->stream();

        } catch (\Exception $e) {
            Log::error("Error al generar PDF", [
                'venta_id' => $ventaId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => true,
                'message' => 'Error al visualizar PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preparar datos específicos para cada tipo de comprobante
     */
    private function prepararDatosComprobante($venta, $tipoConfig)
    {
        $datos = [
            'subtotal' => 0,
            'descuento_total' => 0,
            'base_imponible' => 0,
            'igv' => 0,
            'total' => 0,
            'total_en_letras' => '',
            'observaciones' => '',
            'condiciones_pago' => 'Contado',
            'fecha_vencimiento' => null
        ];

        // Calcular totales
        foreach ($venta->detalles as $detalle) {
            $subtotal_detalle = $detalle->cantidad * $detalle->precio_unitario;
            $descuento_detalle = $subtotal_detalle * ($detalle->descuento_porcentaje / 100);
            $neto_detalle = $subtotal_detalle - $descuento_detalle;

            $datos['subtotal'] += $subtotal_detalle;
            $datos['descuento_total'] += $descuento_detalle;
            $datos['base_imponible'] += $neto_detalle;
        }

        // Aplicar IGV solo si corresponde
        if ($tipoConfig['aplica_igv']) {
            $datos['igv'] = $datos['base_imponible'] * 0.18;
        }

        $datos['total'] = $datos['base_imponible'] + $datos['igv'];
        $datos['total_en_letras'] = $this->numeroALetras($datos['total']);

        // Configuraciones específicas por tipo
        switch ($venta->tipo_comprobante) {
            case 'Cotización':
                $datos['observaciones'] = 'Esta cotización es válida por 30 días calendario desde su emisión.';
                $datos['fecha_vencimiento'] = now()->addDays(30);
                break;

            case 'Guía de Remisión':
                $datos['observaciones'] = 'Documento que sustenta el traslado de bienes.';
                $datos['motivo_traslado'] = 'Venta';
                $datos['peso_bruto'] = $this->calcularPesoBruto($venta);
                break;

            case 'Recibo por Honorarios':
                $datos['observaciones'] = 'Servicios profesionales independientes.';
                $datos['retencion'] = $datos['base_imponible'] * 0.08; // Retención 8%
                $datos['total'] = $datos['base_imponible'] - $datos['retencion'];
                break;

            case 'Ticket de Máquina Registradora':
                $datos['observaciones'] = 'Ticket emitido por máquina registradora.';
                break;

            default:
                $datos['observaciones'] = 'Comprobante electrónico emitido según normativa SUNAT.';
        }

        return $datos;
    }

    /**
     * Obtener datos de la empresa
     */
    private function getDatosEmpresa()
    {
        // En el futuro esto podría venir de una tabla de configuración
        return [
            'razon_social' => env('EMPRESA_RAZON_SOCIAL', 'IRM MAQUINARIAS SRL'),
            'ruc' => env('EMPRESA_RUC', '20123456789'),
            'direccion' => env('EMPRESA_DIRECCION', 'Av. Industrial 123, Lima, Perú'),
            'telefono' => env('EMPRESA_TELEFONO', '(01) 234-5678'),
            'email' => env('EMPRESA_EMAIL', 'ventas@irmmaquinarias.com'),
            'web' => env('EMPRESA_WEB', 'www.irmmaquinarias.com'),
            'logo_path' => $this->getLogoPath(),
            'logo_base64' => $this->getLogoBase64()
        ];
    }

    /**
     * Obtener la ruta del logo
     */
    private function getLogoPath()
    {
        $logoPath = public_path('images/logo.png');
        if (file_exists($logoPath)) {
            return $logoPath;
        }
        
        $logoSvgPath = public_path('images/logo.svg');
        if (file_exists($logoSvgPath)) {
            return $logoSvgPath;
        }
        
        return null;
    }

    /**
     * Obtener logo en base64 para embedding en PDF
     */
    private function getLogoBase64()
    {
        $logoPath = $this->getLogoPath();
        
        if ($logoPath && file_exists($logoPath)) {
            $imageData = file_get_contents($logoPath);
            $mimeType = mime_content_type($logoPath);
            return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }
        
        // Logo SVG por defecto si no se encuentra ningún archivo
        $defaultSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 100" style="background:#f39c12">
            <rect width="200" height="100" fill="#f39c12"/>
            <circle cx="100" cy="50" r="35" fill="#2c3e50"/>
            <text x="100" y="58" font-family="Arial" font-size="18" font-weight="bold" text-anchor="middle" fill="white">IRM</text>
            <text x="100" y="75" font-family="Arial" font-size="8" text-anchor="middle" fill="white">MAQUINARIAS</text>
        </svg>';
        
        return 'data:image/svg+xml;base64,' . base64_encode($defaultSvg);
    }

    /**
     * Generar múltiples PDFs (por lote)
     */
    public function generarLotePdfs(Request $request)
    {
        $ventaIds = $request->input('venta_ids', []);
        $ventas = Venta::whereIn('id', $ventaIds)->get();
        
        if ($ventas->isEmpty()) {
            return response()->json([
                'error' => 'No se encontraron ventas válidas'
            ], 404);
        }

        $zip = new \ZipArchive();
        $zipFileName = 'comprobantes_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Crear directorio temporal si no existe
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            return response()->json(['error' => 'No se pudo crear el archivo ZIP'], 500);
        }

        foreach ($ventas as $venta) {
            $pdf = $this->generarPdfVenta($venta);
            $nombreArchivo = $this->getNombreArchivo($venta) . '.pdf';
            $zip->addFromString($nombreArchivo, $pdf->output());
        }

        $zip->close();

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Enviar PDF por email
     */
    public function enviarPorEmail(Request $request, $ventaId)
    {
        $venta = Venta::with(['cliente', 'detalles.producto', 'tipoComprobante', 'moneda'])
                      ->findOrFail($ventaId);
        
        $email = $request->input('email') ?: $venta->cliente->email;
        
        if (!$email) {
            return response()->json([
                'error' => 'El cliente no tiene email registrado'
            ], 400);
        }

        $pdf = $this->generarPdfVenta($venta);
        $nombreArchivo = $this->getNombreArchivo($venta) . '.pdf';

        try {
            Mail::send('emails.comprobante', compact('venta'), function ($message) use ($email, $pdf, $nombreArchivo, $venta) {
                $message->to($email)
                        ->subject('Comprobante Electrónico - ' . $venta->serie . '-' . $venta->numero)
                        ->attachData($pdf->output(), $nombreArchivo, [
                            'mime' => 'application/pdf',
                        ]);
            });

            return response()->json([
                'success' => 'PDF enviado exitosamente a ' . $email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al enviar el email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de PDFs generados
     */
    public function estadisticasPdf()
    {
        $stats = [
            'total_comprobantes' => Venta::count(),
            'por_tipo' => Venta::select('tipo_comprobante_id')
                               ->selectRaw('COUNT(*) as cantidad')
                               ->with('tipoComprobante')
                               ->groupBy('tipo_comprobante_id')
                               ->get(),
            'ultimo_mes' => Venta::where('created_at', '>=', now()->subMonth())->count(),
            'hoy' => Venta::whereDate('created_at', now()->toDateString())->count()
        ];

        return response()->json($stats);
    }

    /**
     * Generar nombre del archivo PDF
     */
    private function generarNombreArchivo($venta, $tipoConfig)
    {
        $prefijo = strtoupper(Str::slug($venta->tipo_comprobante, '_'));
        $serie = $venta->serie;
        $numero = str_pad($venta->numero_comprobante, 8, '0', STR_PAD_LEFT);
        $fecha = $venta->fecha->format('Y-m-d');

        return "{$prefijo}_{$serie}_{$numero}_{$fecha}.pdf";
    }

    /**
     * Convertir número a letras
     */
    private function numeroALetras($numero)
    {
        // Implementación básica - puedes usar una librería más completa
        $entero = intval($numero);
        $decimales = intval(($numero - $entero) * 100);
        
        // Por simplicidad, retorno básico
        return "SON: " . strtoupper($this->convertirNumeroALetras($entero)) . " CON {$decimales}/100 SOLES";
    }

    /**
     * Convertir número entero a letras (implementación simplificada)
     */
    private function convertirNumeroALetras($numero)
    {
        if ($numero == 0) return "CERO";
        
        $unidades = ["", "UNO", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE"];
        $decenas = ["", "", "VEINTE", "TREINTA", "CUARENTA", "CINCUENTA", "SESENTA", "SETENTA", "OCHENTA", "NOVENTA"];
        $especiales = ["DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE"];
        
        if ($numero < 10) {
            return $unidades[$numero];
        } elseif ($numero < 20) {
            return $especiales[$numero - 10];
        } elseif ($numero < 100) {
            $d = intval($numero / 10);
            $u = $numero % 10;
            return $decenas[$d] . ($u > 0 ? " Y " . $unidades[$u] : "");
        }
        
        // Para números más grandes, implementar lógica completa
        return "NÚMERO MAYOR";
    }

    /**
     * Calcular peso bruto para guías de remisión
     */
    private function calcularPesoBruto($venta)
    {
        $peso = 0;
        foreach ($venta->detalles as $detalle) {
            // Asumir peso promedio si no está en la base de datos
            $peso += $detalle->cantidad * 1; // 1 kg por item (ajustar según necesidad)
        }
        return number_format($peso, 2) . ' KG';
    }

    /**
     * Generar código QR para comprobantes electrónicos
     */
    public function generarQR($ventaId)
    {
        try {
            $venta = Venta::findOrFail($ventaId);
            
            $tipoConfig = $this->getConfiguracionTipoComprobante($venta->tipoComprobante);
            
            // Datos para QR según formato SUNAT
            $qrData = implode('|', [
                '20123456789', // RUC emisor
                $tipoConfig['codigo_sunat'] ?? '01',
                $venta->serie,
                str_pad($venta->numero_comprobante, 8, '0', STR_PAD_LEFT),
                number_format($venta->total * 0.18, 2, '.', ''), // IGV
                number_format($venta->total, 2, '.', ''), // Total
                $venta->fecha->format('Y-m-d'),
                '6', // Tipo doc cliente (RUC=6, DNI=1)
                $venta->cliente->numero_documento ?? ''
            ]);

            // Generar QR usando librería (requiere instalar simplesoftwareio/simple-qrcode)
            // QrCode::format('png')->size(100)->generate($qrData);
            
            return response()->json(['qr_data' => $qrData]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Generar PDF para una venta específica (método auxiliar)
     */
    private function generarPdfVenta($venta)
    {
        // Obtener configuración del tipo de comprobante
        $tipoConfig = $this->getConfiguracionTipoComprobante($venta->tipoComprobante);
        
        if (!$tipoConfig) {
            throw new \Exception("Tipo de comprobante no soportado. ID: {$venta->id_tipo_comprobante}");
        }

        // Obtener datos de la empresa
        $empresa = $this->getDatosEmpresa();

        // Preparar datos específicos según el tipo
        $datos = $this->prepararDatosComprobante($venta, $tipoConfig);

        // Renderizar la vista correspondiente
        return PDF::loadView('comprobantes.' . $tipoConfig['template'], compact('venta', 'datos', 'empresa', 'tipoConfig'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
                'margin_right' => 10
            ]);
    }

    /**
     * Obtener nombre de archivo para el PDF
     */
    private function getNombreArchivo($venta)
    {
        $tipoConfig = $this->getConfiguracionTipoComprobante($venta->tipoComprobante);
        $descripcion = $tipoConfig['descripcion'] ?? 'comprobante';
        
        return strtolower(str_replace(' ', '_', $descripcion)) . '_' . $venta->serie . '_' . str_pad($venta->numero_comprobante, 8, '0', STR_PAD_LEFT);
    }
}