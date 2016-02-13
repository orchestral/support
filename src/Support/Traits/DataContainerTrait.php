<?php namespace Orchestra\Support\Traits;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\EncryptException;

trait DataContainerTrait
{
    /**
     * The encrypter implementation.
     *
     * @var \Illuminate\Contracts\Encryption\Encrypter|null
     */
    protected $encrypter;

    /**
     * Item or collection.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Get a item value.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
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
     * Get an encrypted item value.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    public function secureGet($key, $default = null)
    {
        $value = $this->get($key, $default);

        if ($this->encrypter instanceof Encrypter) {
            try {
                return $this->encrypter->decrypt($value);
            } catch (DecryptException $e) {
                //
            }
        }

        return $value;
    }

    /**
     * Set a item value.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return mixed
     */
    public function set($key, $value = null)
    {
        return Arr::set($this->items, $key, value($value));
    }

    /**
     * Set an ecrypted item value.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return mixed
     */
    public function secureSet($key, $value = null)
    {
        try {
            if ($this->encrypter instanceof Encrypter) {
                $value = $this->encrypter->encrypt($value);
            }
        } catch (EncryptException $e) {
            //
        }

        return $this->set($key, $value);
    }

    /**
     * Check if item key has a value.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function has($key)
    {
        return ! is_null($this->get($key));
    }

    /**
     * Remove a item key.
     *
     * @param  string  $key
     *
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
