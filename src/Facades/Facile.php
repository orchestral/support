<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Orchestra\Facile\Facile make($name, array $data = [], $format = null)
 * @method \Orchestra\Facile\Facile view($view, array $data = [])
 * @method \Orchestra\Facile\Facile with(mixed $data)
 * @method void name(string $name, string|\Orchestra\Facile\Template\Parser $parser)
 * @method mixed resolve($name, $format, array $data, $method = 'compose')
 * @method \Orchestra\Facile\Template\Parser parse(string|\Orchestra\Facile\Template\Parser $name)
 *
 * @see \Orchestra\Facile\Factory
 */
class Facile extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.facile';
    }
}
