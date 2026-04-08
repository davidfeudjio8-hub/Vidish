<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;

class VoltServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Dis à Volt de chercher les composants dans ce dossier
        Volt::mount([
            resource_path('views/livewire'),
            resource_path('views/pages'), // Optionnel, si tu utilises des pages Volt
        ]);
    }
}
