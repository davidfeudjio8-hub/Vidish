<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // On récupère les plats du restaurant de l'utilisateur connecté
        // (En supposant que ton modèle User a une relation 'restaurant')
        $restaurant = Auth::user()->restaurant;
        $dishes = $restaurant ? $restaurant->dishes : collect();

        return view('vendor.dashboard', compact('dishes'));
    }
}
