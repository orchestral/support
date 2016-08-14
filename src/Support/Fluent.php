<?php namespace Orchestra\Support;

use Illuminate\Support\Fluent as BaseFluent;

class Fluent extends BaseFluent
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
