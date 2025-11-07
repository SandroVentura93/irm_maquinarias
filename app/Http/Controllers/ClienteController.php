<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use DB;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Cliente::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nombre', 'like', "%$search%")
                  ->orWhere('razon_social', 'like', "%$search%")
                  ->orWhere('numero_documento', 'like', "%$search%");
        }

        $clientes = $query->get();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ubigeos = DB::table('ubigeos')->select('id_ubigeo', 'departamento', 'provincia', 'distrito')->get();
        return view('clientes.create', compact('ubigeos'));
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
            'tipo_documento' => 'required|in:DNI,RUC,PASAPORTE',
            'numero_documento' => 'required|max:15',
            'razon_social' => 'nullable|max:255',
            'nombre' => 'nullable|max:255',
            'direccion' => 'nullable|max:255',
            'id_ubigeo' => 'nullable|size:6',
            'telefono' => 'nullable|max:20',
            'correo' => 'nullable|email|max:100',
            'activo' => 'required|boolean',
        ]);

        Cliente::create($validatedData);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        $ubigeos = DB::table('ubigeos')->select('id_ubigeo', 'departamento', 'provincia', 'distrito')->get();
        return view('clientes.edit', compact('cliente', 'ubigeos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        $validatedData = $request->validate([
            'tipo_documento' => 'required|in:DNI,RUC,PASAPORTE',
            'numero_documento' => 'required|max:15',
            'razon_social' => 'nullable|max:255',
            'nombre' => 'nullable|max:255',
            'direccion' => 'nullable|max:255',
            'id_ubigeo' => 'nullable|size:6',
            'telefono' => 'nullable|max:20',
            'correo' => 'nullable|email|max:100',
            'activo' => 'required|boolean',
        ]);

        $cliente->update($validatedData);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }

    /**
     * Buscar clientes por DNI o RUC.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buscar(Request $request)
    {
        $dniRuc = $request->query('dni_ruc');

        $clientes = Cliente::where('numero_documento', $dniRuc)->get();

        return response()->json($clientes);
    }

    /**
     * Buscar cliente por documento específico.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buscarPorDocumento(Request $request)
    {
        $documento = $request->query('documento');

        if (!$documento) {
            return response()->json(['error' => 'Documento requerido'], 400);
        }

        $cliente = Cliente::where('numero_documento', $documento)->first();

        if ($cliente) {
            return response()->json([
                'encontrado' => true,
                'cliente' => [
                    'id' => $cliente->id_cliente,
                    'documento' => $cliente->numero_documento,
                    'nombre' => $cliente->nombre_completo,
                    'direccion' => $cliente->direccion,
                    'telefono' => $cliente->telefono,
                    'email' => $cliente->correo,
                    'tipo_documento' => $cliente->tipo_documento
                ]
            ]);
        }

        return response()->json([
            'encontrado' => false,
            'mensaje' => 'Cliente no encontrado en la base de datos'
        ]);
    }
}
