<?php

namespace Orchestra\Support;

abstract class Serializer
{
    /**
     * Invoke the serializer.
     *
     * @param mixed $parameters
     *
     * @return mixed
     */
    public function __invoke(...$parameters)
    {
        return $this->serialize(...$parameters);
    }
}
