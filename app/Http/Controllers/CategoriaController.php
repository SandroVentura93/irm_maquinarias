<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Muestra todas las categorías.
     */
    public function index()
    {
        $categorias = Categoria::orderBy('nombre')->paginate(10);
        return view('categorias.index', compact('categorias'));
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Guarda una nueva categoría en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:255',
            'activo' => 'required|boolean',
        ]);

        Categoria::create($validated);

        return redirect()
            ->route('categorias.index')
            ->with('success', '✅ Categoría creada exitosamente.');
    }

    /**
     * Muestra una categoría específica.
     */
    public function show(Categoria $categoria)
    {
        return view('categorias.show', compact('categoria'));
    }

    /**
     * Muestra el formulario para editar una categoría existente.
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Actualiza una categoría en la base de datos.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre,' . $categoria->id_categoria . ',id_categoria',
            'descripcion' => 'nullable|string|max:255',
            'activo' => 'required|boolean',
        ]);

        $categoria->update($validated);

        return redirect()
            ->route('categorias.index')
            ->with('success', '✅ Categoría actualizada correctamente.');
    }

    /**
     * Elimina una categoría.
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return redirect()
            ->route('categorias.index')
            ->with('success', '🗑️ Categoría eliminada exitosamente.');
    }
}
