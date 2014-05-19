<?php namespace Orchestra\Support;

use Orchestra\Support\Traits\ValidationTrait;

abstract class Validator
{
    use ValidationTrait;

    /**
     * Create a scope scenario.
     *
     * @param  string   $scenario
     * @param  array    $parameters
     * @return Validator
     */
    public function on($scenario, array $parameters = array())
    {
        return $this->onValidationScenario($scenario, $parameters);
    }

    /**
     * Add bindings.
     *
     * @param  array    $bindings
     * @return Validator
     */
    public function bind(array $bindings)
    {
        return $this->bindToValidation($bindings);
    }

    /**
     * Execute validation service.
     *
     * @param  array           $input
     * @param  string|array    $event
     * @param  array           $phrases
     * @return \Illuminate\Validation\Validator
     */
    public function with(array $input, $events = array(), array $phrases = array())
    {
        return $this->runValidation($input, $events, $phrases);
    }
}
