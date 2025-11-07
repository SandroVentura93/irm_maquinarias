<?php

namespace App\Http\Controllers;

use App\Models\ComprobanteArchivo;
use Illuminate\Http\Request;

class ComprobanteArchivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $archivos = ComprobanteArchivo::with('comprobante')->get();
        return view('comprobante_archivos.index', compact('archivos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('comprobante_archivos.create');
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
            'id_comprobante' => 'required|exists:comprobantes_electronicos,id_comprobante',
            'tipo' => 'required|in:XML,PDF',
            'nombre_archivo' => 'required|string|max:500',
            'ruta' => 'nullable|string|max:1000',
            'tamanio_bytes' => 'nullable|numeric',
        ]);

        ComprobanteArchivo::create($validatedData);

        return redirect()->route('comprobante_archivos.index')->with('success', 'Archivo de comprobante creado exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ComprobanteArchivo  $comprobanteArchivo
     * @return \Illuminate\Http\Response
     */
    public function show(ComprobanteArchivo $comprobanteArchivo)
    {
        return view('comprobante_archivos.show', compact('comprobanteArchivo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ComprobanteArchivo  $comprobanteArchivo
     * @return \Illuminate\Http\Response
     */
    public function edit(ComprobanteArchivo $comprobanteArchivo)
    {
        return view('comprobante_archivos.edit', compact('comprobanteArchivo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ComprobanteArchivo  $comprobanteArchivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ComprobanteArchivo $comprobanteArchivo)
    {
        $validatedData = $request->validate([
            'id_comprobante' => 'required|exists:comprobantes_electronicos,id_comprobante',
            'tipo' => 'required|in:XML,PDF',
            'nombre_archivo' => 'required|string|max:500',
            'ruta' => 'nullable|string|max:1000',
            'tamanio_bytes' => 'nullable|numeric',
        ]);

        $comprobanteArchivo->update($validatedData);

        return redirect()->route('comprobante_archivos.index')->with('success', 'Archivo de comprobante actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ComprobanteArchivo  $comprobanteArchivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(ComprobanteArchivo $comprobanteArchivo)
    {
        $comprobanteArchivo->delete();

        return redirect()->route('comprobante_archivos.index')->with('success', 'Archivo de comprobante eliminado exitosamente.');
    }
}