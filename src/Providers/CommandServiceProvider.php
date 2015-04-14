<?php namespace Orchestra\Support\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class CommandServiceProvider extends BaseServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        foreach (array_keys($this->commands) as $command) {
            $method = "register{$command}Command";

            call_user_func_array([$this, $method], []);
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
        return array_values($this->commands);
    }
}
