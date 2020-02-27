<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method bool extension(string $name)
 * @method bool foundation()
 * @method \Orchestra\Foundation\Publisher\PublisherManager setDefaultDriver(string $driver)
 * @method bool connected()
 * @method bool execute()
 * @method bool queue(string|array $queue)
 * @method array queued()
 *
 * @see \Orchestra\Foundation\Publisher\PublisherManager
 */
class Publisher extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.publisher';
    }
}
