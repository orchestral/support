<?php namespace Orchestra\Support\Providers;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;
use Orchestra\Support\Providers\Traits\FilterProviderTrait;

abstract class FilterServiceProvider extends PipelineServiceProvider
{
    use FilterProviderTrait;

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

        parent::boot($router, $kernel);
    }
}
