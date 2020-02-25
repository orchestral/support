<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Orchestra\Foundation\Foundation boot()
 * @method \Orchestra\Contracts\Authorization\Authorization|null acl()
 * @method \Orchestra\Contracts\Memory\Provider|null memory()
 * @method \Orchestra\Widget\Handlers\Menu|null menu()
 * @method \Orchestra\Widget\Handler|null widget(string $type)
 * @method void namespaced(?string $namespace, array|null $attributes = [], \Closure|null $callback = null)
 * @method \Orchestra\Contracts\Extension\UrlGenerator route(string $name, string $default = '/')
 * @method bool installed()
 * @method void flush()
 *
 * @see \Orchestra\Foundation\Foundation
 */
class Foundation extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.app';
    }
}
