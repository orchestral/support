<?php namespace Orchestra\Support\Providers;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Orchestra\Support\Providers\Traits\FilterProviderTrait;
use Orchestra\Support\Providers\Traits\MiddlewareProviderTrait;

abstract class PipelineServiceProvider extends ServiceProvider
{
    use FilterProviderTrait, MiddlewareProviderTrait;

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
        $this->registerRouteFilters($router);

        $this->registerRouteMiddleware($router, $kernel);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
