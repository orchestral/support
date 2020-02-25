<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Illuminate\Support\Collection detect()
 * @method \Orchestra\Contracts\Extension\Finder finder()
 * @method mixed option(string $name, string $option, mixed $default = null)
 * @method bool permission(string $name)
 * @method void publish(string $name)
 * @method bool register(string $name, string $path)
 * @method \Orchestra\Contracts\Extension\UrlGenerator route(string $name, string $default = '/')
 *
 * @see \Orchestra\Extension\Factory
 */
class Extension extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.extension';
    }
}
