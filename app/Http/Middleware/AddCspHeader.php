<?php

namespace App\Http\Middleware;

use Closure;

class AddCspHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('Content-Security-Policy', "script-src 'self' https://conoret.com;");
        return $response;
    }
}