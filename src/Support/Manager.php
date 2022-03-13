<?php

namespace Orchestra\Support;

use Illuminate\Support\Manager as BaseManager;
use InvalidArgumentException;

abstract class Manager extends BaseManager
{
    /**
     * Define blacklisted character in name.
     *
     * @var array
     */
    protected $blacklisted = ['.'];

    /**
     * Create a new instance.
     *
     * @param  string  $driver
     *
     * @return object
     */
    public function make($driver = null)
    {
        return $this->driver($driver);
    }

    /**
     * Create a new driver instance.
     *
     * @param  string  $driverName
     *
     * @return object
     */
    protected function createDriver($driverName)
    {
        [$driver, $name] = $this->getDriverName($driverName);

        $method = 'create'.Str::studly($driver).'Driver';

        // We'll check to see if a creator method exists for the given driver.
        // If not we will check for a custom driver creator, which allows
        // developers to create drivers using their own customized driver
        // creator Closure to create it.
        if (isset($this->customCreators[$driver])) {
            return $this->callCustomCreator($driverName);
        } elseif (method_exists($this, $method)) {
            return $this->{$method}($name);
        }

        throw new InvalidArgumentException("Driver [$driver] not supported.");
    }

    /**
     * Call a custom driver creator.
     *
     * @param  string  $driverName
     *
     * @return object
     */
    protected function callCustomCreator($driverName)
    {
        [$driver, $name] = $this->getDriverName($driverName);

        return $this->customCreators[$driver]($this->container, $name);
    }

    /**
     * Get driver name.
     *
     * @param  string  $driverName
     *
     * @return array
     */
    protected function getDriverName(string $driverName): array
    {
        if (false === strpos($driverName, '.')) {
            $driverName = "{$driverName}.default";
        }

        [$driver, $name] = explode('.', $driverName, 2);

        $this->checkNameIsNotBlacklisted($name);

        return [$driver, $name];
    }

    /**
     * Check if name is not blacklisted.
     *
     * @param  string  $name
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function checkNameIsNotBlacklisted(string $name): void
    {
        if (Str::contains($name, $this->blacklisted)) {
            throw new InvalidArgumentException("Invalid character in driver name [{$name}].");
        }
    }
}
