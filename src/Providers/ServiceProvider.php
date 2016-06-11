<?php

namespace Orchestra\Support\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    use Traits\PackageProvider;

     /**
     * Register a database migration path.
     *
     * @param  array|string  $paths
     * @return void
     */
    protected function loadMigrationsFrom($paths)
    {
        if ($this->app->bound('migrator')) {
            parent::loadMigrationsFrom($paths);
        }
    }
}
