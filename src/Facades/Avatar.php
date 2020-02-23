<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Orchestra\Avatar\Contracts\Handler make($identifier)
 * @method \Orchestra\Avatar\Contracts\Handler user($user)
 * @method \Orchestra\Avatar\Contracts\Handler getHandler()
 *
 * @see \Orchestra\Avatar\AvatarManager
 */
class Avatar extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.avatar';
    }
}
