<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if the authenticated user has the superadmin role
        if (!auth()->user()->hasRole('superadmin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // If the user is authenticated and has the superadmin role, proceed with the request
        return $next($request);
    }
}
