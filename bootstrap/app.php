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
    ->withMiddleware(function (Middleware $middleware){
        // This tells Laravel where to redirect authenticated users
        $middleware->redirectTo(
            guests: '/login',      // Where to go if not logged in
            users: '/dashboard'    // Where to go if already logged in (The "HOME" replacement)
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
