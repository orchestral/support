<?php namespace Orchestra\Support\Providers;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Orchestra\Support\Providers\Traits\MiddlewareProviderTrait;

abstract class PipelineServiceProvider extends ServiceProvider
{
    use MiddlewareProviderTrait;

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
