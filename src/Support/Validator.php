<?php namespace Orchestra\Support;

use Orchestra\Support\Traits\ValidationTrait;

abstract class Validator
{
    use ValidationTrait;

    /**
     * List of rules.
     *
     * @var array
     */
    protected $rules = array();

    /**
     * List of events.
     *
     * @var array
     */
    protected $events = array();

    /**
     * List of phrases.
     *
     * @var array
     */
    protected $phrases = array();

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
     * @param  string|array    $events
     * @param  array           $phrases
     * @return \Illuminate\Validation\Validator
     */
    public function with(array $input, $events = array(), array $phrases = array())
    {
        return $this->runValidation($input, $events, $phrases);
    }

    /**
     * Get validation events.
     *
     * @return array
     */
    public function getValidationEvents()
    {
        return $this->events;
    }

    /**
     * Get validation phrases.
     *
     * @return array
     */
    public function getValidationPhrases()
    {
        return $this->phrases;
    }

    /**
     * Get validation rules.
     *
     * @return array
     */
    public function getValidationRules()
    {
        return $this->rules;
    }
}
