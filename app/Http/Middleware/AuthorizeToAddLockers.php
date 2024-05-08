<?php

namespace App\Http\Middleware;

use Closure;

class AuthorizeToAddLockers
{
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated and has the admin role
        if ($request->user() && $request->user()->hasRole('admin')) {
            return $next($request);
        }

        // If not authorized, return a 403 Forbidden response
        abort(403, 'Unauthorized access.');
    }
}
