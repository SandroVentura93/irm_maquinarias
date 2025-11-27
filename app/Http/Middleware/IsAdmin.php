<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->id_rol === 1) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Acceso denegado. Solo los administradores pueden acceder a esta sección.');
    }
}