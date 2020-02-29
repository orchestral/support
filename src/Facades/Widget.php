<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method mixed add(string $id, string|\Closure $location = 'parent', \Closure|null $callback = null)
 * @method bool has(string $id)
 * @method mixed is(?string $id)
 * @method \Illuminate\Support\Collection items()
 * @method string toJson(int $options = 0)
 * @method int count()
 * @method bool isEmpty()
 * @method \Orchestra\Widget\Handler of(string|\Closure $name, \Closure|null $callback = null)
 *
 * @see \Orchestra\Widget\WidgetManager
 */
class Widget extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.widget';
    }
}
