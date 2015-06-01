<?php namespace Orchestra\Support\Providers;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Orchestra\Support\Providers\Traits\MiddlewareProviderTrait;

abstract class MiddlewareServiceProvider extends ServiceProvider
{
    use MiddlewareProviderTrait;

    /**
     * The application's middleware stack.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [];

    /**
     * Bootstrap the application events.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  \Illuminate\Contracts\Http\Kernel  $kernel
     *
     * @return void
     */
    public function boot(Router $router, Kernel $kernel)
    {
        $this->registerRouteMiddleware($router, $kernel);
    }
}
