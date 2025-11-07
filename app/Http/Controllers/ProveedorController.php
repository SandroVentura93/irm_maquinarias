<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Ubigeo;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proveedores = Proveedor::all();
        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ubigeos = Ubigeo::all();
        $proveedores = Proveedor::all();
        return view('proveedores.create', compact('ubigeos', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'razon_social' => 'required|string|max:191',
            'tipo_documento' => 'required|in:DNI,RUC,PASAPORTE',
            'numero_documento' => 'required|string|max:15|unique:proveedores,numero_documento',
            'contacto' => 'nullable|string|max:191',
            'telefono' => 'nullable|string|max:15',
            'correo' => 'nullable|email|max:191',
            'direccion' => 'nullable|string|max:191',
            'id_ubigeo' => 'nullable|string',
            'activo' => 'required|boolean',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id_proveedor
     * @return \Illuminate\Http\Response
     */
    public function show($id_proveedor)
    {
        $proveedor = Proveedor::findOrFail($id_proveedor);
        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id_proveedor
     * @return \Illuminate\Http\Response
     */
    public function edit($id_proveedor)
    {
        $proveedor = Proveedor::findOrFail($id_proveedor);
        $ubigeos = Ubigeo::all();
        return view('proveedores.edit', compact('proveedor', 'ubigeos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id_proveedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_proveedor)
    {
        $proveedor = Proveedor::findOrFail($id_proveedor);
        $request->validate([
            'razon_social' => 'required|string|max:191',
            'tipo_documento' => 'required|in:DNI,RUC,PASAPORTE',
            'numero_documento' => 'required|string|max:15|unique:proveedores,numero_documento,' . $proveedor->id_proveedor . ',id_proveedor',
            'contacto' => 'nullable|string|max:191',
            'telefono' => 'nullable|string|max:15',
            'correo' => 'nullable|email|max:191',
            'direccion' => 'nullable|string|max:191',
            'id_ubigeo' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id_proveedor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_proveedor)
    {
        $proveedor = Proveedor::findOrFail($id_proveedor);
        $proveedor->delete();

        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado exitosamente.');
    }
}
