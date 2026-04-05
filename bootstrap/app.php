<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        $middleware->redirectGuestsTo(fn () => route('home', ['auth_trigger' => 'login']));

        // CORRECTION : On s'assure que la redirection ne boucle pas
        $middleware->redirectTo(
            users: function (Request $request) {
                if ($request->user()) {
                    if ($request->user()->role === 'restaurateur') {
                        return route('vendor.dashboard');
                    }
                    // Pour un client, on redirige vers le feed plutôt que le dashboard vide
                    return route('video.feed');
                }
                return route('home');
            }
        );

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        $middleware->validateCsrfTokens(except: []);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();