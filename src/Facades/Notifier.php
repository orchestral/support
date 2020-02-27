<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Orchestra\Contracts\Notification\Receipt send(\Orchestra\Contracts\Notification\Recipient $user, \Orchestra\Contracts\Notification\Message $message, \Closure $callback = null)
 *
 * @see \Orchestra\Notifier\NotifierManager
 */
class Notifier extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.notifier';
    }
}
