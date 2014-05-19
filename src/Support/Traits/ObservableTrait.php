<?php namespace Orchestra\Support\Traits;

use Illuminate\Events\Dispatcher;

trait ObservableTrait
{

    /**
     * User exposed observable events.
     *
     * @var array
     */
    protected $observables = [];

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Events\Dispatcher
     */
    protected static $dispatcher;

    /**
     * Register an observer.
     *
     * @param  object   $class
     * @return void
     */
    public static function observe($class)
    {
        $instance = new static;

        $className = get_class($class);

        foreach ($instance->getObservableEvents() as $event) {
            if (method_exists($class, $event)) {
                static::registerObservableEvent($event, "{$className}@{$event}");
            }
        }
    }

    /**
     * Get the observer key.
     *
     * @param  string   $event
     * @return string
     */
    protected function getObservableKey($event)
    {
        return $event;
    }

    /**
     * Get the observable events.
     *
     * @return array
     */
    protected function getObservableEvents()
    {
        return $this->observables;
    }

    /**
     * Register an event with the dispatcher.
     *
     * @param  string           $event
     * @param  \Closure|string  $callback
     * @return void
     */
    protected static function registerObservableEvent($event, $callback)
    {
        if (! isset(static::$dispatcher)) {
            return ;
        }

        $className = get_called_class();

        $event = with(new static)->getObservableKey($event);

        static::$dispatcher->listen("{$event}: {$className}", $callback);
    }

    /**
     * Fire the given event.
     *
     * @param  string   $event
     * @param  boolean  $halt
     * @return mixed
     */
    protected function fireObservableEvent($event, $halt)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        $className = get_class($this);
        $event = $this->getObservableKey($event);

        $method = $halt ? 'until' : 'fire';

        return static::$dispatcher->$method("{$event}: {$className}", $this);
    }

    /**
     * Remove all of the event listeners for the observers.
     *
     * @return void
     */
    public static function flushEventListeners()
    {
        if (! isset(static::$dispatcher)) {
            return ;
        }

        $instance  = new static;
        $className = get_called_class();

        foreach ($instance->getObservableEvents() as $event) {
            $event = $instance->getObservableKey($event);

            static::$dispatcher->forget("{$event}: {$className}");
        }
    }

    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Events\Dispatcher
     */
    public static function getEventDispatcher()
    {
        return static::$dispatcher;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param  \Illuminate\Events\Dispatcher    $dispatcher
     * @return void
     */
    public static function setEventDispatcher(Dispatcher $dispatcher)
    {
        static::$dispatcher = $dispatcher;
    }

    /**
     * Unset the event dispatcher instance.
     *
     * @return void
     */
    public static function unsetEventDispatcher()
    {
        static::$dispatcher = null;
    }
}
