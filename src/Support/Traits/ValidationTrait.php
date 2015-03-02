<?php namespace Orchestra\Support\Traits;

use Orchestra\Support\Str;
use Illuminate\Support\Fluent;
use Illuminate\Contracts\Validation\Validator;

trait ValidationTrait
{
    /**
     * The validation factory implementation.
     *
     * @var \Illuminate\Contracts\Validation\Factory
     */
    protected $validationFactory;

    /**
     * The event dispatcher implementation.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $validationDispatcher;

    /**
     * List of bindings.
     *
     * @var array
     */
    protected $validationBindings = [];

    /**
     * Validation scenario configuration.
     *
     * @var array
     */
    protected $validationScenarios;

    /**
     * Create a scope scenario.
     *
     * @param  string  $scenario
     * @param  array   $parameters
     *
     * @return $this
     */
    public function onValidationScenario($scenario, array $parameters = [])
    {
        list($on, $extend) = $this->getValidationSchemasName($scenario);

        $this->validationScenarios = [
            'on'         => method_exists($this, $on) ? $on : null,
            'extend'     => method_exists($this, $extend) ? $extend : null,
            'parameters' => $parameters,
        ];

        return $this;
    }

    /**
     * Add bindings.
     *
     * @param  array  $bindings
     *
     * @return $this
     */
    public function bindToValidation(array $bindings)
    {
        $this->validationBindings = array_merge($this->validationBindings, $bindings);

        return $this;
    }

    /**
     * Execute validation service.
     *
     * @param  array  $input
     * @param  string|array  $events
     * @param  array  $phrases
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function runValidation(array $input, $events = [], array $phrases = [])
    {
        if (is_null($this->validationScenarios)) {
            $this->onValidationScenario('any');
        }

        $this->runQueuedOn();

        list($rules, $phrases) = $this->runValidationEvents($events, $phrases);

        $validationResolver = $this->validationFactory->make($input, $rules, $phrases);

        $this->runQueuedExtend($validationResolver);

        return $validationResolver;
    }

    /**
     * Run rules bindings.
     *
     * @return array
     */
    protected function getBindedRules()
    {
        $rules = $this->getValidationRules();

        if (! empty($this->validationBindings)) {
            foreach ($rules as $key => $value) {
                $rules[$key] = Str::replace($value, $this->validationBindings);
            }
        }

        return $rules;
    }

    /**
     * Run queued on scenario.
     *
     * @return void
     */
    protected function runQueuedOn()
    {
        if (! is_null($method = $this->validationScenarios['on'])) {
            call_user_func_array([$this, $method], $this->validationScenarios['parameters']);
        }
    }

    /**
     * Run queued extend scenario.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validationResolver
     *
     * @return void
     */
    protected function runQueuedExtend(Validator $validationResolver)
    {
        if (! is_null($method = $this->validationScenarios['extend'])) {
            call_user_func([$this, $method], $validationResolver);
        }
    }

    /**
     * Run validation events and return the finalize rules and phrases.
     *
     * @param  array|string  $events
     * @param  array  $phrases
     *
     * @return array
     */
    protected function runValidationEvents($events, array $phrases)
    {
        is_array($events) || $events = (array) $events;

        // Merge all the events.
        $events = array_merge($this->getValidationEvents(), $events);

        // Convert rules array to Fluent, in order to pass it by references
        // in all event listening to this validation.
        $rules   = new Fluent($this->getBindedRules());
        $phrases = new Fluent(array_merge($this->getValidationPhrases(), $phrases));

        foreach ((array) $events as $event) {
            $this->validationDispatcher->fire($event, [& $rules, & $phrases]);
        }

        return [
            $rules->getAttributes(),
            $phrases->getAttributes(),
        ];
    }

    /**
     * Get validation events.
     *
     * @return array
     */
    public function getValidationEvents()
    {
        return [];
    }

    /**
     * Get validation phrases.
     *
     * @return array
     */
    public function getValidationPhrases()
    {
        return [];
    }

    /**
     * Get validation rules.
     *
     * @return array
     */
    public function getValidationRules()
    {
        return [];
    }

    /**
     * Get validation schemas name.
     *
     * @param  string  $scenario
     *
     * @return array
     */
    protected function getValidationSchemasName($scenario)
    {
        $on     = 'on'.ucfirst($scenario);
        $extend = 'extend'.ucfirst($scenario);

        return [$on, $extend];
    }
}
