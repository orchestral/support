<?php namespace Orchestra\Support\Providers;

use Orchestra\Contracts\Config\PackageRepository;
use Orchestra\Support\Providers\Traits\PackageProviderTrait;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    use PackageProviderTrait;

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        parent::mergeConfigFrom($path, $key);

        $config = $this->app['config'];

        if ($config instanceof PackageRepository) {
            $config->file($key);
        }
    }
}
