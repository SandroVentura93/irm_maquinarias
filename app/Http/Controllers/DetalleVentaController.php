<?php

namespace App\Http\Controllers;

use App\Models\DetalleVenta;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detalleVentas = DetalleVenta::with(['venta', 'producto'])->get();
        return view('detalle_ventas.index', compact('detalleVentas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('detalle_ventas.create');
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
            'id_venta' => 'required|exists:ventas,id_venta',
            'id_producto' => 'required|exists:productos,id_producto',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'igv' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $detalle = DetalleVenta::create($validatedData);

        // Actualizar saldo solo si el comprobante es factura, boleta o ticket
        $venta = \App\Models\Venta::find($detalle->id_venta);
        $tipoComprobante = strtolower($venta->tipoComprobante->descripcion ?? '');
        if (in_array($tipoComprobante, ['factura', 'boleta', 'ticket'])) {
            $venta->total = $venta->total + $detalle->total;
            // Recalcular saldo: saldo = total - suma de pagos
            $pagos = $venta->pagos()->sum('monto');
            $venta->saldo = $venta->total - $pagos;
            $venta->save();
        }

        return redirect()->route('detalle_ventas.index')->with('success', 'Detalle de venta creado exitosamente y saldo actualizado.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetalleVenta  $detalleVenta
     * @return \Illuminate\Http\Response
     */
    public function show(DetalleVenta $detalleVenta)
    {
        return view('detalle_ventas.show', compact('detalleVenta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DetalleVenta  $detalleVenta
     * @return \Illuminate\Http\Response
     */
    public function edit(DetalleVenta $detalleVenta)
    {
        return view('detalle_ventas.edit', compact('detalleVenta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetalleVenta  $detalleVenta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetalleVenta $detalleVenta)
    {
        $validatedData = $request->validate([
            'id_venta' => 'required|exists:ventas,id',
            'id_producto' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'igv' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $detalleVenta->update($validatedData);

        return redirect()->route('detalle_ventas.index')->with('success', 'Detalle de venta actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetalleVenta  $detalleVenta
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetalleVenta $detalleVenta)
    {
        $detalleVenta->delete();

        return redirect()->route('detalle_ventas.index')->with('success', 'Detalle de venta eliminado exitosamente.');
    }
}