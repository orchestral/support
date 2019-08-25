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
}
