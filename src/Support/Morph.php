<?php

namespace Orchestra\Support;

use RuntimeException;

/**
 * @deprecated v3.8.0
 */
abstract class Morph
{
    /**
     * Method prefix.
     *
     * @var string
     */
    public static $prefix = '';

    /**
     * Magic method to call passtru PHP functions.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @throws \RuntimeException
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $parameters)
    {
        if (! static::isCallable($method)) {
            throw new RuntimeException("Unable to call [{$method}].");
        }

        $callback = static::$prefix.Str::snake($method);

        return $callback(...$parameters);
    }

    /**
     * Determine if method is callable.
     *
     * @param  string  $method
     *
     * @return bool
     */
    public static function isCallable(string $method): bool
    {
        return \is_callable(static::$prefix.Str::snake($method));
    }
}
