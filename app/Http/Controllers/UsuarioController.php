<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('rol')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Rol::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_rol' => 'required|exists:roles,id_rol',
            'nombre' => 'required|string|max:100',
            'usuario' => 'required|string|max:50|unique:usuarios,usuario',
            'contrasena' => 'required|string|min:8',
            'correo' => 'nullable|email|max:100',
            'telefono' => 'nullable|string|max:20',
        ]);

        Usuario::create([
            'id_rol' => $request->id_rol,
            'nombre' => $request->nombre,
            'usuario' => $request->usuario,
            'contrasena' => Hash::make($request->contrasena),
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'activo' => $request->has('activo') ? 1 : 0,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(Usuario $usuario)
    {
        $roles = Rol::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'id_rol' => 'required|exists:roles,id_rol',
            'nombre' => 'required|string|max:100',
            'usuario' => 'required|string|max:50|unique:usuarios,usuario,' . $usuario->id_usuario . ',id_usuario',
            'correo' => 'nullable|email|max:100',
            'telefono' => 'nullable|string|max:20',
        ]);

        $data = $request->except('contrasena');
        $data['activo'] = $request->has('activo') ? 1 : 0;
        $usuario->update($data);

        if ($request->filled('contrasena')) {
            $usuario->update(['contrasena' => Hash::make($request->contrasena)]);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    public function show(Usuario $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }
}