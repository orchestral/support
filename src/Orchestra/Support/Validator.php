<?php namespace Orchestra\Support;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator as V;

abstract class Validator {
	
	/**
	 * List of rules.
	 *
	 * @var array
	 */
	protected static $rules = array();

	/**
	 * List of events.
	 *
	 * @var array
	 */
	protected static $events = array();

	/**
	 * List of bindings.
	 *
	 * @var array
	 */
	protected $bindings = array();

	/**
	 * Create a scope scenario.
	 *
	 * @access public
	 * @param  string   $scenario
	 * @return self
	 */
	public function on($scenario)
	{
		$method = 'on'.ucfirst($scenario);

		if (method_exists($this, $method)) $this->{$method}();

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
	 * @return void
	 */
	public function with($input, $events = array())
	{
		$rules = $this->getBindedRules();
		$this->runValidationEvents($events);

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
		$rules    = static::$rules;
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
	 * Run validation events.
	 *
	 * @access protected
	 * @return void
	 */
	protected function runValidationEvents($events)
	{
		$events = array_merge(static::$events, (array) $events);

		foreach ((array) $events as $event) 
		{
			Event::fire($event, array( & $rules));
		}
	}
}
