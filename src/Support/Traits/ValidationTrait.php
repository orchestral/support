<?php namespace Orchestra\Support\Traits;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator as V;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;
use Orchestra\Support\Str;

trait ValidationTrait
{
    /**
     * Laravel validator instance.
     *
     * @var \Illuminate\Validation\Validator
     */
    protected $validationResolver;

    /**
     * List of phrases.
     *
     * @var array
     */
    protected $phrases = [];

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
    protected $validationEvents = [];

    /**
     * List of bindings.
     *
     * @var array
     */
    protected $validationBindings = [];

    /**
     * Scenario queues.
     *
     * @var array
     */
    protected $validationScenarios = [
        'on'         => null,
        'extend'     => null,
        'parameters' => [],
    ];

    /**
     * Create a scope scenario.
     *
     * @param  string   $scenario
     * @param  array    $parameters
     * @return ValidationTrait
     */
    public function onValidationScenario($scenario, array $parameters = [])
    {
        $on     = 'on'.ucfirst($scenario);
        $extend = 'extend'.ucfirst($scenario);

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
     * @param  array    $bindings
     * @return ValidationTrait
     */
    public function bindToValidation(array $bindings)
    {
        $this->validationBindings = array_merge($this->validationBindings, $bindings);

        return $this;
    }

    /**
     * Execute validation service.
     *
     * @param  array           $input
     * @param  string|array    $event
     * @param  array           $phrases
     * @return \Illuminate\Validation\Validator
     */
    public function runValidation(array $input, $events = [], array $phrases = [])
    {
        $this->runQueuedOn();

        list($rules, $phrases) = $this->runValidationEvents($events, $phrases);

        $this->validationResolver = V::make($input, $rules, $phrases);

        $this->runQueuedExtend($this->validationResolver);

        return $this->validationResolver;
    }

    /**
     * Run rules bindings.
     *
     * @return array
     */
    protected function getBindedRules()
    {
        $rules = $this->rules;

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
            call_user_func_array(array($this, $method), $this->validationScenarios['parameters']);
        }
    }

    /**
     * Run queued extend scenario.
     *
     * @param  \Illuminate\Validation\Validator    $validationResolver
     * @return void
     */
    protected function runQueuedExtend(Validator $validationResolver)
    {
        if (! is_null($method = $this->validationScenarios['extend'])) {
            call_user_func(array($this, $method), $validationResolver);
        }
    }

    /**
     * Run validation events and return the finalize rules and phrases.
     *
     * @param  array|string    $events
     * @param  array           $phrases
     * @return array
     */
    protected function runValidationEvents($events, array $phrases)
    {
        is_array($events) || $events = (array) $events;

        // Merge all the events.
        $events = array_merge($this->validationEvents, $events);

        // Convert rules array to Fluent, in order to pass it by references
        // in all event listening to this validation.
        $rules   = new Fluent($this->getBindedRules());
        $phrases = new Fluent(array_merge($this->phrases, $phrases));

        foreach ((array) $events as $event) {
            Event::fire($event, [& $rules, & $phrases]);
        }

        return [
            $rules->getAttributes(),
            $phrases->getAttributes(),
        ];
    }
}
