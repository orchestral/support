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
     * Meta types.
     *
     * @var array
     */
    protected $meta = ['includes', 'excludes'];

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

        foreach ($this->meta as $name) {
            $this->filterMetaType($name);
        }

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

    /**
     * Resolve includes for transformer.
     *
     * @param  string  $group
     * @param  array  $data
     * @param  mixed  $parameters
     *
     * @return array
     */
    protected function transformByMeta($meta, $data, ...$parameters)
    {
        $name  = Str::singular($meta);
        $types = $this->options[$meta] ?? null;

        if (empty($types)) {
            return $data;
        }

        foreach ($types as $type) {
            $method = $name.Str::studly($type);

            if (method_exists($this, $method)) {
                $data = $this->{$method}($data, ...$parameters);
            }
        }

        return $data;
    }

    /**
     * Merge meta options.
     *
     * @param string|array $meta
     * @param array        $options
     *
     * @return array
     */
    protected function merge($meta, array $options = [])
    {
        if (is_array($meta) && empty($options)) {
            $options = $meta;
            $meta    = null;
        }

        $options = array_merge(['includes' => null, 'excludes' => null], $options);

        foreach ($options as $key => $value) {
            $data = Arr::get($this->options, is_null($meta) ? $key : "{$key}.{$meta}", []);

            if (is_array($data)) {
                $options[$key] = array_merge($data, (array) $value);
            }
        }

        return $options;
    }

    /**
     * Get option by group.
     *
     * @param  string  $name
     *
     * @return array|null
     */
    protected function filterMetaType($name)
    {
        $types = $this->options[$name] ?? $this->request->input($name);

        if (is_string($types)) {
            $types = explode(',', $types);
        }

        $this->options[$name] = is_array($types) ? Arr::expand($types) : null;
    }
}
