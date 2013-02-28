<?php namespace Orchestra\Support;

use RuntimeException;

abstract class Morph {
	
	/**
	 * Method prefix.
	 *
	 * @var string
	 */
	public static $prefix = '';

	/**
	 * Magic method to call passtru PHP functions.
	 */
	public static function __callStatic($method, $parameters)
	{
		if ( ! static::isCallable($method))
		{
			throw new RuntimeException("Unable to call [{$method}].");
		}

		return call_user_func_array(static::$prefix.snake_case($method), $parameters);
	}

	/**
	 * Determine if method is callable.
	 *
	 * @static
	 * @access public
	 * @param  string   $method
	 * @return bool
	 */
	public static function isCallable($method)
	{
		return is_callable(static::$prefix.snake_case($method));
	}
}
