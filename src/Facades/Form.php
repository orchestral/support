<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Orchestra\Contracts\Html\Builder make(callable $callback = null)
 * @method \Orchestra\Contracts\Html\Builder of(string $name, callable $callback = null)
 * @method \Orchestra\Html\Form\Factory setConfig(array $config)
 *
 * @see \Orchestra\Html\Form\Factory
 */
class Form extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.form';
    }
}
