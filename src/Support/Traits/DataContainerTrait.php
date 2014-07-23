<?php namespace Orchestra\Support\Traits;

use Illuminate\Support\Arr;

trait DataContainerTrait
{
    /**
     * Item or collection.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Get a item value.
     *
     * @param  string   $key
     * @param  mixed    $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = Arr::get($this->items, $key, $default);

        if (is_null($value)) {
            return value($default);
        }

        return $value;
    }

    /**
     * Set a item value.
     *
     * @param  string   $key
     * @param  mixed    $value
     * @return mixed
     */
    public function set($key, $value = null)
    {
        return Arr::set($this->items, $key, value($value));
    }

    /**
     * Check if item key has a value.
     *
     * @param  string   $key
     * @return boolean
     */
    public function has($key)
    {
        return ! is_null($this->get($key));
    }

    /**
     * Remove a item key.
     *
     * @param  string   $key
     * @return void
     */
    public function forget($key)
    {
        Arr::forget($this->items, $key);
    }

    /**
     * Get all available items.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }
}
