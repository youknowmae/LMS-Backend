<?php

namespace App\Http;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\AuthorizeToAddLockers;
use App\Http\Middleware\CheckAccess;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Other middleware...
    ];

    protected $middlewareGroups = [
        'web' => [
            // Other middleware...
        ],

        'api' => [
            EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            SubstituteBindings::class,
        ],
    ];

    protected $middlewarePriority = [
        // Other middleware...
    ];

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'check.access' => \App\Http\Middleware\CheckAccess::class,
        'authorizeToAddLockers' => \App\Http\Middleware\AuthorizeToAddLockers::class,

    ];
}
