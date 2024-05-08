<?php
namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

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
// Other middleware...
],
];

protected $middlewarePriority = [
// Other middleware...
];

protected $routeMiddleware = [
// Other middleware...
'auth' => \App\Http\Middleware\Authenticate::class,
'check.access' => \App\Http\Middleware\CheckPermittedAccess::class,
];
}
