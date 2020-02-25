<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method mixed get(string $key, mixed $default = null)
 * @method mixed secureGet(string $key, mixed $default = null)
 * @method mixed set(string $key, mixed $value = null)
 * @method mixed secureSet(string $key, mixed $value = null)
 * @method bool has(string $key)
 * @method bool forget(string $key)
 * @method array all()
 *
 * @see \Orchestra\Foundation\Meta
 */
class Meta extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.meta';
    }
}
