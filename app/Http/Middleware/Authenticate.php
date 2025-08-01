<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // Si c'est une route admin, rediriger vers la connexion admin
            if ($request->is('admin*')) {
                return route('admin.auth.login');
            }

            // Sinon, rediriger vers la connexion client
            return route('auth.login');
        }

        return null;
    }
}
