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
        $events = \array_merge_recursive(
            (\method_exists($this, 'discoveredEvents') ? $this->discoveredEvents() : []),
            $this->listens()
        );

        foreach ($events as $event => $listeners) {
            foreach (\array_unique($listeners) as $listener) {
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
