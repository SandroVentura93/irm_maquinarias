<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\ComprobanteElectronico;
use PDF;

class VentaController extends Controller
{
    // Lista de ventas
    public function index()
    {
        $ventas = Venta::with(['cliente'])->orderBy('created_at', 'desc')->get();
        return view('ventas.index', compact('ventas'));
    }

    // Vista principal del formulario
    public function create()
    {
        return view('ventas.create');
    }

    // Buscar cliente por RUC/DNI (mejorado)
    public function buscarCliente(Request $r)
    {
        $doc = $r->query('doc', '');
        
        if (empty($doc)) {
            return response()->json(['found' => false, 'message' => 'Número de documento requerido']);
        }

        try {
            $cliente = Cliente::where('numero_documento', $doc)->first();

            if ($cliente) {
                return response()->json([
                    'found' => true, 
                    'cliente' => [
                        'id_cliente' => $cliente->id_cliente,
                        'numero_documento' => $cliente->numero_documento,
                        'tipo_documento' => $cliente->tipo_documento ?? 'RUC',
                        'nombre' => $cliente->nombre,
                        'razon_social' => $cliente->razon_social,
                        'direccion' => $cliente->direccion,
                        'telefono' => $cliente->telefono,
                        'email' => $cliente->email
                    ]
                ]);
            }

            return response()->json([
                'found' => false, 
                'message' => 'Cliente no encontrado'
            ]);
            
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
                    'stock_status' => ($producto->stock_actual ?? 0) <= ($producto->stock_minimo ?? 0) ? 'Bajo' : 'Normal',
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
            'tipo_comprobante' => 'required|string',
            'moneda' => 'required|string',
            'serie' => 'required|string',
            // 'numero' se auto-genera, no requerido en el request
            'detalle' => 'required|array|min:1',
            'detalle.*.id_producto' => 'required|integer',
            'detalle.*.cantidad' => 'required|numeric|min:0.01',
            'detalle.*.precio_unitario' => 'required|numeric|min:0',
            'detalle.*.descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            $igv_rate = 0.18;
            $subtotal = 0;

            foreach ($data['detalle'] as $d) {
                $precio_final = $d['precio_unitario'] * (1 - ($d['descuento_porcentaje'] ?? 0) / 100);
                $subtotal += $precio_final * $d['cantidad'];
            }

            $igv = round($subtotal * $igv_rate, 2);
            $total = round($subtotal + $igv, 2);

            // Mapear valores del formulario a IDs de base de datos
            $id_moneda = $data['moneda'] === 'PEN' ? 1 : 2; // 1=PEN, 2=USD
            
            // Mapear tipo de comprobante de manera consistente
            $tipoComprobanteMap = [
                'Cotizacion' => 4,
                'Factura' => 1,
                'Boleta' => 2,
                'Nota de Crédito' => 3
            ];
            $id_tipo_comprobante = $tipoComprobanteMap[$data['tipo_comprobante']] ?? 1;

            // Obtener el último número para esta serie y tipo de comprobante
            $ultimo_numero_venta = Venta::where('serie', $data['serie'])
                ->where('id_tipo_comprobante', $id_tipo_comprobante)
                ->max('numero');
            
            // Extraer solo el número si tiene formato (ej: "F001-00000123" -> 123)
            if ($ultimo_numero_venta && is_string($ultimo_numero_venta)) {
                if (strpos($ultimo_numero_venta, '-') !== false) {
                    $ultimo_numero_venta = explode('-', $ultimo_numero_venta)[1];
                }
                $ultimo_numero_venta = intval($ultimo_numero_venta);
            } else {
                $ultimo_numero_venta = intval($ultimo_numero_venta ?: 0);
            }
            
            $nuevo_numero = $ultimo_numero_venta + 1;
            
            // Crear formato de número según tipo de comprobante
            $prefijos = [
                'Cotizacion' => 'COT-',
                'Factura' => 'F001-',
                'Boleta' => 'B001-',
                'Nota de Crédito' => 'NC01-'
            ];
            $prefijo = $prefijos[$data['tipo_comprobante']] ?? '';
            $numero_formateado = $prefijo . str_pad($nuevo_numero, 8, '0', STR_PAD_LEFT);

            $venta = Venta::create([
                'id_cliente' => $data['id_cliente'],
                'id_vendedor' => auth()->user()->id_usuario, // Asignar el vendedor logueado
                'id_moneda' => $id_moneda,
                'id_tipo_comprobante' => $id_tipo_comprobante,
                'serie' => $data['serie'],
                'numero' => $numero_formateado, // Usar formato con prefijo
                'fecha' => now(),
                'subtotal' => $subtotal,
                'igv' => $igv,
                'total' => $total,
                'xml_estado' => 'PENDIENTE'
            ]);

            \Log::info('Venta creada:', ['id_venta' => $venta->id_venta]);

            foreach ($data['detalle'] as $d) {
                $precio_final = $d['precio_unitario'] * (1 - ($d['descuento_porcentaje'] ?? 0) / 100);
                $subtotal_linea = $precio_final * $d['cantidad'];
                $igv_linea = $subtotal_linea * $igv_rate;
                $total_linea = $subtotal_linea + $igv_linea;

                DetalleVenta::create([
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

                Producto::where('id_producto', $d['id_producto'])
                    ->decrement('stock_actual', $d['cantidad']);
            }

            // Crear el comprobante electrónico con el mismo número
            $comp = ComprobanteElectronico::create([
                'id_venta' => $venta->id_venta,
                'id_tipo_comprobante' => $id_tipo_comprobante,
                'serie' => $data['serie'],
                'numero' => $nuevo_numero, // Usar el número entero, no el string con ceros
                'fecha_emision' => now(),
                'monto_subtotal' => $subtotal,
                'monto_igv' => $igv,
                'monto_total' => $total,
                'moneda_id' => $id_moneda,
                'estado' => 'PENDIENTE',
            ]);

            DB::commit();

            return response()->json([
                'ok' => true, 
                'id_venta' => $venta->id_venta, 
                'total' => $total,
                'numero_comprobante' => $nuevo_numero,
                'serie' => $data['serie']
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
        return view('ventas.show', compact('venta'));
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $venta = Venta::with(['cliente', 'vendedor', 'detalleVentas.producto'])->findOrFail($id);
        
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
        
        return view('ventas.edit', compact('venta', 'clientes', 'productos'));
    }

    // Actualizar venta
    public function update(Request $request, $id)
    {
        \Log::info('Iniciando actualización de venta', ['id' => $id, 'datos' => $request->all()]);
        
        $venta = Venta::with(['detalleVentas', 'comprobanteElectronico'])->findOrFail($id);
        
        // Solo permitir edición si la venta está en estado PENDIENTE
        if ($venta->xml_estado !== 'PENDIENTE') {
            \Log::warning('Intento de editar venta no pendiente', ['id' => $id, 'estado' => $venta->xml_estado]);
            return redirect()->route('ventas.show', $id)
                ->with('error', 'Solo se pueden editar ventas en estado PENDIENTE');
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
        return redirect()->route('ventas.create')->with('info', 'Use la interfaz AJAX para registrar ventas');
    }

    // Generar PDF del comprobante electrónico
    public function generarPDF($id)
    {
        try {
            // Cargar venta con todas sus relaciones
            $venta = Venta::with(['cliente', 'detalleVentas.producto', 'comprobanteElectronico', 'vendedor'])
                         ->findOrFail($id);

            // Determinar el tipo de comprobante basado en el ID
            $tiposComprobante = [
                1 => 'Factura',
                2 => 'Boleta',
                3 => 'Nota de Crédito',
                4 => 'Cotizacion'
            ];
            
            $tipoComprobante = $tiposComprobante[$venta->id_tipo_comprobante] ?? 'Comprobante';
            
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
                    'simbolo' => 'S/.',
                    'descripcion' => 'Soles'
                ],
                'descuentoTotal' => $venta->detalleVentas->sum('descuento_monto') ?? 0,
                'totalEnLetras' => $this->numeroALetras($venta->total ?? 0)
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

    // Confirmar cancelación de venta
    /**
     * Convertir cotización a factura o boleta
     */
    public function convertirCotizacion(Request $request, $id)
    {
        try {
            $venta = Venta::findOrFail($id);
            
            // Verificar que sea una cotización y esté pendiente
            if ($venta->id_tipo_comprobante != 4) {
                return response()->json([
                    'success' => false,
                    'error' => 'Solo se pueden convertir cotizaciones'
                ], 400);
            }
            
            if ($venta->xml_estado !== 'PENDIENTE') {
                return response()->json([
                    'success' => false,
                    'error' => 'Solo se pueden convertir cotizaciones pendientes'
                ], 400);
            }
            
            $tipoDestino = $request->input('tipo_destino');
            
            if (!in_array($tipoDestino, ['Factura', 'Boleta'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Tipo de destino inválido'
                ], 400);
            }
            
            DB::beginTransaction();
            
            // Mapear tipo destino
            $tipoComprobanteMap = [
                'Factura' => 1,
                'Boleta' => 2
            ];
            $nuevoTipoId = $tipoComprobanteMap[$tipoDestino];
            
            // Configuración de series
            $seriesConfig = [
                'Factura' => ['serie' => 'F001', 'prefijo' => 'F001-'],
                'Boleta' => ['serie' => 'B001', 'prefijo' => 'B001-']
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
            
            DB::commit();
            
            \Log::info("Cotización convertida", [
                'id_venta' => $id,
                'tipo_original' => 'Cotización',
                'tipo_destino' => $tipoDestino,
                'nueva_serie' => $nuevaSerie,
                'nuevo_numero' => $nuevoNumeroFormateado
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Cotización convertida exitosamente a {$tipoDestino}",
                'nueva_serie' => $nuevaSerie,
                'nuevo_numero' => $nuevoNumeroFormateado,
                'tipo_destino' => $tipoDestino
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error("Error al convertir cotización: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener el siguiente número de comprobante para un tipo y serie específicos
     */
    public function siguienteNumero(Request $request)
    {
        $tipo = $request->get('tipo');
        $serie = $request->get('serie');
        
        if (!$tipo || !$serie) {
            return response()->json(['error' => 'Tipo y serie son requeridos'], 400);
        }
        
        // Mapear tipo de comprobante a ID
        $tipoComprobanteMap = [
            'Cotizacion' => 4,
            'Factura' => 1,
            'Boleta' => 2,
            'Nota de Crédito' => 3
        ];
        
        $idTipoComprobante = $tipoComprobanteMap[$tipo] ?? 1;
        
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
            
            // Revertir stock de productos
            foreach ($venta->detalleVentas as $detalle) {
                $producto = Producto::find($detalle->id_producto);
                if ($producto) {
                    $oldStock = $producto->stock_actual;
                    $producto->stock_actual += $detalle->cantidad;
                    $producto->save();
                    \Log::info('Stock actualizado', [
                        'producto_id' => $producto->id,
                        'stock_anterior' => $oldStock,
                        'cantidad_revertida' => $detalle->cantidad,
                        'stock_nuevo' => $producto->stock_actual
                    ]);
                }
            }
            
            // Actualizar estado de la venta
            $venta->update([
                'xml_estado' => 'ANULADO',
                'fecha_anulacion' => now(),
                'motivo_anulacion' => $request->input('motivo', 'Anulación manual')
            ]);
            
            \Log::info('Venta anulada exitosamente', ['venta_id' => $id]);
            
            DB::commit();
            
            return redirect()->route('ventas.index')
                           ->with('success', 'Venta anulada exitosamente. Stock revertido.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error al anular venta', ['error' => $e->getMessage(), 'venta_id' => $id]);
            return redirect()->back()
                           ->with('error', 'Error al cancelar la venta: ' . $e->getMessage());
        }
    }
}