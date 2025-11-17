<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ErrorHandlingMiddleware
{
    /**
     * ⚡ Middleware para manejo mejorado de errores
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);
            
            // Log de requests exitosos (solo para APIs críticas)
            if ($request->is('api/ventas/*') || $request->is('api/pdf/*')) {
                Log::info('API Request Success', [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip(),
                    'status' => $response->getStatusCode(),
                    'response_time_ms' => round((microtime(true) - LARAVEL_START) * 1000, 2)
                ]);
            }
            
            return $response;
            
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database Error', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);
            
            return $this->formatErrorResponse(
                'Error de base de datos. Por favor contacte al administrador.',
                500,
                $request->expectsJson()
            );
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation Error', [
                'url' => $request->fullUrl(),
                'errors' => $e->errors(),
                'input' => $request->except(['password', 'password_confirmation', '_token'])
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'error' => 'Datos inválidos',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
            Log::info('404 Error', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);
            
            return $this->formatErrorResponse(
                'Recurso no encontrado.',
                404,
                $request->expectsJson()
            );
            
        } catch (\Exception $e) {
            Log::error('General Error', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'ip' => $request->ip()
            ]);
            
            return $this->formatErrorResponse(
                'Error interno del servidor. Por favor intente nuevamente.',
                500,
                $request->expectsJson()
            );
        }
    }
    
    /**
     * Formatear respuesta de error según el tipo de request
     */
    private function formatErrorResponse(string $message, int $code, bool $expectsJson)
    {
        if ($expectsJson) {
            return response()->json([
                'ok' => false,
                'error' => $message,
                'code' => $code,
                'timestamp' => now()->toISOString()
            ], $code);
        }
        
        return response()->view('errors.custom', [
            'message' => $message,
            'code' => $code
        ], $code);
    }
}