<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

class PermissionMiddlewareProvider extends ServiceProvider
{
    public function boot(Router $router): void
    {
        // spatie middlewares
        $router->aliasMiddleware('role', RoleMiddleware::class);
        $router->aliasMiddleware('permission', PermissionMiddleware::class);
        $router->aliasMiddleware('role_or_permission', RoleOrPermissionMiddleware::class);
    }
}

