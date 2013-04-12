<?php namespace Orchestra\Support;

use Closure;

abstract class Manager extends \Illuminate\Support\Manager {

	/**
	 * Create a new driver instance.
	 *
	 * @param  string  $driverName
	 * @return mixed
	 */
	protected function createDriver($driverName)
	{
		list($driver, $name) = $this->getDriverName($driverName);

		$method = 'create'.ucfirst($driver).'Driver';

		// We'll check to see if a creator method exists for the given driver. If not we
		// will check for a custom driver creator, which allows developers to create
		// drivers using their own customized driver creator Closure to create it.
		if (isset($this->customCreators[$driver]))
		{
			return $this->callCustomCreator($driver, $name);
		}
		elseif (method_exists($this, $method))
		{
			return call_user_func(array($this, $method), $name);
		}

		throw new \InvalidArgumentException("Driver [$driver] not supported.");
	}

	/**
	 * Call a custom driver creator.
	 *
	 * @param  string  $driver
	 * @return mixed
	 */
	protected function callCustomCreator($driver, $name)
	{
		return call_user_func($this->customCreators[$driver], $this->app, $name);
	}

	/**
	 * Get driver name.
	 * 
	 * @access protected
	 * @param  string   $driverName
	 * @return array
	 */
	protected function getDriverName($driverName)
	{
		if (false === strpos($driverName, '.')) $driverName = $driverName.'.default';

		return explode('.', $driverName, 2);
	}

}