<?php

namespace Orchestra\Support;

use Illuminate\Support\Fluent as BaseFluent;
use Orchestra\Contracts\Support\Transformable;

class Fluent extends BaseFluent implements Transformable
{
    /**
     * Transform each item in the attributes using a callback.
     *
     * @param  callable  $callback
     *
     * @return $this
     */
    public function transform(callable $callback)
    {
        $this->attributes = $callback($this);

        return $this;
    }
}
