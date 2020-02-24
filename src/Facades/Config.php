<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method bool map(string $name, array $aliases)
 *
 * @see \Orchestra\Extension\Config\Repository
 */
class Config extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.extension.config';
    }
}
