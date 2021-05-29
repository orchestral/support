<?php

namespace Orchestra\Support\Concerns;

use Orchestra\Support\Fluent;

trait Descendible
{
    /**
     * Get last descendant node from items recursively.
     *
     * @param  array  $array
     * @param  string|null  $key
     *
     * @return \Illuminate\Support\Fluent|array|null
     */
    protected function descendants(array $array, ?string $key = null)
    {
        if (\is_null($key)) {
            return $array;
        }

        $keys = explode('.', $key);
        $first = array_shift($keys);

        if (! isset($array[$first])) {
            return null;
        }

        return $this->resolveLastDecendant($array[$first], $keys);
    }

    /**
     * Resolve last descendant node from items.
     *
     * @param  mixed  $array
     * @param  array  $keys
     *
     * @return \Orchestra\Support\Fluent|null
     */
    protected function resolveLastDecendant($array, array $keys): ?Fluent
    {
        $isLastDescendant = static function ($array, $segment) {
            return ! \is_array($array->childs) || ! isset($array->childs[$segment]);
        };

        // To retrieve the array item using dot syntax, we'll iterate through
        // each segment in the key and look for that value. If it exists,
        // we will return it, otherwise we will set the depth of the array
        // and look for the next segment.
        foreach ($keys as $segment) {
            if ($isLastDescendant($array, $segment)) {
                return $array;
            }

            $array = $array->childs[$segment];
        }

        return $array;
    }
}
