<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleCorsManually
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Primero, permite que la petición continúe (ej. al AuthController)
        $response = $next($request);

        // Luego, añade las cabeceras de CORS a la respuesta ANTES de enviarla
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:4200');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept');

        return $response;
    }
}
