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
        
        // 1. Gestion des redirections automatiques (Fusionnée)
        $middleware->redirectTo(
            // Pour les non-connectés : redirection vers l'accueil avec trigger login
            guests: fn () => route('home', ['auth_trigger' => 'login']), 

            // Pour les connectés : redirection selon le rôle
            users: function (Request $request) {
                if ($request->user() && $request->user()->role === 'restaurateur') {
                    return route('vendor.dashboard');
                }
                return route('dashboard');
            }
        );

        // 2. Définition des alias de Middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();