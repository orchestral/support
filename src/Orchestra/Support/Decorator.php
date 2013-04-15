<?php namespace Orchestra\Support;

use BadMethodCallException;

class Decorator {
	
	/**
	 * The registered custom macros.
	 *
	 * @var array
	 */
	protected $macros = array();

	/**
	 * Registers a custom macro.
	 *
	 * @param  string   $name
	 * @param  Closure  $macro
	 * @return void
	 */
	public function macro($name, $macro)
	{
		$this->macros[$name] = $macro;
	}

	/**
	 * Dynamically handle calls to custom macros.
	 *
	 * @access public
	 * @param  string   $method
	 * @param  array    $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		if (isset($this->macros[$method]))
		{
			return call_user_func_array($this->macros[$method], $parameters);
		}

		throw new BadMethodCallException("Method [$method] does not exist.");
	}
}
