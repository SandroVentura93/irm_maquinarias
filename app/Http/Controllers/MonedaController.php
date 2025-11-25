<?php

namespace App\Http\Controllers;

use App\Models\Moneda;
use Illuminate\Http\Request;

class MonedaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $monedas = Moneda::all();
        return view('monedas.index', compact('monedas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('monedas.create');
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
            'nombre' => 'required|max:50',
            'simbolo' => 'required|max:5',
            'codigo_iso' => 'required|max:3',
        ]);

        Moneda::create($validatedData);

        return redirect()->route('monedas.index')->with('success', 'Moneda creada exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id_moneda
     * @return \Illuminate\Http\Response
     */
    public function show($id_moneda)
    {
        $moneda = Moneda::findOrFail($id_moneda);
        return view('monedas.show', compact('moneda'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Moneda  $moneda
     * @return \Illuminate\Http\Response
     */
    public function edit(Moneda $moneda)
    {
        return view('monedas.edit', compact('moneda'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Moneda  $moneda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Moneda $moneda)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|max:50',
            'simbolo' => 'required|max:5',
            'codigo_iso' => 'required|max:3',
        ]);

        $moneda->update($validatedData);

        return redirect()->route('monedas.index')->with('success', 'Moneda actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Moneda  $moneda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Moneda $moneda)
    {
        $moneda->delete();

        return redirect()->route('monedas.index')->with('success', 'Moneda eliminada exitosamente.');
    }
}
