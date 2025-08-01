<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('auth.login');
        }

        if (auth()->user()->role !== $role) {
            if ($role === 'admin') {
                return redirect()->route('admin.auth.login')->with('error', 'Accès réservé aux administrateurs.');
            }

            return redirect()->route('client.home')->with('error', 'Accès non autorisé.');
        }

        return $next($request);
    }
}
