<?php namespace Orchestra\Support\Providers;

use Orchestra\Support\Providers\Traits\EventProviderTrait;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

abstract class EventServiceProvider extends BaseServiceProvider
{
    use EventProviderTrait;

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [];

    /**
     * Register the application's event listeners.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        $this->registerEventListeners($events);
    }
}
