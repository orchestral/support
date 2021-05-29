<?php

namespace Orchestra\Support\Concerns;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Support\Arr;

trait DataContainer
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
     * Removed item or collection.
     *
     * @var array
     */
    protected $removedItems = [];

    /**
     * Get a item value.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }

    /**
     * Get an encrypted item value.
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return mixed
     */
    public function secureGet(string $key, $default = null)
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
    public function set(string $key, $value = null)
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
    public function secureSet(string $key, $value = null)
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
    public function has(string $key): bool
    {
        return ! \is_null($this->get($key));
    }

    /**
     * Remove a item key.
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function forget(string $key): bool
    {
        $items = $this->items;

        array_push($this->removedItems, $key);
        Arr::forget($items, $key);

        $this->items = $items;

        return true;
    }

    /**
     * Get all available items.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get all available items including deleted.
     *
     * @return array
     */
    public function allWithRemoved(): array
    {
        $items = $this->all();

        foreach ($this->removedItems as $deleted) {
            Arr::set($items, $deleted, null);
        }

        return $items;
    }
}
