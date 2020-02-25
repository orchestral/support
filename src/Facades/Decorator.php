<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method void macro(string $name, callable $macro)
 * @method string render(string $name, ...$parameters)
 *
 * @see \Orchestra\View\Decorator
 */
class Decorator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.decorator';
    }
}
