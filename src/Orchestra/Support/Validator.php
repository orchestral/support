<?php namespace Orchestra\Support;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator as V;
use Illuminate\Support\Fluent;

abstract class Validator {
	
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
	 * Create a new instance.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->setRules($this->rules);
	}

	/**
	 * Create a scope scenario.
	 *
	 * @access public
	 * @param  string   $scenario
	 * @param  array    $parameters
	 * @return self
	 */
	public function on($scenario, $parameters = array())
	{
		$method = 'on'.ucfirst($scenario);

		if (method_exists($this, $method)) 
		{
			call_user_func_array(array($this, $method), $parameters);
		}

		return $this;
	}

	/**
	 * Add bindings.
	 *
	 * @access public
	 * @param  array    $bindings
	 * @return self
	 */
	public function bind($bindings)
	{
		$this->bindings = array_merge($this->bindings, $bindings);

		return $this;
	}

	/**
	 * Execute validation service.
	 *
	 * @access public
	 * @param  array    $input
	 * @param  string   $event
	 * @return \Illuminate\Validation\Factory
	 */
	public function with($input, $events = array())
	{
		$rules = $this->runValidationEvents($events);

		return V::make($input, $rules);
	}

	/**
	 * Run rules bindings.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getBindedRules()
	{
		$rules    = $this->rules;
		$bindings = $this->prepareBindings('{', '}');

		$filter = function ( & $value, $key, $bindings)
		{
			$value = strtr($value, $bindings);
		};

		foreach ($rules as $key => $value)
		{
			if (is_array($value)) array_walk($value, $filter, $bindings);
			else $value = strtr($value, $bindings);
			
			$rules[$key] = $value;
		}

		return $rules;
	}

	/**
	 * Prepare strtr() bindings.
	 *
	 * @access protected
	 * @param  string   $prefix
	 * @param  string   $suffix
	 * @return array
	 */
	protected function prepareBindings($prefix = '{', $suffix = '}')
	{
		$bindings = $this->bindings;

		foreach ($bindings as $key => $value)
		{
			$bindings["{$prefix}{$key}{$suffix}"] = $value;
		}

		return $bindings;
	}

	/**
	 * Run validation events and return the finalize rules.
	 *
	 * @access protected
	 * @param  array    $events
	 * @return array
	 */
	protected function runValidationEvents($events)
	{
		// Merge all the events.
		$events = array_merge($this->events, (array) $events);

		// Convert rules array to Fluent, in order to pass it by references 
		// in all event listening to this validation.
		$rules  = new Fluent($this->getBindedRules());

		foreach ((array) $events as $event) 
		{
			Event::fire($event, array( & $rules));
		}

		return $rules->getAttributes();
	}

	/**
	 * Set validation rules, this would override all previously defined 
	 * rules.
	 *
	 * @access public
	 * @return array
	 */
	public function setRules($rules = array())
	{
		return $this->rules = $rules;
	}
}
