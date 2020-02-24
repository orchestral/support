<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Orchestra\Asset\Asset container(string $container = 'default')
 * @method \Orchestra\Asset\Asset addVersioning()
 * @method \Orchestra\Asset\Asset removeVersioning()
 * @method \Orchestra\Asset\Asset prefix(?string $path = null)
 * @method \Orchestra\Asset\Asset add(string|array $name, string $source, string|array $dependencies = [], string|array $attributes = [], string|array $replaces = [])
 * @method \Orchestra\Asset\Asset style(string|array $name, string $source, string|array $dependencies = [], string|array $attributes = [], string|array $replaces = [])
 * @method \Orchestra\Asset\Asset script(string|array $name, string $source, string|array $dependencies = [], string|array $attributes = [], string|array $replaces = [])
 * @method string styles()
 * @method string scripts()
 * @method string show()
 * @method string toHtml()
 *
 * @see \Orchestra\Asset\Factory
 */
class Asset extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.asset';
    }
}
