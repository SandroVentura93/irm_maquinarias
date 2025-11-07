<?php

namespace App\Http\Controllers;

use App\Models\ComprobanteElectronico;
use Illuminate\Http\Request;

class ComprobanteElectronicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comprobantes = ComprobanteElectronico::with(['venta', 'tipoComprobante'])->get();
        return view('comprobantes_electronicos.index', compact('comprobantes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('comprobantes_electronicos.create');
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
            'id_venta' => 'nullable|exists:ventas,id',
            'id_tipo_comprobante' => 'required|exists:tipo_comprobantes,id_tipo_comprobante',
            'serie' => 'required|string|max:10',
            'numero' => 'required|numeric',
            'cliente_ruc' => 'nullable|string|max:15',
            'cliente_razon_social' => 'nullable|string|max:200',
            'fecha_emision' => 'required|date',
            'monto_subtotal' => 'required|numeric',
            'monto_igv' => 'required|numeric',
            'monto_total' => 'required|numeric',
            'moneda_id' => 'required|exists:monedas,id',
            'xml_nombre' => 'nullable|string|max:255',
            'xml_hash' => 'nullable|string|max:255',
            'pdf_nombre' => 'nullable|string|max:255',
            'estado' => 'required|in:PENDIENTE,FIRMADO,ENVIADO,ACEPTADO,RECHAZO,ANULADO',
            'respuesta_sunat' => 'nullable|json',
            'qr' => 'nullable|string',
            'usuario_genero' => 'nullable|string|max:100',
        ]);

        ComprobanteElectronico::create($validatedData);

        return redirect()->route('comprobantes_electronicos.index')->with('success', 'Comprobante electrónico creado exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ComprobanteElectronico  $comprobanteElectronico
     * @return \Illuminate\Http\Response
     */
    public function show(ComprobanteElectronico $comprobanteElectronico)
    {
        return view('comprobantes_electronicos.show', compact('comprobanteElectronico'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ComprobanteElectronico  $comprobanteElectronico
     * @return \Illuminate\Http\Response
     */
    public function edit(ComprobanteElectronico $comprobanteElectronico)
    {
        return view('comprobantes_electronicos.edit', compact('comprobanteElectronico'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ComprobanteElectronico  $comprobanteElectronico
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ComprobanteElectronico $comprobanteElectronico)
    {
        $validatedData = $request->validate([
            'id_venta' => 'nullable|exists:ventas,id',
            'id_tipo_comprobante' => 'required|exists:tipo_comprobantes,id_tipo_comprobante',
            'serie' => 'required|string|max:10',
            'numero' => 'required|numeric',
            'cliente_ruc' => 'nullable|string|max:15',
            'cliente_razon_social' => 'nullable|string|max:200',
            'fecha_emision' => 'required|date',
            'monto_subtotal' => 'required|numeric',
            'monto_igv' => 'required|numeric',
            'monto_total' => 'required|numeric',
            'moneda_id' => 'required|exists:monedas,id',
            'xml_nombre' => 'nullable|string|max:255',
            'xml_hash' => 'nullable|string|max:255',
            'pdf_nombre' => 'nullable|string|max:255',
            'estado' => 'required|in:PENDIENTE,FIRMADO,ENVIADO,ACEPTADO,RECHAZO,ANULADO',
            'respuesta_sunat' => 'nullable|json',
            'qr' => 'nullable|string',
            'usuario_genero' => 'nullable|string|max:100',
        ]);

        $comprobanteElectronico->update($validatedData);

        return redirect()->route('comprobantes_electronicos.index')->with('success', 'Comprobante electrónico actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ComprobanteElectronico  $comprobanteElectronico
     * @return \Illuminate\Http\Response
     */
    public function destroy(ComprobanteElectronico $comprobanteElectronico)
    {
        $comprobanteElectronico->delete();

        return redirect()->route('comprobantes_electronicos.index')->with('success', 'Comprobante electrónico eliminado exitosamente.');
    }
}