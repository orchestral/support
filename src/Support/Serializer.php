<?php

namespace Orchestra\Support;

use Illuminate\Contracts\Pagination\Paginator;

abstract class Serializer
{
    /**
     * Data serializer key name.
     *
     * @var string
     */
    protected $key = 'data';

    /**
     * Invoke the serializer.
     *
     * @param mixed $parameters
     *
     * @return mixed
     */
    public function __invoke(...$parameters)
    {
        if (method_exists($this, 'serialize')) {
            return $this->serialize(...$parameters);
        }

        return $this->serializeBasicDataset($parameters[0]);
    }

    /**
     * Resolve paginated dataset.
     *
     * @param  mixed  $dataset
     * @return array
     */
    final protected function serializeBasicDataset($dataset)
    {
        $key = $this->resolveSerializerKey($dataset);

        if ($dataset instanceof Paginator) {
            $collection = $dataset->toArray();

            $collection[$key] = $collection['data'];
            unset($collection['data']);

            return $collection;
        }

        return [
            $key => $dataset->toArray(),
        ];
    }

    /**
     * Resolve serializer key.
     *
     * @param  mixed  $dataset
     *
     * @return string
     */
    protected function resolveSerializerKey($dataset)
    {
        if ($dataset instanceof Collection || $dataset instanceof Paginator) {
            return Str::plural($this->key);
        }

        return $this->key;
    }
}
