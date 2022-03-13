<?php

namespace Orchestra\Support\Providers;

use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    use Concerns\PackageProvider;

    /**
     * Register Eloquent model factory paths.
     *
     * @param  array|string  $paths
     *
     * @return void
     */
    protected function loadFactoriesFrom($paths)
    {
        if (method_exists($this->app, 'runningUnitTests') && $this->app->runningUnitTests()) {
            $this->callAfterResolving(EloquentFactory::class, function ($factory) use ($paths) {
                foreach ((array) $paths as $path) {
                    $factory->load($path);
                }
            });
        }
    }
}
