<?php namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @deprecated since 3.2.x
 */
class Resources extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.resources';
    }
}
