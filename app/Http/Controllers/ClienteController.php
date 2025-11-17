<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
            'nombre' => 'nullable|max:255',
            'direccion' => 'nullable|max:255',
            'id_ubigeo' => 'nullable|size:6',
            'telefono' => 'nullable|max:20',
            'correo' => 'nullable|email|max:100',
            'activo' => 'required|boolean',
        ]);

        $cliente = Cliente::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Cliente creado exitosamente.',
            'cliente' => $cliente
        ]);
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
        $query = $request->get('q', '');
        
        if (strlen($query) < 3) {
            return response()->json([]);
        }
        
        // ⚡ Cache para búsquedas frecuentes
        $cacheKey = "cliente_search_" . md5($query);
        
        $clientes = Cache::remember($cacheKey, 600, function() use ($query) { // 10 minutos
            return Cliente::select('id_cliente', 'numero_documento', 'nombre', 'apellidos', 'direccion', 'telefono', 'correo', 'tipo_documento')
                ->where(function($q) use ($query) {
                    $q->where('numero_documento', 'LIKE', "%{$query}%")
                      ->orWhere('nombre', 'LIKE', "%{$query}%")
                      ->orWhere('apellidos', 'LIKE', "%{$query}%")
                      ->orWhere('correo', 'LIKE', "%{$query}%");
                })
                ->orderBy('nombre')
                ->limit(10)
                ->get()
                ->map(function($cliente) {
                    return [
                        'id_cliente' => $cliente->id_cliente,
                        'documento' => $cliente->numero_documento,
                        'nombres' => $cliente->nombre,
                        'apellidos' => $cliente->apellidos ?? '',
                        'nombre_completo' => trim($cliente->nombre . ' ' . ($cliente->apellidos ?? '')),
                        'direccion' => $cliente->direccion ?? '',
                        'telefono' => $cliente->telefono ?? '',
                        'correo' => $cliente->correo ?? '',
                        'tipo_documento' => $cliente->tipo_documento
                    ];
                });
        });
        
        return response()->json($clientes);
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
                'ok' => true,
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
                'ok' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
