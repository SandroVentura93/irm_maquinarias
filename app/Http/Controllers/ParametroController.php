<?php

namespace App\Http\Controllers;

use App\Models\Parametro;
use Illuminate\Http\Request;

class ParametroController extends Controller
{
    public function index()
    {
        $parametros = Parametro::all();
        return view('parametros.index', compact('parametros'));
    }

    public function create()
    {
        return view('parametros.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'valor' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Parametro::create($request->all());

        return redirect()->route('parametros.index');
    }

    public function show(Parametro $parametro)
    {
        return view('parametros.show', compact('parametro'));
    }

    public function edit(Parametro $parametro)
    {
        return view('parametros.edit', compact('parametro'));
    }

    public function update(Request $request, Parametro $parametro)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'valor' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $parametro->update($request->all());

        return redirect()->route('parametros.index');
    }

    public function destroy(Parametro $parametro)
    {
        $parametro->delete();

        return redirect()->route('parametros.index');
    }
}