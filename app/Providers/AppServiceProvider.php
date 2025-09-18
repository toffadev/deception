<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Publication;
use App\Observers\PublicationObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || request()->isSecure()) {
            URL::forceScheme('https');
        }
        // Force the correct domain and scheme
        URL::forceRootUrl(config('app.url'));
        // Enregistrer les observers
        Publication::observe(PublicationObserver::class);

        // Partager les données d'authentification avec toutes les pages Inertia
        Inertia::share([
            'auth' => function () {
                return [
                    'user' => Auth::user() ? [
                        'id' => Auth::user()->id,
                        'pseudo' => Auth::user()->pseudo,
                        'email' => Auth::user()->email,
                        'role' => Auth::user()->role,
                        'avatar_url' => Auth::user()->avatar_url,
                        'display_name' => Auth::user()->display_name,
                    ] : null,
                ];
            },
            'flash' => function () {
                return [
                    'success' => session('success'),
                    'error' => session('error'),
                    'info' => session('info'),
                    'warning' => session('warning'),
                ];
            },
        ]);
    }
}
