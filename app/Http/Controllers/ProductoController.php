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
            $query->where('id_categoria', $request->categoria_id);
        }
        
        // Filtro por marca
        if ($request->has('marca_id') && $request->marca_id != '') {
            $query->where('id_marca', $request->marca_id);
        }
        
        // Filtro por estado de stock
        if ($request->has('stock_status') && $request->stock_status != '') {
            if ($request->stock_status == 'bajo') {
                $query->whereColumn('stock_actual', '<', 'stock_minimo');
            } elseif ($request->stock_status == 'normal') {
                $query->whereColumn('stock_actual', '>=', 'stock_minimo');
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
        
        // Estadísticas rápidas
        $estadisticas = [
            'total_productos' => Producto::count(),
            'productos_bajo_stock' => Producto::whereColumn('stock_actual', '<', 'stock_minimo')->count(),
            'valor_total_inventario' => Producto::sum('precio_venta'),
        ];
        // Tipo de cambio manual: por defecto 3.80, editable en la vista
        $tipoCambio = $request->get('tipo_cambio', session('tipo_cambio', 3.80));
        session(['tipo_cambio' => $tipoCambio]);
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
        
        // Tipo de cambio manual: por defecto 3.80, editable en la vista
        $tipoCambio = session('tipo_cambio', 3.80);
        return view('productos.create', compact('categorias', 'marcas', 'proveedores', 'tipoCambio'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_categoria' => 'nullable|exists:categorias,id_categoria',
            'id_marca' => 'nullable|exists:marcas,id_marca',
            'id_proveedor' => 'nullable|exists:proveedores,id_proveedor',
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
        // Tipo de cambio manual: por defecto 3.80, editable en la vista
        $tipoCambio = session('tipo_cambio', 3.80);
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
        
        // Tipo de cambio manual: por defecto 3.80, editable en la vista
        $tipoCambio = session('tipo_cambio', 3.80);
        return view('productos.edit', compact('producto', 'categorias', 'marcas', 'proveedores', 'tipoCambio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'id_categoria' => 'nullable|exists:categorias,id_categoria',
            'id_marca' => 'nullable|exists:marcas,id_marca',
            'id_proveedor' => 'nullable|exists:proveedores,id_proveedor',
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


    /**
     * ⚡ Búsqueda optimizada para AJAX (formulario de ventas)
     */
    public function searchPublic(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        // Sin caché: siempre devolver stock actualizado
        $productos = Producto::with(['categoria', 'marca', 'proveedor'])
            ->select(
                'id_producto', 'codigo', 'numero_parte', 'descripcion', 'modelo', 'precio_venta', 'precio_compra',
                'stock_actual', 'stock_minimo', 'ubicacion', 'peso', 'importado', 'activo', 'id_categoria', 'id_marca', 'id_proveedor'
            )
            ->where(function($q) use ($query) {
                $q->where('codigo', 'LIKE', "%{$query}%")
                  ->orWhere('numero_parte', 'LIKE', "%{$query}%")
                  ->orWhere('descripcion', 'LIKE', "%{$query}%")
                  ->orWhere('modelo', 'LIKE', "%{$query}%");
            })
            ->orderBy('descripcion')
            ->limit(15)
            ->get()
            ->map(function($producto) {
                return [
                    'id_producto' => $producto->id_producto,
                    'codigo' => $producto->codigo ?? '',
                    'numero_parte' => $producto->numero_parte ?? '',
                    'descripcion' => $producto->descripcion ?? '',
                    'modelo' => $producto->modelo ?? '',
                    'precio_venta' => number_format($producto->precio_venta ?? 0, 2, '.', ''),
                    'precio_compra' => number_format($producto->precio_compra ?? 0, 2, '.', ''),
                    'stock_actual' => intval($producto->stock_actual ?? 0),
                    'stock_minimo' => intval($producto->stock_minimo ?? 0),
                    'ubicacion' => $producto->ubicacion ?? 'Sin ubicación',
                    'peso' => $producto->peso ?? 0,
                    'importado' => $producto->importado ? 'Sí' : 'No',
                    'activo' => $producto->activo ? 'Activo' : 'Inactivo',
                    'categoria' => $producto->categoria ? $producto->categoria->descripcion : 'Sin categoría',
                    'marca' => $producto->marca ? $producto->marca->descripcion : 'Sin marca',
                    'proveedor' => $producto->proveedor ? ($producto->proveedor->nombre ?? $producto->proveedor->razon_social ?? $producto->proveedor->descripcion) : 'Sin proveedor',
                    'stock_status' => ($producto->stock_actual ?? 0) <= ($producto->stock_minimo ?? 0) ? 'Bajo' : 'Normal',
                ];
            });
        return response()->json($productos);
    }
}