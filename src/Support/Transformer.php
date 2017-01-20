<?php

namespace Orchestra\Support;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Contracts\Pagination\Paginator;
use Orchestra\Contracts\Support\Transformable;
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
     * The request implementation.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Construct a new transformer.
     *
     * @param array $options
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

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
        } elseif ($instance instanceof Transformable || $instance instanceof BaseCollection) {
            $transformable = $instance;
        } else {
            throw new InvalidArgumentException("Unable to transform {get_class($instance)}.");
        }

        return $transformable->transform($this);
    }

    /**
     * Add options.
     *
     * @param  array  $options
     *
     * @return $this
     */
    public function withOptions(array $options = [])
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Invoke the transformation.
     *
     * @param  mixed  $instance
     * @param  array  $parameters
     *
     * @return mixed
     */
    public static function with($instance, array $parameters)
    {
        return (new static(app('request')))
                    ->withOptions(...$parameters)
                    ->handle($instance);
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
        return $this->transformByGroup('exclude', $this->transformByGroup('include', $this->transform(...$parameters)));
    }

    /**
     * Resolve includes for transformer.
     *
     * @param  array  $transformed
     *
     * @return array
     */
    protected function transformByGroup($group, array $transformed = [])
    {
        $types = $this->getOptionByGroup("{$group}s");

        if (empty($types)) {
            return $transformed;
        }

        foreach ($types as $type) {
            $method = $group.Str::studly($type);

            if (method_exists($this, $method)) {
                $transformed = $this->{$method}($transformed);
            }
        }

        return $transformed;
    }

    /**
     * Get option by group.
     *
     * @param  string  $group
     *
     * @return array|null
     */
    protected function getOptionByGroup($group)
    {
        $types = Arr::get($this->options, $group, $this->request->input($group));

        if (! is_string($types)) {
            return $types;
        }

        return explode(',', $types);
    }
}
