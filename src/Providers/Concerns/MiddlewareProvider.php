<?php

namespace Orchestra\Support\Providers\Concerns;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;

trait MiddlewareProvider
{
    /**
     * Register route middleware.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  \Illuminate\Contracts\Http\Kernel  $kernel
     *
     * @return void
     */
    protected function registerRouteMiddleware(Router $router, Kernel $kernel): void
    {
        foreach ((array) $this->middleware as $middleware) {
            $kernel->pushMiddleware($middleware);
        }

        foreach ((array) $this->middlewareGroups as $key => $middleware) {
            $router->middlewareGroup($key, $middleware);
        }

        foreach ((array) $this->routeMiddleware as $key => $middleware) {
            $router->aliasMiddleware($key, $middleware);
        }
    }
}
