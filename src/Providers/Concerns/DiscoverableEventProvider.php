<?php

namespace Orchestra\Support\Providers\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Foundation\Events\DiscoverEvents;

trait DiscoverableEventProvider
{
    /**
     * Get the discovered events and listeners for the application.
     *
     * @return array
     */
    protected function discoveredEvents()
    {
        if ($this->app->eventsAreCached()) {
            return require $this->app->getCachedEventsPath();
        }

        return $this->shouldDiscoverEvents()
                    ? $this->discoverEvents()
                    : [];
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }

    /**
     * Discover the events and listeners for the application.
     *
     * @return array
     */
    public function discoverEvents()
    {
        return Collection::make($this->discoverEventsWithin())
                    ->reduce(function ($discovered, $directory) {
                        return \array_merge_recursive(
                            $discovered,
                            DiscoverEvents::within($directory, $this->app->basePath())
                        );
                    }, []);
    }

    /**
     * Get the listener directories that should be used to discover events.
     *
     * @return array
     */
    protected function discoverEventsWithin()
    {
        return [
            $this->app->path('Listeners'),
        ];
    }
}
