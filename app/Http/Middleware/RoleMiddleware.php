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

        // Normalizar parámetros: aceptar tanto múltiples parámetros como
        // un único parámetro con comas (ej. '1,2,3').
        $allowedRoles = [];
        foreach ($roles as $r) {
            // Separar por comas y limpiar espacios
            $parts = array_filter(array_map('trim', explode(',', $r)), function($v) { return $v !== ''; });
            foreach ($parts as $p) {
                $allowedRoles[] = intval($p);
            }
        }

        // Verificar si el usuario tiene uno de los roles permitidos
        if (!in_array((int) Auth::user()->id_rol, $allowedRoles)) {
            abort(403, 'No tienes permiso para acceder a este módulo.');
        }

        return $next($request);
    }
}
