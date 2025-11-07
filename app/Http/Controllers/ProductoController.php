<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Proveedor;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::orderBy('descripcion')->paginate(10);
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $marcas = Marca::all();
        $proveedores = Proveedor::all();

        return view('productos.create', compact('categorias', 'marcas', 'proveedores'));
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
        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        $marcas = Marca::all();
        $proveedores = Proveedor::all();

        return view('productos.edit', compact('producto', 'categorias', 'marcas', 'proveedores'));
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
}