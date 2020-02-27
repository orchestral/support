<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Illuminate\Contracts\Support\Htmlable create(string $tag = 'div', mixed $value = null, array $attributes = [])
 * @method \Illuminate\Contracts\Support\Htmlable raw(string $value)
 * @method string attributable(array $attributes, array $defaults = [])
 * @method array decorate(array $attributes, array $defaults = [])
 *
 * @see \Orchestra\Html\HtmlBuilder
 */
class HTML extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'html';
    }
}
