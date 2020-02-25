<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Illuminate\Contracts\Support\MessageBag extend(\Closure $callback)
 * @method \Illuminate\Contracts\Support\MessageBag copy()
 * @method void save()
 * @method \Orchestra\Messages\MessageBag setSessionStore(\Illuminate\Contracts\Session\Session $session)
 * @method \Illuminate\Contracts\Session\Session getSessionStore()
 *
 * @see \Orchestra\Messages\MessageBag
 */
class Messages extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.messages';
    }
}
