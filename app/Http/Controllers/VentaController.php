<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\TipoComprobante;
use App\Models\Moneda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ventas = Venta::with(['cliente', 'vendedor'])->get();
        return view('ventas.index', compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::where('activo', 1)->get();
        $tiposComprobante = TipoComprobante::all();
        $monedas = Moneda::all();
        
        return view('ventas.create', compact('clientes', 'productos', 'tiposComprobante', 'monedas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required|string',
            'cliente_id' => 'required|exists:clientes,id_cliente',
            'tipo_comprobante_id' => 'required|integer',
            'serie' => 'required|string',
            'correlativo' => 'required|string',
            'moneda_id' => 'required|integer',
            'productos' => 'required|array',
            'productos.*.producto_id' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
            'productos.*.descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
            'productos.*.precio_final' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Crear la venta
            $venta = new Venta();
            $venta->fecha = $validatedData['fecha'] . ' ' . $validatedData['hora'];
            $venta->id_cliente = $validatedData['cliente_id'];
            $venta->id_vendedor = auth()->user()->id_usuario; // Asignar el usuario logueado como vendedor
            $venta->id_tipo_comprobante = $validatedData['tipo_comprobante_id'];
            $venta->serie = $validatedData['serie'];
            $venta->numero = $validatedData['correlativo'];
            $venta->id_moneda = $validatedData['moneda_id'];
            $venta->subtotal = 0;
            $venta->igv = 0;
            $venta->total = 0;
            $venta->save();

            $total_final = 0;
            
            // Crear los detalles de venta
            foreach ($validatedData['productos'] as $producto) {
                $total_linea = $producto['precio_final'] * $producto['cantidad'];
                $subtotal_linea = $total_linea / 1.18; // Sin IGV
                $igv_linea = $total_linea - $subtotal_linea; // IGV calculado
                
                $detalleVenta = new DetalleVenta();
                $detalleVenta->id_venta = $venta->id_venta;
                $detalleVenta->id_producto = $producto['producto_id'];
                $detalleVenta->cantidad = $producto['cantidad'];
                $detalleVenta->precio_unitario = $producto['precio_unitario'];
                $detalleVenta->descuento_porcentaje = isset($producto['descuento_porcentaje']) ? $producto['descuento_porcentaje'] : 0;
                $detalleVenta->precio_final = $producto['precio_final'];
                $detalleVenta->subtotal = $subtotal_linea;
                $detalleVenta->igv = $igv_linea;
                $detalleVenta->total = $total_linea;
                $detalleVenta->save();

                // Actualizar stock del producto
                $productoModel = Producto::find($producto['producto_id']);
                if ($productoModel) {
                    $productoModel->stock_actual -= $producto['cantidad'];
                    $productoModel->save();
                }
                
                $total_final += $total_linea;
            }

            // Actualizar totales de la venta
            $venta->subtotal = $total_final / 1.18; // Sin IGV
            $venta->igv = $total_final - $venta->subtotal; // IGV calculado
            $venta->total = $total_final;
            $venta->save();

            DB::commit();

            return redirect()->route('ventas.index')->with('success', 'Venta registrada exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('Error al registrar la venta: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'vendedor']);
        return view('ventas.show', compact('venta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function edit(Venta $venta)
    {
        // Solo permitir editar ventas en estado PENDIENTE
        if ($venta->xml_estado !== 'PENDIENTE') {
            return view('ventas.edit', compact('venta'));
        }
        
        $clientes = \App\Models\Cliente::all();
        $productos = \App\Models\Producto::where('stock', '>', 0)->get();
        
        return view('ventas.edit', compact('venta', 'clientes', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venta $venta)
    {
        // Solo permitir editar ventas en estado PENDIENTE
        if ($venta->xml_estado !== 'PENDIENTE') {
            return redirect()->route('ventas.index')->with('error', 'Solo se pueden editar ventas en estado PENDIENTE.');
        }
        
        $validatedData = $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required|string',
            'cliente_id' => 'required|exists:clientes,id_cliente',
            'serie' => 'required|string|max:10',
            'correlativo' => 'required|string|max:20',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id_producto',
            'productos.*.cantidad' => 'required|numeric|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
            'productos.*.descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
            'productos.*.precio_final' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Restaurar stock de productos anteriores
            foreach ($venta->detalleVentas as $detalle) {
                $producto = Producto::find($detalle->id_producto);
                if ($producto) {
                    $producto->stock += $detalle->cantidad;
                    $producto->save();
                }
            }

            // Eliminar detalles anteriores
            $venta->detalleVentas()->delete();

            // Combinar fecha y hora
            $fechaCompleta = $validatedData['fecha'] . ' ' . $validatedData['hora'] . ':00';

            // Calcular totales
            $subtotal = 0;
            $descuentoTotal = 0;
            
            foreach ($validatedData['productos'] as $productoData) {
                $precioSinDescuento = $productoData['precio_unitario'] * $productoData['cantidad'];
                $descuentoLinea = $precioSinDescuento * ((isset($productoData['descuento_porcentaje']) ? $productoData['descuento_porcentaje'] : 0) / 100);
                $subtotal += $precioSinDescuento - $descuentoLinea;
                $descuentoTotal += $descuentoLinea;
            }
            
            $igv = $subtotal * 0.18;
            $total = $subtotal + $igv;

            // Actualizar venta
            $venta->update([
                'fecha' => $fechaCompleta,
                'id_cliente' => $validatedData['cliente_id'],
                'serie' => $validatedData['serie'],
                'numero' => $validatedData['correlativo'],
                'subtotal' => $subtotal,
                'descuento' => $descuentoTotal,
                'igv' => $igv,
                'total' => $total,
                'fecha_actualizacion' => now()
            ]);

            // Crear nuevos detalles y actualizar stock
            foreach ($validatedData['productos'] as $productoData) {
                // Verificar stock disponible
                $producto = Producto::find($productoData['producto_id']);
                if (!$producto || $producto->stock < $productoData['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto: " . ($producto ? $producto->descripcion : 'ID ' . $productoData['producto_id']));
                }

                // Crear detalle
                DetalleVenta::create([
                    'id_venta' => $venta->id_venta,
                    'id_producto' => $productoData['producto_id'],
                    'cantidad' => $productoData['cantidad'],
                    'precio_unitario' => $productoData['precio_unitario'],
                    'descuento_porcentaje' => isset($productoData['descuento_porcentaje']) ? $productoData['descuento_porcentaje'] : 0,
                    'precio_final' => $productoData['precio_final'],
                    'total' => $productoData['cantidad'] * $productoData['precio_final']
                ]);

                // Reducir stock
                $producto->stock -= $productoData['cantidad'];
                $producto->save();
            }

            DB::commit();
            return redirect()->route('ventas.index')->with('success', 'Venta actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar la venta: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venta $venta)
    {
        $venta->delete();

        return redirect()->route('ventas.index')->with('success', 'Venta eliminada exitosamente.');
    }

    /**
     * Show confirmation page for canceling a sale.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function confirmCancel(Venta $venta)
    {
        // Validar que la venta puede ser anulada
        if (!in_array($venta->xml_estado, ['PENDIENTE', 'ENVIADO', 'ACEPTADO'])) {
            return redirect()->route('ventas.index')->withErrors('Esta venta no puede ser anulada.');
        }

        $venta->load(['cliente', 'vendedor', 'detalleVentas.producto']);
        return view('ventas.confirm-cancel', compact('venta'));
    }

    /**
     * Cancel the specified sale.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function cancel(Venta $venta)
    {
        try {
            DB::beginTransaction();

            // Validar que la venta puede ser anulada
            if ($venta->xml_estado === 'RECHAZADO') {
                return back()->withErrors('No se puede anular una venta que ya fue rechazada.');
            }

            if ($venta->xml_estado === 'ANULADO') {
                return back()->withErrors('Esta venta ya se encuentra anulada.');
            }

            // Cargar los detalles de la venta
            $venta->load('detalleVentas.producto');

            // Revertir el stock de cada producto
            foreach ($venta->detalleVentas as $detalle) {
                if ($detalle->producto) {
                    $detalle->producto->stock_actual += $detalle->cantidad;
                    $detalle->producto->save();
                }
            }

            // Actualizar el estado de la venta
            $venta->xml_estado = 'ANULADO';
            $venta->fecha_anulacion = now();
            $venta->motivo_anulacion = 'Anulación manual por usuario: ' . (auth()->user()->nombre ?: 'Sistema');
            $venta->save();

            DB::commit();

            return redirect()->route('ventas.index')->with('success', 
                'Venta anulada exitosamente. El stock de los productos ha sido revertido.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('Error al anular la venta: ' . $e->getMessage());
        }
    }

    /**
     * Generar PDF del comprobante electrónico
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function generarPDF(Venta $venta)
    {
        // Cargar relaciones necesarias
        $venta->load([
            'cliente', 
            'vendedor',
            'detalleVentas.producto'
        ]);

        // Obtener detalles de la venta
        $detalles = $venta->detalleVentas;
        
        // Calcular descuento total
        $descuentoTotal = $detalles->sum(function($detalle) {
            return ($detalle->precio_unitario - $detalle->precio_final) * $detalle->cantidad;
        });

        // Obtener tipo de comprobante
        $tipoComprobante = DB::table('tipo_comprobantes')
            ->where('id_tipo_comprobante', $venta->id_tipo_comprobante)
            ->first();

        // Obtener moneda
        $moneda = DB::table('monedas')
            ->where('id_moneda', $venta->id_moneda)
            ->first();

        // Convertir número a letras (función simple)
        $totalEnLetras = $this->numeroALetras($venta->total);

        // Generar PDF
        $pdf = PDF::loadView('comprobantes.pdf', compact(
            'venta',
            'detalles', 
            'tipoComprobante',
            'moneda',
            'descuentoTotal',
            'totalEnLetras'
        ));

        // Configurar el PDF
        $pdf->setPaper('A4', 'portrait');
        
        // Nombre del archivo
        $fileName = strtolower($tipoComprobante->descripcion) . '_' . $venta->serie . '-' . $venta->numero . '.pdf';
        
        return $pdf->download($fileName);
    }

    /**
     * Convertir número a letras (implementación básica)
     *
     * @param float $numero
     * @return string
     */
    private function numeroALetras($numero)
    {
        $formatter = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);
        $enteros = floor($numero);
        $decimales = round(($numero - $enteros) * 100);
        
        $letras = $formatter->format($enteros);
        
        if ($decimales > 0) {
            return strtoupper($letras) . ' CON ' . sprintf('%02d', $decimales) . '/100 SOLES';
        }
        
        return strtoupper($letras) . ' CON 00/100 SOLES';
    }
}