<?php


namespace App\Http\Controllers;

use App\Models\ComprobanteElectronico;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Cliente;
use App\Models\Venta;
use App\Models\PagoVenta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use PDF;
// ...existing use statements...

class VentaController extends Controller
{
    // Lista de ventas
    public function index(Request $request)
    {
        // ⚡ Optimización de consulta con paginación y filtros
        $query = Venta::with([
                'cliente:id_cliente,nombre,numero_documento',
                'tipoComprobante:id_tipo_comprobante,descripcion,codigo_sunat',
                'moneda:id_moneda,codigo_iso,simbolo'
            ])
            ->select('id_venta', 'id_cliente', 'id_tipo_comprobante', 'id_moneda', 'serie', 'numero', 'fecha', 'total', 'saldo', 'xml_estado', 'created_at');
        
        // Filtro por búsqueda de texto (número de documento o cliente)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero', 'LIKE', "%{$search}%")
                  ->orWhere('serie', 'LIKE', "%{$search}%")
                  ->orWhereHas('cliente', function($subQ) use ($search) {
                      $subQ->where('nombre', 'LIKE', "%{$search}%")
                           ->orWhere('numero_documento', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Filtro por tipo de comprobante
        if ($request->has('tipo_comprobante') && $request->tipo_comprobante != '') {
            $query->whereHas('tipoComprobante', function($q) use ($request) {
                $q->where('descripcion', $request->tipo_comprobante);
            });
        }
        
        // Filtro por estado XML
        if ($request->has('xml_estado') && $request->xml_estado != '') {
            $query->where('xml_estado', $request->xml_estado);
        }
        
        // Filtro por rango de fechas
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }
        
        $ventas = $query->orderBy('created_at', 'desc')
            ->limit(500) // Limitar resultados para mejor performance
            ->get();
        
        // Obtener tipo de cambio actual (ya tiene cache interno)
        $tipoCambio = $this->obtenerTipoCambio();
        
        return view('ventas.index', compact('ventas', 'tipoCambio'));
    }

    // Vista principal del formulario
    public function create()
    {
        // ⚡ Cache optimizado para datos que no cambian frecuentemente
        $ubigeos = Cache::remember('ubigeos_list', 86400, function() { // 24 horas
            return DB::table('ubigeos')
                ->select('id_ubigeo', DB::raw("CONCAT(departamento, ' - ', provincia, ' - ', distrito) as descripcion"))
                ->orderBy('departamento')
                ->orderBy('provincia') 
                ->orderBy('distrito')
                ->get();
        });
        
        // ⚡ Cache para tipos de comprobante
        $tiposComprobante = Cache::remember('tipos_comprobante_active', 3600, function() { // 1 hora
            return \App\Models\TipoComprobante::orderBy('codigo_sunat')->get();
        });
        
        // Obtener tipo de cambio actual (ya tiene cache interno)
        $tipoCambio = $this->obtenerTipoCambio();

        return view('ventas.create', compact('ubigeos', 'tiposComprobante', 'tipoCambio'));
    }

    // Método para obtener tipo de cambio actual
    private function obtenerTipoCambio()
    {
        try {
            // Intentar obtener desde cache primero (válido por 1 hora)
            $tipoCambioCache = \Cache::remember('tipo_cambio_usd_pen', 3600, function () {
                return $this->obtenerTipoCambioAPI();
            });
            
            if ($tipoCambioCache && $tipoCambioCache > 0) {
                return $tipoCambioCache;
            }
        } catch (\Exception $e) {
            \Log::warning('Error al obtener tipo de cambio desde cache: ' . $e->getMessage());
        }

        // Si falla el cache, intentar API directamente
        try {
            $tipoCambio = $this->obtenerTipoCambioAPI();
            if ($tipoCambio) {
                return $tipoCambio;
            }
        } catch (\Exception $e) {
            \Log::warning('Error al obtener tipo de cambio de API: ' . $e->getMessage());
        }

        // Valor por defecto actualizado (aproximado para Perú)
        return 3.73; // Valor aproximado, se actualiza automáticamente con las APIs
    }

    // Método para obtener tipo de cambio desde múltiples APIs
    private function obtenerTipoCambioAPI()
    {
        // 1. Intentar desde API de SUNAT
        try {
            $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
            $fecha = now()->format('d-m-Y');
            
            $response = $client->get("https://api.apis.net.pe/v1/tipo-cambio-sunat", [
                'query' => ['fecha' => $fecha]
            ]);
            
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                
                if (isset($data['compra']) && isset($data['venta'])) {
                    // Usar precio de COMPRA (para mostrar cuántos soles por dólar)
                    $tipoCambio = $data['compra'];
                    
                    // Guardar info en cache
                    \Cache::put('tipo_cambio_usd_pen_info', [
                        'fuente' => 'SUNAT',
                        'fecha_actualizacion' => now(),
                        'cache_hit' => false,
                        'compra' => $data['compra'],
                        'venta' => $data['venta']
                    ], 3600);
                    
                    \Log::info("Tipo de cambio obtenido de SUNAT (compra): {$tipoCambio}");
                    return $tipoCambio;
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Error al obtener tipo de cambio de SUNAT: ' . $e->getMessage());
        }
        
        // 2. Fallback: API alternativa
        try {
            $client = new \GuzzleHttp\Client(['verify' => false, 'timeout' => 5]);
            $response = $client->get("https://api.exchangerate-api.com/v4/latest/USD");
            
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                
                if (isset($data['rates']['PEN'])) {
                    $tipoCambio = $data['rates']['PEN'];
                    
                    \Cache::put('tipo_cambio_usd_pen_info', [
                        'fuente' => 'ExchangeRate-API',
                        'fecha_actualizacion' => now(),
                        'cache_hit' => false
                    ], 3600);
                    
                    \Log::info("Tipo de cambio obtenido de API alternativa: {$tipoCambio}");
                    return $tipoCambio;
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Error al obtener tipo de cambio de API alternativa: ' . $e->getMessage());
        }
        
        return null;
    }

    // Buscar cliente por RUC/DNI (mejorado)
    public function buscarCliente(Request $r)
    {
        try {
            // Aquí va la lógica real de búsqueda de cliente, por ahora solo placeholder
            return response()->json([
                'found' => false,
                'message' => 'Funcionalidad no implementada'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error en búsqueda de cliente: ' . $e->getMessage());
            return response()->json([
                'found' => false, 
                'message' => 'Error en la búsqueda'
            ], 500);
        }
    }

    // Buscar producto (optimizado con todos los campos, sin filtro de activo estricto)
    public function buscarProducto(Request $r)
    {
        $q = $r->query('q', '');
        
        // Validar longitud mínima
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        try {
            // Buscar en múltiples campos sin filtro estricto de activo
            $productos = Producto::where(function($query) use ($q) {
                $query->where('codigo', 'like', "%{$q}%")
                      ->orWhere('numero_parte', 'like', "%{$q}%")
                      ->orWhere('descripcion', 'like', "%{$q}%")
                      ->orWhere('modelo', 'like', "%{$q}%");
            })
            // Sin filtro de activo por ahora para evitar problemas
            ->select(
                'id_producto', 'id_categoria', 'id_marca', 'id_proveedor',
                'codigo', 'numero_parte', 'descripcion', 'modelo', 
                'peso', 'ubicacion', 'stock_actual', 'stock_minimo',
                'precio_compra', 'precio_venta', 'importado', 'activo'
            )
            ->limit(15)
            ->get()
            ->map(function($producto) {
                return [
                    'id_producto' => $producto->id_producto,
                    'codigo' => $producto->codigo ?? '',
                    'numero_parte' => $producto->numero_parte ?? '',
                    'descripcion' => $producto->descripcion ?? 'Sin descripción',
                    'modelo' => $producto->modelo ?? '',
                    'peso' => $producto->peso ?? 0,
                    'ubicacion' => $producto->ubicacion ?? 'Sin ubicación',
                    'stock_actual' => $producto->stock_actual ?? 0,
                    'stock_minimo' => $producto->stock_minimo ?? 0,
                    'precio_compra' => number_format($producto->precio_compra ?? 0, 2, '.', ''),
                    'precio_venta' => number_format($producto->precio_venta ?? 0, 2, '.', ''),
                    'importado' => $producto->importado ? 'Sí' : 'No',
                    'activo' => $producto->activo ? 'Activo' : 'Inactivo',
                    // Información básica sin relaciones por ahora
                    'categoria' => 'Sin categoría',
                    'marca' => 'Sin marca',
                    'proveedor' => 'Sin proveedor',
                    // Campos combinados para mejor presentación
                    'codigo_completo' => ($producto->codigo ?? '') . ($producto->numero_parte ? ' | ' . $producto->numero_parte : ''),
                    'stock_status' => ($producto->stock_actual ?? 0) < ($producto->stock_minimo ?? 0) ? 'Bajo' : 'Normal',
                    'texto_busqueda' => ($producto->codigo ?? '') . ' - ' . ($producto->numero_parte ?? '') . ' - ' . ($producto->descripcion ?? '') . ' - ' . ($producto->modelo ?? '')
                ];
            });

            \Log::info("Búsqueda de productos realizada", [
                'query' => $q, 
                'encontrados' => $productos->count(),
                'productos' => $productos->take(3)->toArray()
            ]);

            return response()->json($productos);
            
        } catch (\Exception $e) {
            \Log::error('Error en búsqueda de productos: ' . $e->getMessage(), [
                'query' => $q,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([]);
        }
    }

    // Registrar venta completa
    public function guardarVenta(Request $r)
    {
        \Log::info('Datos recibidos en guardarVenta:', $r->all());
        
            $data = $r->validate([
            'id_cliente' => 'required|integer',
            'tipo_comprobante' => 'required', // Puede ser ID o string para compatibilidad
            'moneda' => 'nullable|string',
            'serie' => 'required|string',
            'incluir_igv' => 'nullable|boolean', // Campo opcional para IGV
                'tipo_cambio' => 'nullable|numeric|min:0.0001', // TC opcional desde el frontend
            'total' => 'nullable|numeric|min:0', // Permitir total enviado por frontend
            // 'numero' se auto-genera, no requerido en el request
            'detalle' => 'required|array|min:1',
            'detalle.*.id_producto' => 'required|integer',
            'detalle.*.cantidad' => 'required|numeric|min:0.01',
            'detalle.*.precio_unitario' => 'required|numeric|min:0',
                // Moneda del precio unitario enviado por el frontend: 'USD' o 'PEN'
                'detalle.*.moneda_precio' => 'nullable|string|in:USD,PEN',
            'detalle.*.descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $igv_rate = 0.18;
            $subtotal = 0;

            // Determinar moneda según selección del frontend
            $monedaSeleccionada = strtoupper($data['moneda'] ?? 'USD');
            $id_moneda = ($monedaSeleccionada === 'PEN') ? 1 : 2; // 1=PEN, 2=USD
            // Usar TC enviado por el frontend si llega y es válido; si no, obtenerlo
            $tipoCambio = null;
            if (isset($data['tipo_cambio']) && is_numeric($data['tipo_cambio']) && (float)$data['tipo_cambio'] > 0) {
                $tipoCambio = (float) $data['tipo_cambio'];
                \Log::info('[TC] Usando tipo de cambio provisto por frontend', ['tc' => $tipoCambio]);
            } else {
                $tipoCambio = $this->obtenerTipoCambio();
                \Log::info('[TC] Usando tipo de cambio del backend', ['tc' => $tipoCambio]);
            }

            foreach ($data['detalle'] as $d) {
                // Convertir solo si la moneda del precio del producto difiere de la moneda del comprobante
                $precioUnit = $d['precio_unitario'];
                $monedaPrecio = strtoupper($d['moneda_precio'] ?? 'PEN'); // por defecto asumimos PEN del catálogo
                if ($id_moneda === 2) { // comprobante en USD
                    if ($monedaPrecio === 'PEN' && $tipoCambio && $tipoCambio > 0) {
                        // PEN -> USD
                        $precioUnit = round($precioUnit / $tipoCambio, 6);
                    } // si ya está en USD, no convertir
                } else { // comprobante en PEN
                    if ($monedaPrecio === 'USD' && $tipoCambio && $tipoCambio > 0) {
                        // USD -> PEN
                        $precioUnit = round($precioUnit * $tipoCambio, 6);
                    } // si ya está en PEN, no convertir
                }

                $precio_final = $precioUnit * (1 - ($d['descuento_porcentaje'] ?? 0) / 100);
                $subtotal += $precio_final * $d['cantidad'];
            }

            // Calcular IGV según la selección del frontend y persistir correctamente
            // Default to including IGV for all ventas when not explicitly provided
            $incluir_igv = filter_var($data['incluir_igv'] ?? true, FILTER_VALIDATE_BOOLEAN);
            if ($incluir_igv) {
                $igv = round($subtotal * $igv_rate, 2);
                $total = round($subtotal + $igv, 2);
            } else {
                $igv = 0.00;
                $total = round($subtotal, 2);
            }

            // Moneda: usar selección del frontend (PEN o USD)
            
            // Determinar ID del tipo de comprobante
            if (is_numeric($data['tipo_comprobante'])) {
                // Si ya es un ID, usarlo directamente
                $id_tipo_comprobante = (int) $data['tipo_comprobante'];
            } else {
                // Si es un string, mapear (compatibilidad hacia atrás)
                $tipoComprobanteMap = [
                    'Cotización' => 8,
                    'Factura' => 1,
                    'Boleta de Venta' => 2,
                    'Nota de Crédito' => 3,
                    'Nota de Débito' => 4,
                    'Guía de Remisión' => 5,
                    'Ticket de Máquina Registradora' => 6,
                    'Recibo por Honorarios' => 7
                ];
                $id_tipo_comprobante = $tipoComprobanteMap[$data['tipo_comprobante']] ?? 1;
            }

            // Obtener el último número para esta serie y tipo de comprobante
            $ultimo_numero_venta = Venta::where('serie', $data['serie'])
                ->where('id_tipo_comprobante', $id_tipo_comprobante)
                ->orderBy('numero', 'desc')
                ->first();
            
            // Debugging: Log the last number retrieved for the comprobante
            \Log::info('Último registro obtenido para comprobante:', [
                'serie' => $data['serie'], 
                'id_tipo_comprobante' => $id_tipo_comprobante, 
                'ultimo_numero' => $ultimo_numero_venta ? $ultimo_numero_venta->numero : 'null'
            ]);

            // Extraer solo la parte numérica del último número
            $ultimo_correlativo = 0;
            if ($ultimo_numero_venta && $ultimo_numero_venta->numero) {
                $numero_str = $ultimo_numero_venta->numero;
                // Extraer solo los dígitos después del último guión
                if (strpos($numero_str, '-') !== false) {
                    $partes = explode('-', $numero_str);
                    $ultimo_correlativo = intval(end($partes));
                } else {
                    // Si no tiene guión, intentar extraer solo números
                    preg_match('/\d+$/', $numero_str, $matches);
                    $ultimo_correlativo = isset($matches[0]) ? intval($matches[0]) : 0;
                }
            }

            \Log::info('Correlativo procesado:', ['ultimo_correlativo' => $ultimo_correlativo]);

            // Incrementar el correlativo
            $nuevo_correlativo = $ultimo_correlativo + 1;
            
            // Formatear número completo: SERIE-CORRELATIVO
            $numero_formateado = $data['serie'] . '-' . str_pad($nuevo_correlativo, 8, '0', STR_PAD_LEFT);
            
            \Log::info('Nuevo número generado:', ['numero_formateado' => $numero_formateado]);

            // Determinar el estado inicial según el tipo de comprobante
            // Cotizaciones (ID 8) empiezan en ENVIADO, los demás en PENDIENTE
            $estadoInicial = ($id_tipo_comprobante == 8) ? 'ENVIADO' : 'PENDIENTE';

            $venta = Venta::create([
                'id_cliente' => $data['id_cliente'],
                // Vendedor: si no hay sesión, usar null (o un usuario sistema)
                'id_vendedor' => optional(auth()->user())->id_usuario ?? null,
                'id_moneda' => $id_moneda,
                'id_tipo_comprobante' => $id_tipo_comprobante,
                'serie' => $data['serie'],
                'numero' => $numero_formateado, // Usar formato con prefijo
                'fecha' => now(),
                'subtotal' => $subtotal,
                'igv' => $igv,
                'total' => $total,
                'saldo' => $total,
                'xml_estado' => $estadoInicial
            ]);

            \Log::info('Venta creada:', ['id_venta' => $venta->id_venta, 'estado' => $estadoInicial]);

            // Buscar tipo de comprobante real en la base de datos (por codigo_sunat o descripcion)
            $tipoComprobanteDB = \App\Models\TipoComprobante::where(function($q) use ($data, $id_tipo_comprobante) {
                $q->where('id_tipo_comprobante', $id_tipo_comprobante)
                  ->orWhere('codigo_sunat', $data['tipo_comprobante'])
                  ->orWhereRaw('LOWER(descripcion) = ?', [strtolower($data['tipo_comprobante'])]);
            })->first();

            // Definir comprobantes que SÍ descuentan stock
            // Solo descuentan stock los comprobantes con codigo_sunat '01', '03', '12' (Factura, Boleta, Ticket)
            // Las cotizaciones (CT) NUNCA descuentan stock
            $descuentaStock = false;
            if ($tipoComprobanteDB) {
                $codigo = strtoupper($tipoComprobanteDB->codigo_sunat ?? '');
                
                // Verificación explícita: Las cotizaciones NUNCA descuentan stock
                if ($codigo === 'CT') {
                    $descuentaStock = false;
                    \Log::info('[CONTROL STOCK] COTIZACIÓN DETECTADA - NO se descontará stock', [
                        'codigo_sunat' => $codigo,
                        'id_tipo_comprobante' => $id_tipo_comprobante
                    ]);
                } 
                // Solo estos comprobantes descuentan stock
                elseif (in_array($codigo, ['01', '03', '12'])) {
                    $descuentaStock = true;
                    \Log::info('[CONTROL STOCK] Comprobante de venta detectado - SÍ se descontará stock', [
                        'codigo_sunat' => $codigo,
                        'id_tipo_comprobante' => $id_tipo_comprobante
                    ]);
                } else {
                    \Log::info('[CONTROL STOCK] Otro tipo de comprobante - NO se descontará stock', [
                        'codigo_sunat' => $codigo,
                        'id_tipo_comprobante' => $id_tipo_comprobante
                    ]);
                }
            }

            foreach ($data['detalle'] as $d) {
                // Conversión por línea basada en la moneda del precio del producto
                \Log::info('[VENTA] Línea recibida', [
                    'producto_id' => $d['id_producto'] ?? null,
                    'cantidad' => $d['cantidad'] ?? null,
                    'precio_unitario_enviado' => $d['precio_unitario'] ?? null,
                    'moneda_precio' => $d['moneda_precio'] ?? null,
                    'moneda_comprobante' => ($id_moneda === 2 ? 'USD' : 'PEN'),
                    'tipo_cambio' => $tipoCambio
                ]);
                $precioUnit = $d['precio_unitario'];
                $monedaPrecio = strtoupper($d['moneda_precio'] ?? 'PEN');
                if ($id_moneda === 2) {
                    if ($monedaPrecio === 'PEN' && isset($tipoCambio) && $tipoCambio > 0) {
                        $precioUnit = round($precioUnit / $tipoCambio, 6);
                        \Log::info('[VENTA] Conversión PEN->USD', ['precio_convertido' => $precioUnit]);
                    }
                } else {
                    if ($monedaPrecio === 'USD' && isset($tipoCambio) && $tipoCambio > 0) {
                        $precioUnit = round($precioUnit * $tipoCambio, 6);
                        \Log::info('[VENTA] Conversión USD->PEN', ['precio_convertido' => $precioUnit]);
                    }
                }
                $precio_final = $precioUnit * (1 - ($d['descuento_porcentaje'] ?? 0) / 100);
                $subtotal_linea = $precio_final * $d['cantidad'];
                // No agregar IGV por línea; respetar total enviado y evitar incrementos
                $igv_linea = 0;
                $total_linea = $subtotal_linea;

                \Log::info('[VENTA] Línea calculada', [
                    'precio_final' => $precio_final,
                    'subtotal_linea' => $subtotal_linea,
                    'igv_linea' => $igv_linea,
                    'total_linea' => $total_linea
                ]);

                DetalleVenta::create([
                    'id_venta' => $venta->id_venta,
                    'id_producto' => $d['id_producto'],
                    'cantidad' => $d['cantidad'],
                    'precio_unitario' => $precioUnit,
                    'descuento_porcentaje' => $d['descuento_porcentaje'] ?? 0,
                    'precio_final' => $precio_final,
                    'subtotal' => $subtotal_linea,
                    'igv' => $igv_linea,
                    'total' => $total_linea,
                ]);

                \Log::info('[DEBUG STOCK] tipo_comprobante:', [
                    'id_tipo_comprobante' => $id_tipo_comprobante,
                    'tipo_comprobante_request' => $data['tipo_comprobante'],
                    'tipoComprobanteDB' => $tipoComprobanteDB,
                    'descuentaStock' => $descuentaStock
                ]);
                if ($descuentaStock) {
                    \Log::info('[DEBUG STOCK] Descontando stock', [
                        'id_producto' => $d['id_producto'],
                        'cantidad' => $d['cantidad']
                    ]);
                    Producto::where('id_producto', $d['id_producto'])
                        ->decrement('stock_actual', $d['cantidad']);
                } else {
                    \Log::info('[DEBUG STOCK] NO se descuenta stock porque no es comprobante de venta');
                }
            }

            // Crear el comprobante electrónico con el mismo número
            $comp = ComprobanteElectronico::create([
                'id_venta' => $venta->id_venta,
                'id_tipo_comprobante' => $id_tipo_comprobante,
                'serie' => $data['serie'],
                'numero' => $nuevo_correlativo, // Solo el número correlativo (entero)
                'fecha_emision' => now(),
                'monto_subtotal' => $subtotal,
                'monto_igv' => $igv,
                'monto_total' => $total,
                'moneda_id' => $id_moneda,
                'estado' => 'PENDIENTE',
            ]);

            DB::commit();

            // Obtener el stock actualizado de los productos involucrados
            $stocks_actualizados = [];
            foreach ($data['detalle'] as $d) {
                $producto = Producto::find($d['id_producto']);
                if ($producto) {
                    $stocks_actualizados[] = [
                        'id_producto' => $producto->id_producto,
                        'descripcion' => $producto->descripcion,
                        'stock_actual' => $producto->stock_actual
                    ];
                }
            }

            return response()->json([
                'ok' => true, 
                'id_venta' => $venta->id_venta, 
                'total' => $total,
                'numero_comprobante' => $numero_formateado,
                'serie' => $data['serie'],
                // Exponer el tipo de cambio utilizado para mayor claridad en el frontend
                'tipo_cambio' => $tipoCambio,
                'stocks_actualizados' => $stocks_actualizados
                ,
                'moneda' => [
                    'simbolo' => $id_moneda === 2 ? '$' : 'S/',
                    'codigo_iso' => $id_moneda === 2 ? 'USD' : 'PEN',
                    'nombre' => $id_moneda === 2 ? 'Dólar Americano' : 'Sol Peruano'
                ]
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    // Mostrar una venta específica
    public function show($id)
    {
        $venta = Venta::with(['cliente', 'vendedor', 'detalleVentas.producto', 'comprobanteElectronico'])
                     ->findOrFail($id);
        
        // Obtener tipo de cambio actual
        $tipoCambio = $this->obtenerTipoCambio();
        
        return view('ventas.show', compact('venta', 'tipoCambio'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $venta = Venta::with(['cliente', 'vendedor', 'detalleVentas.producto', 'moneda'])->findOrFail($id);
        
        // Obtener todos los clientes (sin filtro de activo por si no existe el campo)
        try {
            $clientes = Cliente::where('activo', 1)->get();
        } catch (\Exception $e) {
            $clientes = Cliente::all();
        }
        
        // Obtener todos los productos (sin filtro de activo por si no existe el campo)
        try {
            $productos = Producto::where('activo', 1)->get();
        } catch (\Exception $e) {
            $productos = Producto::all();
        }
        
        // Obtener tipo de cambio actual para conversiones en la UI de edición
        $tipoCambio = $this->obtenerTipoCambio();
        return view('ventas.edit', compact('venta', 'clientes', 'productos', 'tipoCambio'));
    }

    // Actualizar venta
    public function update(Request $request, $id)
    {
        \Log::info('Iniciando actualización de venta', ['id' => $id, 'datos' => $request->all()]);
        
        $venta = Venta::with(['detalleVentas', 'comprobanteElectronico'])->findOrFail($id);
        
        // Permitir edición si:
        // - La venta está en estado PENDIENTE, o
        // - Es una cotización y NO está ANULADO
        $esCotizacion = ($venta->id_tipo_comprobante == 8 ||
            (isset($venta->tipoComprobante) &&
             (stripos($venta->tipoComprobante->descripcion, 'cotiz') !== false ||
              stripos($venta->tipoComprobante->codigo_sunat ?? '', 'CT') !== false)) ||
            stripos($venta->serie, 'COT') !== false);

        $editable = ($venta->xml_estado === 'PENDIENTE') || ($esCotizacion && $venta->xml_estado !== 'ANULADO');

        if (!$editable) {
            \Log::warning('Intento de editar venta no permitida por estado', ['id' => $id, 'estado' => $venta->xml_estado, 'esCotizacion' => $esCotizacion]);
            return redirect()->route('ventas.show', $id)
                ->with('error', 'Solo se pueden editar ventas PENDIENTES o cotizaciones en cualquier estado excepto ANULADO');
        }

        try {
            $data = $request->validate([
                'fecha' => 'required|date',
                'hora' => 'required',
                'id_cliente' => 'required|exists:clientes,id_cliente',
                'serie' => 'required|string|max:10',
                'numero' => 'required|string|max:20',
                'subtotal' => 'required|numeric|min:0',
                'igv' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'detalle' => 'required|array|min:1',
                'detalle.*.id_producto' => 'required|exists:productos,id_producto',
                'detalle.*.cantidad' => 'required|numeric|min:0.01',
                'detalle.*.precio_unitario' => 'required|numeric|min:0',
                'detalle.*.descuento_porcentaje' => 'nullable|numeric|min:0|max:100'
            ]);
            
            \Log::info('Validación exitosa', ['data' => $data]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Error de validación', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Crear fecha completa
            $fecha_completa = $data['fecha'] . ' ' . $data['hora'];
            
            // Actualizar solo los campos modificados de la venta
            $campos_modificados = [];
            
            if ($venta->fecha != $fecha_completa) {
                $campos_modificados['fecha'] = $fecha_completa;
            }
            
            if ($venta->id_cliente != $data['id_cliente']) {
                $campos_modificados['id_cliente'] = $data['id_cliente'];
            }
            
            if ($venta->serie != $data['serie']) {
                $campos_modificados['serie'] = $data['serie'];
            }
            
            if ($venta->numero != $data['numero']) {
                $campos_modificados['numero'] = $data['numero'];
            }
            
            if ($venta->subtotal != $data['subtotal']) {
                $campos_modificados['subtotal'] = $data['subtotal'];
            }
            
            if ($venta->igv != $data['igv']) {
                $campos_modificados['igv'] = $data['igv'];
            }
            
            if ($venta->total != $data['total']) {
                $campos_modificados['total'] = $data['total'];
            }
            
            // Solo actualizar si hay cambios
            if (!empty($campos_modificados)) {
                $venta->update($campos_modificados);
                \Log::info('Venta actualizada', ['id_venta' => $venta->id_venta, 'campos' => $campos_modificados]);
            }

            // Actualizar detalles de venta
            // Primero eliminar detalles existentes
            $venta->detalleVentas()->delete();
            
            // Crear nuevos detalles
            foreach ($data['detalle'] as $detalle) {
                $precio_final = $detalle['precio_unitario'] * (1 - ($detalle['descuento_porcentaje'] ?? 0) / 100);
                $subtotal_linea = $precio_final * $detalle['cantidad'];
                $igv_linea = $subtotal_linea * 0.18;
                $total_linea = $subtotal_linea + $igv_linea;

                DetalleVenta::create([
                    'id_venta' => $venta->id_venta,
                    'id_producto' => $detalle['id_producto'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'descuento_porcentaje' => $detalle['descuento_porcentaje'] ?? 0,
                    'precio_final' => $precio_final,
                    'subtotal' => $subtotal_linea,
                    'igv' => $igv_linea,
                    'total' => $total_linea,
                ]);
            }

            // Actualizar comprobante electrónico si existe
            if ($venta->comprobanteElectronico) {
                $comp_modificados = [];
                
                if ($venta->comprobanteElectronico->serie != $data['serie']) {
                    $comp_modificados['serie'] = $data['serie'];
                }
                
                if ($venta->comprobanteElectronico->numero != $data['numero']) {
                    $comp_modificados['numero'] = $data['numero'];
                }
                
                if ($venta->comprobanteElectronico->monto_subtotal != $data['subtotal']) {
                    $comp_modificados['monto_subtotal'] = $data['subtotal'];
                }
                
                if ($venta->comprobanteElectronico->monto_igv != $data['igv']) {
                    $comp_modificados['monto_igv'] = $data['igv'];
                }
                
                if ($venta->comprobanteElectronico->monto_total != $data['total']) {
                    $comp_modificados['monto_total'] = $data['total'];
                }
                
                if (!empty($comp_modificados)) {
                    $venta->comprobanteElectronico->update($comp_modificados);
                }
            }

            DB::commit();
            \Log::info('Venta actualizada exitosamente', ['id_venta' => $venta->id_venta]);
            return redirect()->route('ventas.show', $venta->id_venta)
                ->with('success', 'Venta actualizada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error actualizando venta', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Error al actualizar la venta: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Eliminar venta
    public function destroy($id)
    {
        $venta = Venta::findOrFail($id);
        $venta->delete();
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada exitosamente');
    }

    // Método store para el formulario tradicional (si es necesario)
    public function store(Request $request)
    {
        // Implementación de registro tradicional con generación automática de número de comprobante
        $data = $request->validate([
            'id_cliente' => 'required|integer',
            'tipo_comprobante' => 'required',
            'moneda' => 'required|string',
            'serie' => 'nullable|string', // Permitir que sea nulo para generar automáticamente
            'detalle' => 'required|array|min:1',
            'detalle.*.id_producto' => 'required|integer',
            'detalle.*.cantidad' => 'required|numeric|min:0.01',
            'detalle.*.precio_unitario' => 'required|numeric|min:0',
            'detalle.*.descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
            'total' => 'nullable|numeric|min:0', // Permitir total enviado por frontend
        ]);

        DB::beginTransaction();
        try {
            $igv_rate = 0.18;
            $subtotal = 0;
            foreach ($data['detalle'] as $d) {
                $precio_final = $d['precio_unitario'] * (1 - ($d['descuento_porcentaje'] ?? 0) / 100);
                $subtotal += $precio_final * $d['cantidad'];
            }
            // Confiar en el total del frontend si viene; no agregar IGV ni extras
            if ($request->has('total')) {
                $total = round(floatval($request->input('total')), 2);
                $igv = 0;
                $subtotal = $total;
            } else {
                // Precios finales recibidos del formulario se consideran con IGV incluido
                $igv = 0;
                $total = round($subtotal, 2);
            }
            $id_moneda = $data['moneda'] === 'PEN' ? 1 : 2;
            if (is_numeric($data['tipo_comprobante'])) {
                $id_tipo_comprobante = (int) $data['tipo_comprobante'];
            } else {
                $tipoComprobanteMap = [
                    'Cotizacion' => 8,
                    'Factura' => 1,
                    'Boleta' => 2,
                    'Nota de Crédito' => 3,
                    'Ticket' => 6
                ];
                $id_tipo_comprobante = $tipoComprobanteMap[$data['tipo_comprobante']] ?? 1;
            }

            // Generar automáticamente el número de serie si es un ticket
            if ($id_tipo_comprobante === 6) { // Ticket
                $data['serie'] = 'TK01';
            }

            $ultimo_numero_venta = Venta::where('serie', $data['serie'])
                ->where('id_tipo_comprobante', $id_tipo_comprobante)
                ->max('numero');

            if ($ultimo_numero_venta && is_string($ultimo_numero_venta)) {
                if (strpos($ultimo_numero_venta, '-') !== false) {
                    $ultimo_numero_venta = explode('-', $ultimo_numero_venta)[1];
                }
                $ultimo_numero_venta = intval($ultimo_numero_venta);
            } else {
                $ultimo_numero_venta = intval($ultimo_numero_venta ?: 0);
            }

            $nuevo_numero = $ultimo_numero_venta + 1;
            $prefijos = [
                'Cotizacion' => 'COT-',
                'Factura' => 'F001-',
                'Boleta' => 'B001-',
                'Nota de Crédito' => 'NC01-',
                'Ticket de máquina registradora' => 'TK01-'
            ];
            $prefijo = $prefijos[$data['tipo_comprobante']] ?? '';
            $numero_formateado = $prefijo . str_pad($nuevo_numero, 8, '0', STR_PAD_LEFT);

            $venta = Venta::create([
                'id_cliente' => $data['id_cliente'],
                'id_vendedor' => auth()->user()->id_usuario ?? 1, // fallback a 1 si no hay usuario
                'id_moneda' => $id_moneda,
                'id_tipo_comprobante' => $id_tipo_comprobante,
                'serie' => $data['serie'],
                'numero' => $numero_formateado,
                'fecha' => now(),
                'subtotal' => $subtotal,
                'igv' => $igv,
                'total' => $total,
                'saldo' => $total,
                'xml_estado' => 'PENDIENTE'
            ]);

            // Determinar si debe descontar stock
            $tipoComprobanteDB = \App\Models\TipoComprobante::where('id_tipo_comprobante', $id_tipo_comprobante)->first();
            $descuentaStock = false;
            if ($tipoComprobanteDB) {
                $codigo = strtoupper($tipoComprobanteDB->codigo_sunat ?? '');
                
                // Verificación explícita: Las cotizaciones (CT) NUNCA descuentan stock
                if ($codigo === 'CT') {
                    $descuentaStock = false;
                } 
                // Solo descuentan stock: Factura (01), Boleta (03), Ticket (12)
                elseif (in_array($codigo, ['01', '03', '12'])) {
                    $descuentaStock = true;
                }
                
                \Log::info('[CONTROL STOCK] Verificación de tipo de comprobante', [
                    'tipo_comprobante_id' => $id_tipo_comprobante,
                    'codigo_sunat' => $codigo,
                    'descripcion' => $tipoComprobanteDB->descripcion,
                    'descuenta_stock' => $descuentaStock ? 'SÍ' : 'NO'
                ]);
            }

            foreach ($data['detalle'] as $d) {
                $precio_final = $d['precio_unitario'] * (1 - ($d['descuento_porcentaje'] ?? 0) / 100);
                $subtotal_linea = $precio_final * $d['cantidad'];
                // No agregar IGV adicional en líneas (precios finales ya incluyen impuestos)
                $igv_linea = 0;
                $total_linea = $subtotal_linea;
                \App\Models\DetalleVenta::create([
                    'id_venta' => $venta->id_venta,
                    'id_producto' => $d['id_producto'],
                    'cantidad' => $d['cantidad'],
                    'precio_unitario' => $d['precio_unitario'],
                    'descuento_porcentaje' => $d['descuento_porcentaje'] ?? 0,
                    'precio_final' => $precio_final,
                    'subtotal' => $subtotal_linea,
                    'igv' => $igv_linea,
                    'total' => $total_linea,
                ]);

                // Descontar stock solo para comprobantes de venta (Factura, Boleta, Ticket)
                if ($descuentaStock) {
                    $productoAntes = Producto::find($d['id_producto']);
                    $stockAntes = $productoAntes ? $productoAntes->stock_actual : 0;
                    
                    Producto::where('id_producto', $d['id_producto'])
                        ->decrement('stock_actual', $d['cantidad']);
                    
                    $productoDespues = Producto::find($d['id_producto']);
                    $stockDespues = $productoDespues ? $productoDespues->stock_actual : 0;
                    
                    \Log::info('[CONTROL STOCK] Stock descontado', [
                        'producto_id' => $d['id_producto'],
                        'cantidad_descontada' => $d['cantidad'],
                        'stock_antes' => $stockAntes,
                        'stock_despues' => $stockDespues
                    ]);
                } else {
                    \Log::info('[CONTROL STOCK] NO se descuenta stock porque no es comprobante de venta');
                }
            }

            \App\Models\ComprobanteElectronico::create([
                'id_venta' => $venta->id_venta,
                'id_tipo_comprobante' => $id_tipo_comprobante,
                'serie' => $data['serie'],
                'numero' => $nuevo_correlativo, // Solo el número correlativo (entero)
                'fecha_emision' => now(),
                'monto_subtotal' => $subtotal,
                'monto_igv' => $igv,
                'monto_total' => $total,
                'moneda_id' => $id_moneda,
                'estado' => 'PENDIENTE',
            ]);

            DB::commit();
            if ($r->expectsJson() || $r->is('api/*')) {
                return response()->json([
                    'ok' => true,
                    'id_venta' => $venta->id_venta,
                    'serie' => $data['serie'],
                    'numero_comprobante' => $nuevo_correlativo,
                    'numero' => $numero_formateado,
                    'total' => round($total, 2),
                    'subtotal' => round($subtotal, 2),
                    'igv' => round($igv, 2),
                    'moneda' => [
                        'simbolo' => $moneda->simbolo ?? ($id_moneda == 2 ? '$' : 'S/'),
                        'codigo_iso' => strtoupper($moneda->codigo_iso ?? ($id_moneda == 2 ? 'USD' : 'PEN')),
                        'nombre' => $moneda->nombre ?? ($id_moneda == 2 ? 'Dólar Americano' : 'Sol Peruano'),
                    ],
                ], 201);
            }
            return redirect()->route('ventas.show', $venta->id_venta)
                ->with('success', 'Venta registrada correctamente. Número de comprobante: ' . $numero_formateado);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar la venta: ' . $e->getMessage())->withInput();
        }
    }

    // Generar PDF del comprobante electrónico
    public function generarPDF($id)
    {
        try {
            // Cargar venta con todas sus relaciones
            $venta = Venta::with(['cliente', 'detalleVentas.producto', 'comprobanteElectronico', 'vendedor', 'moneda'])
                         ->findOrFail($id);

            // Determinar el tipo de comprobante basado en el ID
            $tiposComprobante = [
                1 => 'Factura',
                2 => 'Boleta',
                3 => 'Nota de Crédito',
                4 => 'Cotizacion'
            ];
            
            $tipoComprobante = $tiposComprobante[$venta->id_tipo_comprobante] ?? 'Comprobante';
            
            // Obtener y validar tipo de cambio (prioriza el ingresado en la venta)
            $tipoCambioRaw = $venta->tipo_cambio ?? null;
            $tipoCambio = $this->validarTipoCambio($tipoCambioRaw) ?? $this->obtenerTipoCambio();
            
            // Determinar moneda de la venta
            $codigoIso = strtoupper(optional($venta->moneda)->codigo_iso ?? 'PEN');
            $simbolo = optional($venta->moneda)->simbolo ?? ($codigoIso === 'USD' ? '$' : 'S/');
            $descripcionMoneda = optional($venta->moneda)->nombre ?? ($codigoIso === 'USD' ? 'Dólares Americanos' : 'Soles Peruanos');

            // Preparar datos para el PDF
            $data = [
                'venta' => $venta,
                'cliente' => $venta->cliente,
                'detalles' => $venta->detalleVentas,
                'fecha' => $venta->fecha ?? now(),
                'tipoComprobante' => (object) [
                    'descripcion' => strtoupper($tipoComprobante),
                    'tipo' => $tipoComprobante
                ],
                'moneda' => (object) [
                    'simbolo' => $simbolo,
                    'codigo_iso' => $codigoIso,
                    'descripcion' => $descripcionMoneda
                ],
                'tipoCambio' => $tipoCambio,
                'descuentoTotal' => $venta->detalleVentas->sum('descuento_monto') ?? 0,
                'totalEnLetras' => $this->numeroALetrasConMoneda($venta->total ?? 0, $codigoIso)
            ];

            // Seleccionar la vista según el tipo de comprobante
            $vistaMap = [
                'Cotizacion' => 'comprobantes.cotizacion',
                'Factura' => 'comprobantes.factura',
                'Boleta' => 'comprobantes.boleta',
                'Nota de Crédito' => 'comprobantes.nota_credito'
            ];
            
            $vista = $vistaMap[$tipoComprobante] ?? 'comprobantes.pdf';
            
            // Generar PDF usando la vista específica del tipo de comprobante
            $pdf = PDF::loadView($vista, $data);
            $pdf->setPaper('A4', 'portrait');
            
            // Nombre del archivo con prefijo según tipo
            $prefijos = [
                'Cotizacion' => 'COT',
                'Factura' => 'FAC',
                'Boleta' => 'BOL',
                'Nota de Crédito' => 'NC'
            ];
            
            $prefijo = $prefijos[$tipoComprobante] ?? 'COMP';
            $numero = str_replace(['-', $venta->serie], '', $venta->numero);
            $fileName = $prefijo . '_' . $venta->serie . '_' . $numero . '.pdf';
            
            return $pdf->download($fileName);
            
        } catch (\Exception $e) {
            // Retornar error detallado para debug
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    // Función auxiliar para convertir números a letras
    private function numeroALetras($numero)
    {
        $numero = floatval($numero);
        $enteros = floor($numero);
        $decimales = round(($numero - $enteros) * 100);
        
        // Implementación básica
        $unidades = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $decenas = ['', '', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        
        if ($enteros == 0) {
            return 'CERO CON ' . sprintf('%02d', $decimales) . '/100 SOLES';
        }
        
        if ($enteros <= 9) {
            return $unidades[$enteros] . ' CON ' . sprintf('%02d', $decimales) . '/100 SOLES';
        }
        
        return strtoupper(number_format($enteros)) . ' CON ' . sprintf('%02d', $decimales) . '/100 SOLES';
    }

    // Variante que agrega la moneda (SOLES / DÓLARES) según código ISO
    private function numeroALetrasConMoneda($numero, $codigoIso = 'PEN')
    {
        $base = $this->numeroALetras($numero);
        $codigoIso = strtoupper($codigoIso ?? 'PEN');
        if ($codigoIso === 'USD') {
            // Reemplazar sufijo por DÓLARES si la original dice SOLES
            $base = preg_replace('/SOLES$/', 'DÓLARES', $base);
        }
        return $base;
    }

    // Valida y normaliza el tipo de cambio manual ingresado
    private function validarTipoCambio($tipoCambio)
    {
        if ($tipoCambio === null) return null;
        if (!is_numeric($tipoCambio)) return null;
        $tc = (float) $tipoCambio;
        // Debe ser positivo y en un rango razonable para USD/PEN
        if ($tc <= 0) return null;
        if ($tc < 2.0) return null;   // evita valores improbables
        if ($tc > 10.0) return null;  // evita valores fuera de rango
        // Redondear a 4 decimales para consistencia
        return round($tc, 4);
    }

    // Confirmar cancelación de venta
    /**
     * Convertir cotización a factura o boleta
     */
    public function convertirCotizacion(Request $request, $id)
    {
        try {
            $venta = Venta::findOrFail($id);
            
            // Verificar que sea una cotización
            if ($venta->id_tipo_comprobante != 8) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Solo se pueden convertir cotizaciones'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Solo se pueden convertir cotizaciones');
            }
            
            // Permitir conversión de cotizaciones en estado PENDIENTE o ENVIADO
            if (!in_array($venta->xml_estado, ['PENDIENTE', 'ENVIADO'])) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Solo se pueden convertir cotizaciones pendientes o enviadas'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Solo se pueden convertir cotizaciones pendientes o enviadas');
            }
            
            $tipoDestino = $request->input('tipo_destino');
            
            if (!in_array($tipoDestino, ['Factura', 'Boleta', 'Ticket'])) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Tipo de destino inválido'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Tipo de destino inválido');
            }

            DB::beginTransaction();

            // Mapear tipo destino
            $tipoComprobanteMap = [
                'Factura' => 1,
                'Boleta' => 2,
                'Ticket' => 6
            ];
            $nuevoTipoId = $tipoComprobanteMap[$tipoDestino];

            // Configuración de series
            $seriesConfig = [
                'Factura' => ['serie' => 'F001', 'prefijo' => 'F001-'],
                'Boleta' => ['serie' => 'B001', 'prefijo' => 'B001-'],
                'Ticket' => ['serie' => 'TK01', 'prefijo' => 'TK01-']
            ];
            $nuevaSerie = $seriesConfig[$tipoDestino]['serie'];
            $prefijo = $seriesConfig[$tipoDestino]['prefijo'];

            // Obtener el siguiente número para el nuevo tipo
            $ultimoNumero = Venta::where('serie', $nuevaSerie)
                ->where('id_tipo_comprobante', $nuevoTipoId)
                ->max('numero');

            // Extraer solo el número si tiene formato
            if ($ultimoNumero && is_string($ultimoNumero)) {
                if (strpos($ultimoNumero, '-') !== false) {
                    $ultimoNumero = explode('-', $ultimoNumero)[1];
                }
                $ultimoNumero = intval($ultimoNumero);
            } else {
                $ultimoNumero = intval($ultimoNumero ?: 0);
            }

            $siguienteNumero = $ultimoNumero + 1;
            $nuevoNumeroFormateado = $prefijo . str_pad($siguienteNumero, 8, '0', STR_PAD_LEFT);

            // Actualizar la venta
            $venta->update([
                'id_tipo_comprobante' => $nuevoTipoId,
                'serie' => $nuevaSerie,
                'numero' => $nuevoNumeroFormateado,
                'xml_estado' => 'PENDIENTE' // Mantener como pendiente para poder procesarla
            ]);

            // Descontar stock manualmente porque los triggers solo se activan en INSERT/UPDATE/DELETE de detalle_ventas
            // Al convertir cotización solo actualizamos la tabla ventas, por eso debemos descontar aquí
            foreach ($venta->detalleVentas as $detalle) {
                $productoAntes = Producto::find($detalle->id_producto);
                $stockAntes = $productoAntes ? $productoAntes->stock_actual : 0;
                
                Producto::where('id_producto', $detalle->id_producto)
                    ->decrement('stock_actual', $detalle->cantidad);
                
                $productoDespues = Producto::find($detalle->id_producto);
                $stockDespues = $productoDespues ? $productoDespues->stock_actual : 0;
                
                \Log::info('[CONVERSIÓN] Stock descontado al convertir cotización', [
                    'producto_id' => $detalle->id_producto,
                    'cantidad' => $detalle->cantidad,
                    'stock_antes' => $stockAntes,
                    'stock_despues' => $stockDespues
                ]);
            }

            DB::commit();

            \Log::info("Cotización convertida", [
                'id_venta' => $id,
                'tipo_original' => 'Cotización',
                'tipo_destino' => $tipoDestino,
                'nueva_serie' => $nuevaSerie,
                'nuevo_numero' => $nuevoNumeroFormateado
            ]);

            // Si es una petición AJAX, devolver JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Cotización convertida exitosamente a {$tipoDestino}",
                    'nueva_serie' => $nuevaSerie,
                    'nuevo_numero' => $nuevoNumeroFormateado,
                    'tipo_destino' => $tipoDestino
                ]);
            }
            
            // Si es un formulario HTML normal, redirigir
            return redirect()->route('ventas.index')
                ->with('success', "Cotización convertida exitosamente a {$tipoDestino}: {$nuevoNumeroFormateado}");
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error("Error al convertir cotización: " . $e->getMessage());
            
            // Si es una petición AJAX, devolver JSON de error
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error interno del servidor: ' . $e->getMessage()
                ], 500);
            }
            
            // Si es un formulario HTML normal, redirigir con error
            return redirect()->back()->with('error', 'Error al convertir cotización: ' . $e->getMessage());
        }
    }

    /**
     * Obtener el siguiente número de comprobante para un tipo y serie específicos
     */
    public function siguienteNumero(Request $request)
    {
        $tipoId = $request->get('tipo_id');
        $tipo = $request->get('tipo'); // Para compatibilidad hacia atrás
        $serie = $request->get('serie');
        
        if (!$serie) {
            return response()->json(['error' => 'Serie es requerida'], 400);
        }
        
        // Determinar el ID del tipo de comprobante
        $idTipoComprobante = null;
        
        if ($tipoId) {
            // Si viene el tipo_id directamente, usarlo
            $idTipoComprobante = $tipoId;
        } elseif ($tipo) {
            // Mapear tipo de comprobante por descripción (compatibilidad hacia atrás)
            $tipoComprobanteMap = [
                'Cotización' => 8, // Ajustar según tu BD
                'Factura' => 1,
                'Boleta de Venta' => 2,
                'Nota de Crédito' => 3,
                'Nota de Débito' => 4,
                'Guía de Remisión' => 5,
                'Ticket de Máquina Registradora' => 6,
                'Recibo por Honorarios' => 7
            ];
            $idTipoComprobante = $tipoComprobanteMap[$tipo] ?? 1;
        } else {
            return response()->json(['error' => 'Tipo de comprobante es requerido'], 400);
        }
        
        // Obtener el último número para esta serie y tipo
        $ultimoNumero = Venta::where('serie', $serie)
            ->where('id_tipo_comprobante', $idTipoComprobante)
            ->max('numero');
        
        // Extraer solo el número si tiene formato (ej: "F001-00000123" -> 123)
        if ($ultimoNumero && is_string($ultimoNumero)) {
            // Si tiene guión, tomar la parte después del guión
            if (strpos($ultimoNumero, '-') !== false) {
                $ultimoNumero = explode('-', $ultimoNumero)[1];
            }
            $ultimoNumero = intval($ultimoNumero);
        } else {
            $ultimoNumero = intval($ultimoNumero ?: 0);
        }
        
        $siguienteNumero = $ultimoNumero + 1;
        
        return response()->json([
            'tipo' => $tipo,
            'serie' => $serie,
            'ultimo_numero' => $ultimoNumero,
            'siguiente_numero' => $siguienteNumero
        ]);
    }

    public function confirmCancel($id)
    {
        $venta = Venta::with(['cliente', 'detalleVentas.producto', 'vendedor'])
                     ->findOrFail($id);
        
        // Debug logging para verificar datos
        \Log::info('Datos de venta para cancelación', [
            'venta_id' => $id,
            'detalles_count' => $venta->detalleVentas->count(),
            'primer_detalle' => $venta->detalleVentas->first() ? [
                'cantidad' => $venta->detalleVentas->first()->cantidad,
                'producto_id' => $venta->detalleVentas->first()->producto ? $venta->detalleVentas->first()->producto->id_producto : null,
                'stock_actual' => $venta->detalleVentas->first()->producto ? $venta->detalleVentas->first()->producto->stock_actual : null
            ] : null
        ]);
        
        return view('ventas.confirm-cancel', compact('venta'));
    }

    // Cancelar venta
    public function cancel(Request $request, $id)
    {
        // Log para debug
        \Log::info('Iniciando cancelación de venta', ['id' => $id, 'request' => $request->all()]);
        
        $venta = Venta::findOrFail($id);
        
        // Verificar que la venta se pueda cancelar
        if ($venta->xml_estado === 'ANULADO') {
            return redirect()->back()
                           ->with('error', 'La venta ya está anulada');
        }
        
        try {
            DB::beginTransaction();
            
            // Obtener tipo de comprobante para determinar si debe revertir stock
            $tipoComprobanteDB = \App\Models\TipoComprobante::where('id_tipo_comprobante', $venta->id_tipo_comprobante)->first();
            $revertirStock = false;
            
            if ($tipoComprobanteDB) {
                $codigo = strtoupper($tipoComprobanteDB->codigo_sunat ?? '');
                
                // Verificación explícita: Las cotizaciones (CT) NUNCA revierten stock
                if ($codigo === 'CT') {
                    $revertirStock = false;
                    \Log::info('[CONTROL STOCK] COTIZACIÓN - NO se revertirá stock al anular', [
                        'codigo_sunat' => $codigo,
                        'id_venta' => $id
                    ]);
                }
                // Solo revertir stock para comprobantes de venta: Factura (01), Boleta (03), Ticket (12)
                elseif (in_array($codigo, ['01', '03', '12'])) {
                    $revertirStock = true;
                    \Log::info('[CONTROL STOCK] Comprobante de venta - SÍ se revertirá stock al anular', [
                        'codigo_sunat' => $codigo,
                        'id_venta' => $id
                    ]);
                }
            }
            
            // Revertir stock de productos solo si no es cotización
            if ($revertirStock) {
                foreach ($venta->detalleVentas as $detalle) {
                    $producto = Producto::find($detalle->id_producto);
                    if ($producto) {
                        $oldStock = $producto->stock_actual;
                        $producto->stock_actual += $detalle->cantidad;
                        $producto->save();
                        \Log::info('Stock revertido', [
                            'producto_id' => $producto->id_producto,
                            'stock_anterior' => $oldStock,
                            'cantidad_revertida' => $detalle->cantidad,
                            'stock_nuevo' => $producto->stock_actual
                        ]);
                    }
                }
            } else {
                \Log::info('NO se revierte stock porque es cotización u otro comprobante que no afecta inventario');
            }
            
            // Actualizar estado de la venta
            $venta->update([
                'xml_estado' => 'ANULADO',
                'fecha_anulacion' => now(),
                'motivo_anulacion' => $request->input('motivo', 'Anulación manual')
            ]);
            
            \Log::info('Venta anulada exitosamente', ['venta_id' => $id, 'stock_revertido' => $revertirStock]);
            
            DB::commit();
            
            $mensaje = $revertirStock 
                ? 'Venta anulada exitosamente. Stock revertido.' 
                : 'Cotización anulada exitosamente.';
            
            return redirect()->route('ventas.index')
                           ->with('success', $mensaje);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error al anular venta', ['error' => $e->getMessage(), 'venta_id' => $id]);
            return redirect()->back()
                           ->with('error', 'Error al cancelar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Convertir cotización a factura
     */
    public function convertirAFactura($id)
    {
        \Log::info('[CONVERSIÓN] Iniciando conversión a Factura', ['id_venta' => $id]);
        
        try {
            $venta = Venta::findOrFail($id);
            
            \Log::info('[CONVERSIÓN] Venta encontrada', [
                'id_venta' => $venta->id_venta,
                'tipo_comprobante' => $venta->id_tipo_comprobante,
                'estado' => $venta->xml_estado,
                'serie_numero' => $venta->serie . '-' . $venta->numero
            ]);
            
            // Verificar que sea una cotización (ID 8 según seeder)
            if ($venta->id_tipo_comprobante != 8) {
                \Log::warning('[CONVERSIÓN] No es cotización', ['tipo_comprobante' => $venta->id_tipo_comprobante]);
                return redirect()->back()->with('error', 'Solo se pueden convertir cotizaciones');
            }
            
            if ($venta->xml_estado === 'ANULADO') {
                \Log::warning('[CONVERSIÓN] Cotización anulada');
                return redirect()->back()->with('error', 'No se puede convertir una cotización anulada');
            }

            DB::beginTransaction();
            
            // Configuración para factura
            $nuevaSerie = 'F001';
            $nuevoTipoId = 1; // Factura
            
            // Obtener el siguiente número
            $ultimoNumero = Venta::where('serie', $nuevaSerie)
                ->where('id_tipo_comprobante', $nuevoTipoId)
                ->max('numero');
            
            if ($ultimoNumero && strpos($ultimoNumero, '-') !== false) {
                $ultimoNumero = explode('-', $ultimoNumero)[1];
            }
            $ultimoNumero = intval($ultimoNumero ?: 0);
            $siguienteNumero = $ultimoNumero + 1;
            $nuevoNumeroFormateado = 'F001-' . str_pad($siguienteNumero, 8, '0', STR_PAD_LEFT);
            
            \Log::info('[CONVERSIÓN] Nuevo número generado', ['numero' => $nuevoNumeroFormateado]);
            
            // Actualizar la venta
            $venta->update([
                'id_tipo_comprobante' => $nuevoTipoId,
                'serie' => $nuevaSerie,
                'numero' => $nuevoNumeroFormateado,
                'xml_estado' => 'PENDIENTE'
            ]);
            
            \Log::info('[CONVERSIÓN] Venta actualizada');
            
            // Descontar stock de los productos al convertir a factura
            foreach ($venta->detalleVentas as $detalle) {
                Producto::where('id_producto', $detalle->id_producto)
                    ->decrement('stock_actual', $detalle->cantidad);
                \Log::info('[CONVERSIÓN] Stock descontado', [
                    'producto_id' => $detalle->id_producto,
                    'cantidad' => $detalle->cantidad
                ]);
            }
            
            DB::commit();
            
            \Log::info('[CONVERSIÓN] ✅ Conversión completada exitosamente', [
                'nuevo_numero' => $nuevoNumeroFormateado
            ]);
            
            return redirect()->route('ventas.index')
                           ->with('success', 'Cotización convertida exitosamente a Factura: ' . $nuevoNumeroFormateado);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error("[CONVERSIÓN] ❌ Error al convertir: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Error al convertir cotización: ' . $e->getMessage());
        }
    }

    /**
     * Convertir cotización a boleta
     */
    public function convertirABoleta($id)
    {
        \Log::info('[CONVERSIÓN] Iniciando conversión a Boleta', ['id_venta' => $id]);
        
        try {
            $venta = Venta::findOrFail($id);
            
            \Log::info('[CONVERSIÓN] Venta encontrada', [
                'id_venta' => $venta->id_venta,
                'tipo_comprobante' => $venta->id_tipo_comprobante,
                'estado' => $venta->xml_estado
            ]);
            
            // Verificar que sea una cotización (ID 8 según seeder)
            if ($venta->id_tipo_comprobante != 8) {
                \Log::warning('[CONVERSIÓN] No es cotización', ['tipo_comprobante' => $venta->id_tipo_comprobante]);
                return redirect()->back()->with('error', 'Solo se pueden convertir cotizaciones');
            }
            
            if ($venta->xml_estado === 'ANULADO') {
                \Log::warning('[CONVERSIÓN] Cotización anulada');
                return redirect()->back()->with('error', 'No se puede convertir una cotización anulada');
            }

            DB::beginTransaction();
            
            // Configuración para boleta
            $nuevaSerie = 'B001';
            $nuevoTipoId = 2; // Boleta
            
            // Obtener el siguiente número
            $ultimoNumero = Venta::where('serie', $nuevaSerie)
                ->where('id_tipo_comprobante', $nuevoTipoId)
                ->max('numero');
            
            if ($ultimoNumero && strpos($ultimoNumero, '-') !== false) {
                $ultimoNumero = explode('-', $ultimoNumero)[1];
            }
            $ultimoNumero = intval($ultimoNumero ?: 0);
            $siguienteNumero = $ultimoNumero + 1;
            $nuevoNumeroFormateado = 'B001-' . str_pad($siguienteNumero, 8, '0', STR_PAD_LEFT);
            
            \Log::info('[CONVERSIÓN] Nuevo número generado', ['numero' => $nuevoNumeroFormateado]);
            
            // Actualizar la venta
            $venta->update([
                'id_tipo_comprobante' => $nuevoTipoId,
                'serie' => $nuevaSerie,
                'numero' => $nuevoNumeroFormateado,
                'xml_estado' => 'PENDIENTE'
            ]);
            
            \Log::info('[CONVERSIÓN] Venta actualizada');
            
            // Descontar stock de los productos al convertir a boleta
            foreach ($venta->detalleVentas as $detalle) {
                Producto::where('id_producto', $detalle->id_producto)
                    ->decrement('stock_actual', $detalle->cantidad);
                \Log::info('[CONVERSIÓN] Stock descontado', [
                    'producto_id' => $detalle->id_producto,
                    'cantidad' => $detalle->cantidad
                ]);
            }
            
            DB::commit();
            
            \Log::info('[CONVERSIÓN] ✅ Conversión completada exitosamente', [
                'nuevo_numero' => $nuevoNumeroFormateado
            ]);
            
            return redirect()->route('ventas.index')
                           ->with('success', 'Cotización convertida exitosamente a Boleta: ' . $nuevoNumeroFormateado);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error("Error al convertir cotización a boleta: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al convertir cotización: ' . $e->getMessage());
        }
    }

    /**
     * Obtener tipo de cambio actual via AJAX con información detallada
     */
    public function obtenerTipoCambioActual()
    {
        try {
            $tipoCambio = $this->obtenerTipoCambio();
            
            // Obtener información de cache para saber si es reciente

            $cacheInfo = \Cache::get('tipo_cambio_usd_pen_info', [
                'fuente' => 'Valor por defecto',
                'fecha_actualizacion' => now(),
                'cache_hit' => false
            ]);
            
            return response()->json([
                'success' => true,
                'tipo_cambio' => $tipoCambio,
                'fecha_actualizacion' => $cacheInfo['fecha_actualizacion']->format('d/m/Y H:i:s'),
                'fuente' => $cacheInfo['fuente'] ?? 'API Externa',
                'cache_hit' => $cacheInfo['cache_hit'] ?? false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener tipo de cambio',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Forzar actualización del tipo de cambio limpiando cache
     */
    public function actualizarTipoCambioForzado()
    {
        try {
            // Limpiar cache
            \Cache::forget('tipo_cambio_usd_pen');
            \Cache::forget('tipo_cambio_usd_pen_info');
            
            // Obtener nuevo tipo de cambio
            $tipoCambio = $this->obtenerTipoCambio();
            
            return response()->json([
                'success' => true,
                'tipo_cambio' => $tipoCambio,
                'fecha_actualizacion' => now()->format('d/m/Y H:i:s'),
                'mensaje' => 'Tipo de cambio actualizado forzosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al forzar actualización',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear cotización de prueba temporal (solo para testing)
     */
    public function crearCotizacionPrueba()
    {
        try {
            // Buscar una venta existente para convertir temporalmente
            $venta = Venta::first();
            
            if (!$venta) {
                return response()->json([
                    'success' => false,
                    'error' => 'No hay ventas en la base de datos para convertir'
                ]);
            }

            // Convertir temporalmente a cotización
            $venta->update([
                'id_tipo_comprobante' => 8, // Tipo Cotización
                'serie' => 'COT',
                'numero' => 'COT-' . str_pad($venta->id_venta, 8, '0', STR_PAD_LEFT),
                'xml_estado' => 'PENDIENTE'
            ]);

            return response()->json([
                'success' => true,
                'message' => "Venta ID {$venta->id_venta} convertida a cotización de prueba",
                'venta_id' => $venta->id_venta
            ]);

        } catch (\Exception $e) {
            \Log::error("Error al crear cotización de prueba: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function pago($id)
    {
        $venta = Venta::findOrFail($id);

        return view('ventas.pago', [
            'venta' => $venta
        ]);
    }

    public function registrarPagoConId(Request $request, $id)
    {
        \Log::info('Iniciando registro de pago', ['id_venta' => $id, 'request' => $request->all()]);

        $venta = Venta::findOrFail($id);
        \Log::info('Venta encontrada', ['venta' => $venta]);

        // Validate the payment input
        $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'metodo' => 'required|string|max:255',
            'numero_operacion' => 'nullable|string|max:255',
        ]);

        try {
            // Register the payment
            $pago = new \App\Models\PagoVenta();
            $pago->id_venta = $venta->id;
            $pago->monto = $request->monto;
            $pago->metodo = $request->metodo;
            $pago->numero_operacion = $request->numero_operacion;
            $pago->fecha = now();
            $pago->save();
            \Log::info('Pago registrado', ['pago' => $pago]);

            // Deduct the payment from the pending balance
            $venta->saldo_pendiente -= $request->monto;
            $venta->save();
            \Log::info('Saldo actualizado', ['venta' => $venta]);

            return redirect('/ventas')->with('success', 'Pago registrado correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error al registrar el pago', ['error' => $e->getMessage()]);
            return redirect('/ventas')->with('error', 'Hubo un problema al registrar el pago.');
        }
    }
}