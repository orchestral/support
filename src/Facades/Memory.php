<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method mixed get(string $key, mixed $default = null)
 * @method bool has(string $key)
 * @method mixed put(string $key, mixed $value = '')
 * @method bool forget(string $key)
 * @method \Orchestra\Contracts\Memory\Provider makeOrFallback(string $fallbackName = 'orchestra')
 * @method void finish()
 *
 * @see \Orchestra\Memory\MemoryManager
 */
class Memory extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.memory';
    }
}
