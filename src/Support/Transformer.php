<?php

namespace Orchestra\Support;

use InvalidArgumentException;
use Orchestra\Contracts\Support\Tranformable;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection as BaseCollection;

abstract class Transformer
{
    /**
     * Transformers' options.
     *
     * @var array
     */
    protected $options = [];
    /**
     * Construct a new transformer.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Invoke the transformation.
     *
     * @param  mixed $instance
     * @param  array  $parameters
     * @return mixed
     */
    public static function with($instance, array $parameters)
    {
        if ($instance instanceof Paginator) {
            $transformable = $instance->getCollection();
        } elseif ($transformable instanceof Transformable || $transformable instanceof BaseCollection) {
            $transformable = $instance;
        } else {
            throw new InvalidArgumentException("Unable to transform {get_class($instance)}.");
        }

        return $transformable->transform(new static(...$parameters));
    }

    /**
     * Invoke the transformer.
     *
     * @param mixed $parameters
     *
     * @return mixed
     */
    public function __invoke(...$parameters)
    {
        return $this->transform(...$parameters);
    }
}
