<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // 1. Redirection pour les Restaurateurs
        if ($request->user()->role === 'restaurateur') {
            return redirect()->intended(route('vendor.dashboard'));
        }

        // 2. Redirection pour les Clients (vers le flux vidéo par défaut)
        // Note : .intended() renvoie l'utilisateur là où il voulait aller, 
        // sinon il utilise la route par défaut spécifiée ici.
        return redirect()->intended(route('video.feed'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}