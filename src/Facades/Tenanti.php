<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method void install(?string $datanase, mixed $id = null)
 * @method void run(?string $database, mixed $id = null, bool $pretend = false)
 * @method void rollback(?string $database, mixed $id = null, bool $pretend = false)
 * @method void reset(?string $database, mixed $id = null, bool $pretend = false)
 * @method void runUp(\Illuminate\Database\Eloquent\Model $entity, ?string $database, bool $pretend = false)
 * @method void runDown(\Illuminate\Database\Eloquent\Model $entity, ?string $database, bool $pretend = false)
 * @method void runReset(\Illuminate\Database\Eloquent\Model $entity, ?string $database, bool $pretend = false)
 * @method void connection(?string $using, \Closure $callback, array $options = [])
 * @method \Orchestra\Tenanti\TenantiManager setConfiguration(array $config)
 * @method array getConfiguration()
 * @method mixed config(?string $group = null, mixed $default = null)
 *
 * @see \Orchestra\Tenanti\TenantiManager
 */
class Tenanti extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.tenanti';
    }
}
