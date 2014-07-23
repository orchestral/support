<?php namespace Orchestra\Support;

class Relic
{
    /**
     * Item or collection.
     *
     * @var array
     */
    protected $items = array();

    /**
     * Get a item value.
     *
     * @param  string   $key
     * @param  mixed    $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = array_get($this->items, $key, $default);

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
        return array_set($this->items, $key, value($value));
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
        array_forget($this->items, $key);
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
