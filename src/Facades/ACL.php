<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Orchestra\Contracts\Authorization\Authorization make(string $name = null, ?Provider $memory = null)
 * @method \Orchestra\Contracts\Authorization\Authorization register(string $name, ?callable $callback = null)
 * @method \Orchestra\Authorization\Factory finish()
 * @method array all()
 * @method \Orchestra\Contracts\Authorization\Authorization|null get(string $name)
 *
 * @see \Orchestra\Authorization\Factory
 */
class ACL extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.acl';
    }
}
