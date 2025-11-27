<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class LogBitacora
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'accion' => $request->route()->getName(),
                'descripcion' => 'Acción realizada en la ruta: ' . $request->route()->getName(),
            ]);
        }

        return $response;
    }
}