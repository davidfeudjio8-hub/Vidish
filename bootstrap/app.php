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
        
        // 1. Gestion des redirections pour les invités (non-connectés)
        $middleware->redirectGuestsTo(fn () => route('home', ['auth_trigger' => 'login']));

        // 2. Gestion de la redirection "HOME" (quand un user connecté tente d'aller sur /login ou /register)
        $middleware->redirectTo(
            users: function (Request $request) {
                if ($request->user() && $request->user()->role === 'restaurateur') {
                    return route('vendor.dashboard');
                }
                return route('dashboard');
            }
        );

        // 3. Définition des alias de Middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // 4. Protection contre le "Session Expired" sur les appels AJAX (Optionnel mais recommandé pour ton Toggle)
        $middleware->validateCsrfTokens(except: [
            // 'vendor/status/update', // À décommenter si tu as des soucis de CSRF avec ton bouton AJAX
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();