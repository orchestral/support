<?php namespace Orchestra\Support\Providers;

use Orchestra\Support\Providers\Traits\PackageProviderTrait;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    use PackageProviderTrait;

    /**
     * Boot under Laravel setup.
     *
     * @param  string  $path
     * @return void
     */
    protected function bootUsingLaravel($path)
    {
        //
    }
}
