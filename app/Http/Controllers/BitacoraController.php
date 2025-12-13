<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\Http\Request;

class BitacoraController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->only(['destroy']);
    }
    public function index()
    {
        $bitacoras = Bitacora::all();
        return view('bitacoras.index', compact('bitacoras'));
    }

    public function create()
    {
        return view('bitacoras.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'nullable|integer',
            'accion' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        Bitacora::create($request->all());

        return redirect()->route('bitacoras.index');
    }

    public function show(Bitacora $bitacora)
    {
        return view('bitacoras.show', compact('bitacora'));
    }

    public function edit(Bitacora $bitacora)
    {
        return view('bitacoras.edit', compact('bitacora'));
    }

    public function update(Request $request, Bitacora $bitacora)
    {
        $request->validate([
            'id_usuario' => 'nullable|integer',
            'accion' => 'nullable|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $bitacora->update($request->all());

        return redirect()->route('bitacoras.index');
    }

    public function destroy(Bitacora $bitacora)
    {
        $bitacora->delete();

        return redirect()->route('bitacoras.index');
    }
}