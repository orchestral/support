<?php

namespace Orchestra\Support\Concerns;

use Illuminate\Contracts\Events\Dispatcher;

trait Observable
{
    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected static $dispatcher;

    /**
     * Register an observer.
     *
     * @param  object  $class
     *
     * @return void
     */
    public static function observe($class): void
    {
        $instance = new static();

        $className = \get_class($class);

        foreach ($instance->getObservableEvents() as $event) {
            if (method_exists($class, $event)) {
                static::registerObservableEvent($event, "{$className}@{$event}");
            }
        }
    }

    /**
     * Get the observer key.
     *
     * @param  string  $event
     *
     * @return string
     */
    protected function getObservableKey(string $event): string
    {
        return $event;
    }

    /**
     * Get the observable events.
     *
     * @return array
     */
    public function getObservableEvents(): array
    {
        return [];
    }

    /**
     * Register an event with the dispatcher.
     *
     * @param  string  $event
     * @param  \Closure|string  $callback
     *
     * @return void
     */
    protected static function registerObservableEvent(string $event, $callback): void
    {
        if (! isset(static::$dispatcher)) {
            return;
        }

        $event = (new static())->getObservableKey($event);

        static::$dispatcher->listen("{$event}: ".static::class, $callback);
    }

    /**
     * Fire the given event.
     *
     * @param  string  $event
     * @param  bool    $halt
     *
     * @return mixed
     */
    protected function fireObservableEvent(string $event, bool $halt)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        $className = \get_class($this);
        $event = $this->getObservableKey($event);

        $method = $halt ? 'until' : 'handle';

        return static::$dispatcher->$method("{$event}: {$className}", $this);
    }

    /**
     * Remove all of the event listeners for the observers.
     *
     * @return void
     */
    public static function flushEventListeners(): void
    {
        if (! isset(static::$dispatcher)) {
            return;
        }

        $instance = new static();
        $className = static::class;

        foreach ($instance->getObservableEvents() as $event) {
            $event = $instance->getObservableKey($event);

            static::$dispatcher->forget("{$event}: {$className}");
        }
    }

    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher|null
     */
    public static function getEventDispatcher(): ?Dispatcher
    {
        return static::$dispatcher;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     *
     * @return void
     */
    public static function setEventDispatcher(Dispatcher $dispatcher): void
    {
        static::$dispatcher = $dispatcher;
    }

    /**
     * Unset the event dispatcher instance.
     *
     * @return void
     */
    public static function unsetEventDispatcher(): void
    {
        static::$dispatcher = null;
    }
}
