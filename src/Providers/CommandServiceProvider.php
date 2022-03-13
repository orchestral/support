<?php

namespace Orchestra\Support\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class CommandServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Additional provides.
     *
     * @var array
     */
    protected $provides = [];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        foreach (array_keys($this->commands) as $command) {
            $method = "register{$command}Command";

            $this->{$method}();
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_merge(array_values($this->commands), $this->provides);
    }
}
