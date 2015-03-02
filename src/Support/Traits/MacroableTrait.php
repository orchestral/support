<?php namespace Orchestra\Support\Traits;

trait MacroableTrait
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
    public function macro($name, $macro)
    {
        $this->macros[$name] = $macro;
    }

    /**
     * Dynamically handle calls to the html class.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (isset($this->macros[$method])) {
            return call_user_func_array($this->macros[$method], $parameters);
        }

        throw new \BadMethodCallException("Method {$method} does not exist.");
    }
}
