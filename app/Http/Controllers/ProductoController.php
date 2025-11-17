<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Proveedor;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'marca', 'proveedor']);
        
        // Búsqueda por texto
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('codigo', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtro por categoría
        if ($request->has('categoria_id') && $request->categoria_id != '') {
            $query->where('categoria_id', $request->categoria_id);
        }
        
        // Filtro por marca
        if ($request->has('marca_id') && $request->marca_id != '') {
            $query->where('marca_id', $request->marca_id);
        }
        
        // Filtro por estado de stock
        if ($request->has('stock_status') && $request->stock_status != '') {
            if ($request->stock_status == 'bajo') {
                $query->whereColumn('stock_actual', '<=', 'stock_minimo');
            } elseif ($request->stock_status == 'normal') {
                $query->whereColumn('stock_actual', '>', 'stock_minimo');
            }
        }
        
        // Ordenamiento
        $sortBy = $request->get('sort_by', 'descripcion');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);
        
        $productos = $query->paginate(15)->appends($request->all());
        
        // Obtener datos para filtros
        $categorias = Categoria::orderBy('descripcion')->get();
        $marcas = Marca::orderBy('descripcion')->get();
        
        // Obtener tipo de cambio actual
        $tipoCambio = $this->obtenerTipoCambio();
        
        // Estadísticas rápidas
        $estadisticas = [
            'total_productos' => Producto::count(),
            'productos_bajo_stock' => Producto::whereColumn('stock_actual', '<=', 'stock_minimo')->count(),
            'valor_total_inventario' => Producto::sum('precio_venta'),
        ];
        
        return view('productos.index', compact('productos', 'tipoCambio', 'categorias', 'marcas', 'estadisticas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $marcas = Marca::all();
        $proveedores = Proveedor::all();
        
        // Obtener tipo de cambio actual
        $tipoCambio = $this->obtenerTipoCambio();

        return view('productos.create', compact('categorias', 'marcas', 'proveedores', 'tipoCambio'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_categoria' => 'nullable|exists:categorias,id_categoria',
            'id_marca' => 'nullable|integer',
            'id_proveedor' => 'nullable|integer',
            'codigo' => 'required|string|max:50|unique:productos,codigo',
            'numero_parte' => 'nullable|string|max:50',
            'descripcion' => 'required|string|max:255',
            'modelo' => 'nullable|string|max:100',
            'peso' => 'nullable|numeric|min:0',
            'ubicacion' => 'nullable|string|max:100',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'importado' => 'required|boolean',
            'activo' => 'required|boolean',
        ]);

        Producto::create($validated);

        return redirect()
            ->route('productos.index')
            ->with('success', '✅ Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        // Obtener tipo de cambio actual
        $tipoCambio = $this->obtenerTipoCambio();
        
        return view('productos.show', compact('producto', 'tipoCambio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        $marcas = Marca::all();
        $proveedores = Proveedor::all();
        
        // Obtener tipo de cambio actual
        $tipoCambio = $this->obtenerTipoCambio();

        return view('productos.edit', compact('producto', 'categorias', 'marcas', 'proveedores', 'tipoCambio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'id_categoria' => 'nullable|exists:categorias,id_categoria',
            'id_marca' => 'nullable|integer',
            'id_proveedor' => 'nullable|integer',
            'codigo' => 'required|string|max:50|unique:productos,codigo,' . $producto->id_producto . ',id_producto',
            'numero_parte' => 'nullable|string|max:50',
            'descripcion' => 'required|string|max:255',
            'modelo' => 'nullable|string|max:100',
            'peso' => 'nullable|numeric|min:0',
            'ubicacion' => 'nullable|string|max:100',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'importado' => 'required|boolean',
            'activo' => 'required|boolean',
        ]);

        $producto->update($validated);

        return redirect()
            ->route('productos.index')
            ->with('success', '✅ Producto actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()
            ->route('productos.index')
            ->with('success', '🗑️ Producto eliminado exitosamente.');
    }

    /**
     * Buscar productos por código, número de parte, descripción o modelo.
     */
    public function buscar(Request $request)
    {
        $query = $request->query('query');

        $productos = Producto::where('codigo', 'like', "%$query%")
            ->orWhere('numero_parte', 'like', "%$query%")
            ->orWhere('descripcion', 'like', "%$query%")
            ->orWhere('modelo', 'like', "%$query%")
            ->get(['id_producto', 'codigo', 'numero_parte', 'descripcion', 'modelo', 'precio_venta']);

        return response()->json($productos);
    }

    /**
     * Mostrar detalles de un producto específico.
     */
    public function getDetails($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json(['error' => 'Producto no encontrado'], 404);
        }

        return response()->json([
            'id_producto' => $producto->id_producto,
            'descripcion' => $producto->descripcion,
            'precio_venta' => $producto->precio_venta,
        ]);
    }

    // Método para obtener tipo de cambio actual
    private function obtenerTipoCambio()
    {
        try {
            // Intentar obtener de caché primero
            $tipoCambio = Cache::get('tipo_cambio_actual');
            
            if ($tipoCambio) {
                return $tipoCambio;
            }
            
            // Si no hay en caché, obtener de API
            $tipoCambio = $this->obtenerTipoCambioAPI();
            
            if ($tipoCambio) {
                // Guardar en caché por 1 hora
                Cache::put('tipo_cambio_actual', $tipoCambio, 3600);
                return $tipoCambio;
            }
        } catch (\Exception $e) {
            \Log::warning('Error al obtener tipo de cambio de API: ' . $e->getMessage());
        }

        // Valor por defecto si falla la API
        return 3.75;
    }

    // Método para obtener tipo de cambio desde API externa
    private function obtenerTipoCambioAPI()
    {
        try {
            // Configurar SSL para evitar errores de certificados
            $response = Http::withOptions([
                'verify' => false,
            ])->timeout(10)->get('https://api.exchangerate-api.com/v4/latest/USD');
            
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['rates']['PEN'])) {
                    return round($data['rates']['PEN'], 2);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error en API de tipo de cambio: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * ⚡ Búsqueda optimizada para AJAX (formulario de ventas)
     */
    public function searchPublic(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        // ⚡ Cache para búsquedas frecuentes
        $cacheKey = "producto_search_" . md5($query);
        
        $productos = Cache::remember($cacheKey, 300, function() use ($query) { // 5 minutos
            return Producto::select('id_producto', 'codigo', 'nombre', 'descripcion', 'precio', 'stock_actual')
                ->where(function($q) use ($query) {
                    $q->where('codigo', 'LIKE', "%{$query}%")
                      ->orWhere('nombre', 'LIKE', "%{$query}%")
                      ->orWhere('descripcion', 'LIKE', "%{$query}%");
                })
                ->where('stock_actual', '>', 0) // Solo productos con stock
                ->orderBy('nombre')
                ->limit(10)
                ->get()
                ->map(function($producto) {
                    return [
                        'id' => $producto->id_producto,
                        'codigo' => $producto->codigo,
                        'desc' => $producto->nombre . ' - ' . $producto->descripcion,
                        'precio' => floatval($producto->precio),
                        'stock' => intval($producto->stock_actual)
                    ];
                });
        });
        
        return response()->json($productos);
    }
}