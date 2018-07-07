<?php

namespace Orchestra\Support\Concerns;

use BadMethodCallException;

trait Macroable
{
    /**
     * The registered html macros.
     *
     * @var array
     */
    protected $macros;

    /**
     * Register a custom HTML macro.
     *
     * @param  string  $name
     * @param  callable  $macro
     *
     * @return void
     */
    public function macro(string $name, callable $macro): void
    {
        $this->macros[$name] = $macro;
    }

    /**
     * Dynamically handle calls to the html class.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @throws \BadMethodCallException
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        if (isset($this->macros[$method])) {
            return $this->macros[$method](...$parameters);
        }

        throw new BadMethodCallException("Method {$method} does not exist.");
    }
}
