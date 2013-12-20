<?php namespace Orchestra\Support;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator as V;
use Illuminate\Support\Fluent;

abstract class Validator
{
    /**
     * Laravel validator instance.
     *
     * @var \Illuminate\Validation\Validator
     */
    protected $resolver;

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
    protected $queued = null;

    /**
     * Create a scope scenario.
     *
     * @param  string   $scenario
     * @param  array    $parameters
     * @return Validator
     */
    public function on($scenario, array $parameters = array())
    {
        $method = 'on'.ucfirst($scenario);

        if (method_exists($this, $method)) {
            $this->queued = array('method' => $method, 'parameters' => $parameters);
        } else {
            $this->queued = null;
        }

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
     * @return \Illuminate\Validation\Validator
     */
    public function with(array $input, $events = array())
    {
        is_array($events) or $events = (array) $events;
        $rules = $this->runValidationEvents($events);

        $this->resolver = V::make($input, $rules);

        if (! is_null($this->queued)) {
            call_user_func_array(
                array($this, $this->queued['method']),
                $this->queued['parameters']
            );
        }

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
     * Run validation events and return the finalize rules.
     *
     * @param  array   $events
     * @return array
     */
    protected function runValidationEvents(array $events)
    {
        // Merge all the events.
        $events = array_merge($this->events, $events);

        // Convert rules array to Fluent, in order to pass it by references
        // in all event listening to this validation.
        $rules = new Fluent($this->getBindedRules());

        foreach ((array) $events as $event) {
            Event::fire($event, array(& $rules));
        }

        return $rules->getAttributes();
    }
}
