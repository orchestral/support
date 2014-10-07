<?php namespace Orchestra\Support;

use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator as V;

abstract class Validator
{
    /**
     * Laravel validator instance.
     *
     * @var \Illuminate\Validation\Validator
     */
    protected $resolver;

    /**
     * List of phrases.
     *
     * @var array
     */
    protected $phrases = array();

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
     * List of bindings.
     *
     * @var array
     */
    protected $bindings = array();

    /**
     * Scenario queues.
     *
     * @var array
     */
    protected $queued = array(
        'on'         => null,
        'extend'     => null,
        'parameters' => array(),
    );

    /**
     * Create a scope scenario.
     *
     * @param  string   $scenario
     * @param  array    $parameters
     * @return Validator
     */
    public function on($scenario, array $parameters = array())
    {
        $on     = 'on'.ucfirst($scenario);
        $extend = 'extend'.ucfirst($scenario);

        $this->queued = array(
            'on'         => method_exists($this, $on) ? $on : null,
            'extend'     => method_exists($this, $extend) ? $extend : null,
            'parameters' => $parameters,
        );

        return $this;
    }

    /**
     * Add bindings.
     *
     * @param  array    $bindings
     * @return Validator
     */
    public function bind(array $bindings)
    {
        $this->bindings = array_merge($this->bindings, $bindings);

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
    public function with(array $input, $events = array(), array $phrases = array())
    {
        $this->runQueuedOn();

        list($rules, $phrases) = $this->runValidationEvents($events, $phrases);

        $this->resolver = V::make($input, $rules, $phrases);

        $this->runQueuedExtend($this->resolver);

        return $this->resolver;
    }

    /**
     * Run rules bindings.
     *
     * @return array
     */
    protected function getBindedRules()
    {
        $rules = $this->rules;

        if (! empty($this->bindings)) {
            foreach ($rules as $key => $value) {
                $rules[$key] = Str::replace($value, $this->bindings);
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
        if (! is_null($method = $this->queued['on'])) {
            call_user_func_array(array($this, $method), $this->queued['parameters']);
        }
    }

    /**
     * Run queued extend scenario.
     *
     * @param  \Illuminate\Validation\Validator    $resolver
     * @return void
     */
    protected function runQueuedExtend($resolver)
    {
        if (! is_null($method = $this->queued['extend'])) {
            call_user_func(array($this, $method), $resolver);
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
        $events = array_merge($this->events, $events);

        // Convert rules array to Fluent, in order to pass it by references
        // in all event listening to this validation.
        $rules   = new Fluent($this->getBindedRules());
        $phrases = new Fluent(array_merge($this->phrases, $phrases));

        foreach ((array) $events as $event) {
            Event::fire($event, array(& $rules, & $phrases));
        }

        return array(
            $rules->getAttributes(),
            $phrases->getAttributes(),
        );
    }
}
