<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Return null for API requests to prevent redirect
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }

        return route('login');
    }

    /**
     * Handle an unauthenticated user (for API requests)
     */
    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            abort(response()->json([
                'message' => 'Unauthenticated. Please provide a valid access token.',
                'error' => 'unauthorized'
            ], 401));
        }

        parent::unauthenticated($request, $guards);
    }
}