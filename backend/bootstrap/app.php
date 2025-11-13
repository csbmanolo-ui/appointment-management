<?php

use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: base_path('routes/api.php'),
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Personaliza la redirección para usuarios no autenticados
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('api/*')) {
                return null; // No redirigir para peticiones API
            }
            return route('login');
        });
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
        $middleware->api([
        \App\Http\Middleware\HandleCorsManually::class
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Personaliza la respuesta para excepciones de autenticación
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'No autenticado. Tu token es inválido o ha caducado.'
                ], 401);
            }
        });
    })->create();
