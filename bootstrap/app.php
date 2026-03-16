<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Correctly chain the methods without the semicolon or extra parenthesis
        $middleware->redirectTo(
    guests: fn () => route('login'),
    users: function ($request) {
        if ($request->user()->role === 'restaurateur') {
            return route('vendor.dashboard');
        }
        return route('dashboard');
    }
);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();