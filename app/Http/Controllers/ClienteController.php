<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use DB;

class ClienteController extends Controller
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
    public function index(Request $request)
    {
        $query = Cliente::query();

        // Filtro por búsqueda
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                  ->orWhere('numero_documento', 'like', "%$search%")
                  ->orWhere('correo', 'like', "%$search%")
                  ->orWhere('telefono', 'like', "%$search%");
            });
        }

        // Filtro por tipo de documento
        if ($request->filled('tipo_documento')) {
            $query->where('tipo_documento', $request->input('tipo_documento'));
        }

        // Filtro por estado
        if ($request->filled('activo')) {
            $query->where('activo', $request->input('activo'));
        }

        // Ordenar por más recientes
        $query->orderBy('created_at', 'desc');

        // Paginar los resultados
        $clientes = $query->paginate(12);

        // Cargar ubigeos para los formularios/modales en la vista de clientes
        $ubigeos = DB::table('ubigeos')->select('id_ubigeo', 'departamento', 'provincia', 'distrito')->get();

        return view('clientes.index', compact('clientes', 'ubigeos'));
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
            'nombre' => 'nullable|max:255',
            'direccion' => 'nullable|max:255',
            'id_ubigeo' => 'nullable|size:6',
            'telefono' => 'nullable|max:20',
            'correo' => 'nullable|email|max:100',
            'activo' => 'required|boolean',
        ]);

        $cliente = Cliente::create($validatedData);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado exitosamente.');
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
            'nombre' => 'required|max:255',
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

    /**
     * ⚡ Búsqueda optimizada para AJAX (formulario de ventas)
     */
    public function searchPublic(Request $request)
    {
        $query = $request->get('doc', $request->get('q', ''));
        if (strlen($query) < 3) {
            return response()->json(['found' => false]);
        }
        $cliente = Cliente::where('numero_documento', $query)
            ->orWhere('nombre', 'LIKE', "%{$query}%")
            ->first();
        if ($cliente) {
            return response()->json([
                'found' => true,
                'cliente' => [
                    'id_cliente' => $cliente->id_cliente,
                    'numero_documento' => $cliente->numero_documento,
                    'tipo_documento' => $cliente->tipo_documento ?? 'RUC',
                    'nombre' => $cliente->nombre,
                    'direccion' => $cliente->direccion,
                    'telefono' => $cliente->telefono,
                    'email' => $cliente->correo
                ]
            ]);
        } else {
            return response()->json(['found' => false]);
        }
    }

    /**
     * ⚡ Sugerencias de clientes (autocomplete) para el formulario de ventas
     * Busca por número de documento o por nombre (parcial), devolviendo una lista.
     */
    public function suggestPublic(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $sugerencias = Cliente::query()
            ->where(function ($qb) use ($q) {
                $qb->where('numero_documento', 'LIKE', "%{$q}%")
                   ->orWhere('nombre', 'LIKE', "%{$q}%");
            })
            ->orderBy('nombre')
            ->limit(10)
            ->get(['id_cliente','numero_documento','tipo_documento','nombre','direccion','telefono','correo']);

        return response()->json($sugerencias);
    }

    /**
     * ⚡ Crear cliente público desde formulario de ventas
     */
    public function storePublic(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'tipo_documento' => 'required|in:DNI,RUC,CE,PP',
                'numero_documento' => 'required|string|max:20|unique:clientes,numero_documento',
                'nombre' => 'required|string|max:100',
                'apellidos' => 'nullable|string|max:100',
                'direccion' => 'nullable|string|max:200',
                'telefono' => 'nullable|string|max:20',
                'correo' => 'nullable|email|max:100'
            ]);

            $cliente = Cliente::create($validatedData);

            // ⚡ Limpiar cache relacionado
            Cache::forget('cliente_search_*');

            return response()->json([
                'success' => true,
                'cliente' => [
                    'id_cliente' => $cliente->id_cliente,
                    'documento' => $cliente->numero_documento,
                    'nombres' => $cliente->nombre,
                    'apellidos' => $cliente->apellidos ?? '',
                    'nombre_completo' => trim($cliente->nombre . ' ' . ($cliente->apellidos ?? '')),
                    'direccion' => $cliente->direccion ?? '',
                    'telefono' => $cliente->telefono ?? '',
                    'correo' => $cliente->correo ?? '',
                    'tipo_documento' => $cliente->tipo_documento
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo registrar el cliente. ' . $e->getMessage()
            ], 422);
        }
    }
}
