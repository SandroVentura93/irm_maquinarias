<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  mixed  ...$roles IDs de roles permitidos
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Permitir acceso total al administrador (id_rol == 1)
        if (Auth::user()->id_rol == 1) {
            return $next($request);
        }

        // Convertir roles a array de integers
        $allowedRoles = array_map('intval', $roles);

        // Verificar si el usuario tiene uno de los roles permitidos
        if (!in_array(Auth::user()->id_rol, $allowedRoles)) {
            abort(403, 'No tienes permiso para acceder a este módulo.');
        }

        return $next($request);
    }
}
