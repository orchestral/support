<?php

namespace Orchestra\Support\Traits;

use Orchestra\Support\Arr;
use Orchestra\Support\Str;

trait Transformable
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
     * Get request instance.
     *
     * @return \Illuminate\Http\Request
     */
    public function getRequest()
    {
        if (is_null($this->request)) {
            $this->setRequest(app()->refresh('request', $this, 'setRequest'));
        }

        return $this->request;
    }

    /**
     * Set request instance.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
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
            $meta = null;
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
        $name = Str::singular($meta);
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
     * Get option by group.
     *
     * @param  string  $name
     *
     * @return array|null
     */
    protected function filterMetaType($name)
    {
        $types = $this->options[$name] ?? $this->getRequest()->input($name);

        if (is_string($types)) {
            $types = explode(',', $types);
        }

        $this->options[$name] = is_array($types) ? Arr::expand($types) : null;
    }
}
