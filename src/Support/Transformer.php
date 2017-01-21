<?php

namespace Orchestra\Support;

use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Fluent as BaseFluent;
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
     * @param \Illuminate\Http\Request $request
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
        } elseif ($instance instanceof Transformable
            || $instance instanceof BaseCollection
            || $instance instanceof BaseFluent) {
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
     * @param  array  $options
     *
     * @return mixed
     */
    public static function with($instance, array $options = [])
    {
        return (new static(app('request')))
                    ->withOptions($options)
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
        return $this->transformByGroup(
            'exclude',
            $this->transformByGroup(
                'include',
                $this->transform(...$parameters),
                ...$parameters
            ),
            ...$parameters
        );
    }

    /**
     * Resolve includes for transformer.
     *
     * @param  string  $group
     * @param  array  $data
     * @param  mixed  $parameters
     *
     * @return array
     */
    protected function transformByGroup($group, $data, ...$parameters)
    {
        $types = $this->getOptionByGroup("{$group}s");

        if (empty($types)) {
            return $data;
        }

        foreach ($types as $type) {
            $method = $group.Str::studly($type);

            if (method_exists($this, $method)) {
                $data = $this->{$method}($data, ...$parameters);
            }
        }

        return $data;
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
