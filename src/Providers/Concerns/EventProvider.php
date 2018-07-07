<?php

namespace Orchestra\Support\Providers\Concerns;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

trait EventProvider
{
    /**
     * Register the application's event listeners.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     *
     * @return void
     */
    public function registerEventListeners(DispatcherContract $events): void
    {
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }

        foreach ($this->subscribe as $subscriber) {
            $events->subscribe($subscriber);
        }
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens(): array
    {
        return $this->listen;
    }
}
