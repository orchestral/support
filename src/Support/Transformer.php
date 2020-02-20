<?php

namespace Orchestra\Support;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Fluent as BaseFluent;
use InvalidArgumentException;
use Orchestra\Contracts\Support\Transformable;

abstract class Transformer
{
    /**
     * Handle transformation.
     *
     * @param  mixed  $instance
     *
     * @return mixed
     */
    public function handle($instance)
    {
        if ($instance instanceof Paginator) {
            return $instance->setCollection(
                $instance->getCollection()->transform($this)
            );
        } elseif ($instance instanceof Transformable || $instance instanceof BaseCollection) {
            $transformable = $instance;
        } elseif ($instance instanceof BaseFluent) {
            $transformable = new Fluent($instance->getAttributes());
        } else {
            throw new InvalidArgumentException('Unable to transform '.\get_class($instance).'."');
        }

        return $transformable->transform($this);
    }

    /**
     * Make the transformer..
     *
     * @param  mixed  $instance
     *
     * @return mixed
     */
    public static function make($instance)
    {
        return (new static())->handle($instance);
    }

    /**
     * Invoke the transformer.
     *
     * @param  mixed  $parameters
     *
     * @return mixed
     */
    public function __invoke(...$parameters)
    {
        return $this->transform(...$parameters);
    }
}
