<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;

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
        return view('ventas.create');
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
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_moneda' => 'required|exists:monedas,id',
            'id_tipo_comprobante' => 'required|exists:tipo_comprobantes,id',
            'serie' => 'required|string|max:10',
            'numero' => 'required|string|max:20',
            'fecha' => 'required|date',
            'subtotal' => 'required|numeric',
            'igv' => 'required|numeric',
            'total' => 'required|numeric',
            'xml_hash' => 'nullable|string',
            'xml_nombre' => 'nullable|string',
            'xml_estado' => 'nullable|string',
            'qr_hash' => 'nullable|string',
        ]);

        $validatedData['total'] = $validatedData['subtotal'] + $validatedData['igv'];

        Venta::create($validatedData);

        return redirect()->route('ventas.index')->with('success', 'Venta registrada exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta)
    {
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
        return view('ventas.edit', compact('venta'));
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
        $validatedData = $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'tipo_comprobante' => 'required|string|max:50',
            'numero_comprobante' => 'required|string|max:50',
            'fecha' => 'required|date',
            'subtotal' => 'required|numeric',
            'descuento' => 'nullable|numeric',
            'total' => 'required|numeric',
            'metodo_pago' => 'required|string|max:50',
        ]);

        $venta->update($validatedData);

        return redirect()->route('ventas.index')->with('success', 'Venta actualizada exitosamente.');
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
     * Cancel or annul a sale.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function cancel(Venta $venta)
    {
        $venta->update(['estado' => 'anulada']);

        return redirect()->route('ventas.index')->with('success', 'Venta anulada exitosamente.');
    }
}