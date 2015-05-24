<?php namespace Orchestra\Support\Providers\Traits;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;

trait MiddlewareProviderTrait
{
    /**
     * Register route middleware.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  \Illuminate\Contracts\Http\Kernel  $kernel
     *
     * @return void
     */
    protected function registerRouteMiddleware(Router $router, Kernel $kernel)
    {
        foreach ($this->middleware as $middleware) {
            $kernel->pushMiddleware($middleware);
        }

        foreach ($this->routeMiddleware as $key => $middleware) {
            $router->middleware($key, $middleware);
        }
    }
}
