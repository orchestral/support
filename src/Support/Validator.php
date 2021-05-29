<?php

namespace Orchestra\Support;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;

abstract class Validator
{
    use Concerns\Validation;

    /**
     * List of rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * List of events.
     *
     * @var array
     */
    protected $events = [];

    /**
     * List of local events.
     *
     * @var array
     */
    protected $localEvents = [];

    /**
     * List of phrases.
     *
     * @var array
     */
    protected $phrases = [];

    /**
     * Set state.
     *
     * @param  string  $scenario
     * @param  array   $parameters
     *
     * @return $this
     */
    public function state(string $scenario, array $parameters = [])
    {
        return $this->onValidationScenario($scenario, $parameters);
    }

    /**
     * Add bindings.
     *
     * @param  array  $bindings
     *
     * @return $this
     */
    public function bind(array $bindings)
    {
        return $this->bindToValidation($bindings);
    }

    /**
     * Listen to events.
     *
     * @param  string|array  $events
     *
     * @return $this
     */
    public function listen($events)
    {
        $this->localEvents = array_merge($this->localEvents, Arr::wrap($events));

        return $this;
    }

    /**
     * Execute validation service.
     *
     * @param  \Illuminate\Http\Request|array  $input
     * @param  array  $phrases
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validate($input, array $phrases = []): ValidatorContract
    {
        return $this->runValidation($input, $phrases, $this->localEvents);
    }

    /**
     * Get validation events.
     *
     * @return array
     */
    public function getValidationEvents(): array
    {
        return $this->events;
    }

    /**
     * Get validation phrases.
     *
     * @return array
     */
    public function getValidationPhrases(): array
    {
        return $this->phrases;
    }

    /**
     * Get validation rules.
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return $this->rules;
    }
}
