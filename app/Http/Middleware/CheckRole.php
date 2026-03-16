<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next, string $role): Response
{
    // On vérifie si l'utilisateur est connecté et si son rôle correspond
    if (!$request->user() || $request->user()->role !== $role) {
        // Si ce n'est pas le bon rôle, on redirige vers l'accueil ou on renvoie une erreur 403
        abort(403, "Accès non autorisé. Cette section est réservée aux restaurateurs.");
    }

    return $next($request);
}
}
