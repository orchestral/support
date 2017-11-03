<?php

namespace Orchestra\Support;

use InvalidArgumentException;
use Illuminate\Support\Fluent as BaseFluent;
use Illuminate\Contracts\Pagination\Paginator;
use Orchestra\Contracts\Support\Transformable;
use Illuminate\Support\Collection as BaseCollection;

abstract class Transformer
{
    use Traits\Transformable;

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
            $transformable = $instance->getCollection();
        } elseif ($instance instanceof Transformable
            || $instance instanceof BaseCollection
            || $instance instanceof BaseFluent
        ) {
            $transformable = $instance;
        } else {
            throw new InvalidArgumentException("Unable to transform {get_class($instance)}.");
        }

        return $transformable->transform($this);
    }

    /**
     * Invoke the transformation.
     *
     * @param  mixed  $instance
     * @param  array  $options
     *
     * @return mixed
     */
    public static function with($instance, array $options = [])
    {
        return (new static())->withOptions($options)->handle($instance);
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
        return $this->transformByMeta(
            'excludes',
            $this->transformByMeta(
                'includes',
                $this->transform(...$parameters),
                ...$parameters
            ),
            ...$parameters
        );
    }
}
