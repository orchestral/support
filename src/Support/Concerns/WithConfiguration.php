<?php

namespace Orchestra\Support\Concerns;

trait WithConfiguration
{
    /**
     * Configuration values.
     *
     * @var array
     */
    protected $configurations = [];

    /**
     * Get configuration values.
     *
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configurations;
    }

    /**
     * Set configuration.
     *
     * @param  array  $configurations
     *
     * @return $this
     */
    public function setConfiguration(array $configurations)
    {
        $this->configurations = $configurations;

        return $this;
    }

    /**
     * Set configuration from.
     *
     * @param  string  $namespace
     *
     * @return $this
     */
    public function setConfigurationFrom(string $namespace)
    {
        return $this->setConfiguration($this->config->get($namespace));
    }
}
