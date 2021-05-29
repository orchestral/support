<?php

namespace Orchestra\Support\Providers\Concerns;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

trait EventProvider
{
    /**
     * Register the application's event listeners.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     *
     * @return void
     */
    public function registerEventListeners(DispatcherContract $dispatcher): void
    {
        $events = array_merge_recursive(
            (method_exists($this, 'discoveredEvents') ? $this->discoveredEvents() : []),
            $this->listens()
        );

        foreach ($events as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                $dispatcher->listen($event, $listener);
            }
        }

        foreach ($this->subscribe as $subscriber) {
            $dispatcher->subscribe($subscriber);
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
